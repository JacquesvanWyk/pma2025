<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Song;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MusicController extends Controller
{
    public function index()
    {
        $albums = Album::published()
            ->withCount('songs')
            ->latest('release_date')
            ->paginate(12);

        $featuredAlbum = Album::published()
            ->featured()
            ->with(['songs' => fn ($q) => $q->published()->orderBy('track_number')])
            ->first();

        return view('music.index', compact('albums', 'featuredAlbum'));
    }

    public function show(string $slug)
    {
        $album = Album::where('slug', $slug)
            ->published()
            ->with(['songs' => fn ($q) => $q->published()->orderBy('track_number')])
            ->firstOrFail();

        $relatedAlbums = Album::published()
            ->where('id', '!=', $album->id)
            ->withCount('songs')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('music.show', compact('album', 'relatedAlbums'));
    }

    public function downloadSong(Album $album, Song $song): JsonResponse
    {
        if (! $album->isReleased()) {
            return response()->json(['error' => 'This album has not been released yet'], 403);
        }

        if (! $song->wav_file || ! Storage::disk('public')->exists($song->wav_file)) {
            return response()->json(['error' => 'Audio file not found'], 404);
        }

        $song->incrementAudioDownload();

        $extension = pathinfo($song->wav_file, PATHINFO_EXTENSION) ?: 'mp3';
        $filename = Str::slug($album->artist.' - '.$song->title).'.'.$extension;
        $r2Path = 'downloads/audio/'.$filename;

        if (! Storage::disk('r2')->exists($r2Path)) {
            $localPath = Storage::disk('public')->path($song->wav_file);
            $stream = fopen($localPath, 'r');
            Storage::disk('r2')->writeStream($r2Path, $stream, ['visibility' => 'public']);
            fclose($stream);
        }

        return response()->json(['url' => Storage::disk('r2')->url($r2Path), 'filename' => $filename]);
    }

    public function downloadSongVideo(Album $album, Song $song): JsonResponse
    {
        if (! $album->isReleased()) {
            return response()->json(['error' => 'This album has not been released yet'], 403);
        }

        if (! $song->mp4_video || ! Storage::disk('public')->exists($song->mp4_video)) {
            return response()->json(['error' => 'Video file not found'], 404);
        }

        $song->incrementVideoDownload();

        $filename = Str::slug($album->artist.' - '.$song->title).'.mp4';
        $r2Path = 'downloads/video/'.$filename;

        if (! Storage::disk('r2')->exists($r2Path)) {
            $localPath = Storage::disk('public')->path($song->mp4_video);
            $stream = fopen($localPath, 'r');
            Storage::disk('r2')->writeStream($r2Path, $stream, ['visibility' => 'public']);
            fclose($stream);
        }

        return response()->json(['url' => Storage::disk('r2')->url($r2Path), 'filename' => $filename]);
    }

    public function downloadSongLyrics(Album $album, Song $song): JsonResponse
    {
        if (! $album->isReleased()) {
            return response()->json(['error' => 'This album has not been released yet'], 403);
        }

        if (! $song->lyrics) {
            return response()->json(['error' => 'Lyrics not available'], 404);
        }

        $song->incrementLyricsDownload();

        $lyricsHash = substr(md5($song->lyrics), 0, 8);
        $filename = Str::slug($album->artist.' - '.$song->title.' - Lyrics').'.pdf';
        $r2Path = 'downloads/lyrics/'.Str::slug($album->artist.' - '.$song->title.' - Lyrics').'-'.$lyricsHash.'.pdf';

        if (! Storage::disk('r2')->exists($r2Path)) {
            $pdf = Pdf::loadView('pdf.lyrics', [
                'song' => $song,
                'album' => $album,
            ]);

            $pdf->setPaper('A4', 'portrait');

            Storage::disk('r2')->put($r2Path, $pdf->output(), 'public');
        }

        return response()->json(['url' => Storage::disk('r2')->url($r2Path), 'filename' => $filename]);
    }

    public function downloadSongBundle(Album $album, Song $song): JsonResponse
    {
        if (! $album->isReleased()) {
            return response()->json(['error' => 'This album has not been released yet'], 403);
        }

        if (! $song->hasBundle()) {
            return response()->json(['error' => 'Download is being prepared. Please try again in a few minutes.'], 503);
        }

        $url = $song->getBundleUrl();
        if (! $url) {
            return response()->json(['error' => 'Download not available'], 404);
        }

        $song->incrementBundleDownload();

        $filename = Str::slug($album->artist.' - '.$song->title).'.zip';

        return response()->json(['url' => $url, 'filename' => $filename]);
    }

    public function downloadAlbum(Album $album, string $type = 'full'): JsonResponse
    {
        if (! $album->isReleased()) {
            return response()->json(['error' => 'This album has not been released yet'], 403);
        }

        if (! $album->hasBundles()) {
            return response()->json(['error' => 'Downloads are being prepared. Please try again in a few minutes.'], 503);
        }

        $url = $album->getBundleUrl($type);
        if (! $url) {
            return response()->json(['error' => 'Download not available'], 404);
        }

        match ($type) {
            'audio' => $album->incrementAudioDownload(),
            'video' => $album->incrementVideoDownload(),
            default => $album->incrementFullDownload(),
        };

        $songs = $album->songs()->published()->get();
        foreach ($songs as $song) {
            $song->incrementDownload();
        }

        return response()->json(['url' => $url]);
    }
}
