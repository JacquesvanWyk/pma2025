<?php

namespace App\Jobs;

use App\Models\Album;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class GenerateAlbumDownloads implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(public Album $album) {}

    public function handle(): void
    {
        $album = $this->album->fresh();

        $album->clearBundles();

        $songs = $album->songs()->published()->where(function ($query) {
            $query->whereNotNull('wav_file')->orWhereNotNull('mp4_video')->orWhereNotNull('lyrics');
        })->get();

        if ($songs->isEmpty()) {
            Log::info("No songs to bundle for album {$album->id}");

            return;
        }

        $baseFileName = Str::slug($album->artist.' - '.$album->title);

        $audioBundlePath = $this->generateBundle($album, $songs, 'audio', $baseFileName);
        $videoBundlePath = $this->generateBundle($album, $songs, 'video', $baseFileName);
        $fullBundlePath = $this->generateBundle($album, $songs, 'full', $baseFileName);

        $album->update([
            'audio_bundle_path' => $audioBundlePath,
            'video_bundle_path' => $videoBundlePath,
            'full_bundle_path' => $fullBundlePath,
            'bundles_generated_at' => now(),
        ]);

        Log::info("Album bundles generated for album {$album->id}");
    }

    protected function generateBundle(Album $album, $songs, string $type, string $baseFileName): ?string
    {
        $typeSuffix = match ($type) {
            'audio' => '-audio',
            'video' => '-audio-video',
            default => '-full',
        };

        $zipFileName = $baseFileName.$typeSuffix.'.zip';
        $r2Path = 'downloads/albums/'.$zipFileName;

        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.$zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("Could not create zip file for album {$album->id}");

            return null;
        }

        $zip->addEmptyDir('Audio/MP3');
        $zip->addEmptyDir('Audio/WAV');
        if ($type === 'video' || $type === 'full') {
            $zip->addEmptyDir('Video');
        }
        if ($type === 'full') {
            $zip->addEmptyDir('Lyrics');
        }

        $lyricsPaths = [];

        foreach ($songs as $song) {
            // Add MP3 file
            if ($song->wav_file) {
                $mp3Path = Storage::disk('public')->path($song->wav_file);
                if (file_exists($mp3Path)) {
                    $mp3FileName = sprintf('%02d - %s.mp3', $song->track_number, $song->title);
                    $zip->addFile($mp3Path, 'Audio/MP3/'.$mp3FileName);
                }
            }

            // Add WAV file
            if ($song->wav_file_path) {
                $wavPath = Storage::disk('public')->path($song->wav_file_path);
                if (file_exists($wavPath)) {
                    $wavFileName = sprintf('%02d - %s.wav', $song->track_number, $song->title);
                    $zip->addFile($wavPath, 'Audio/WAV/'.$wavFileName);
                }
            }

            if (($type === 'video' || $type === 'full') && $song->mp4_video) {
                $videoPath = Storage::disk('public')->path($song->mp4_video);
                if (file_exists($videoPath)) {
                    $videoFileName = sprintf('%02d - %s.mp4', $song->track_number, $song->title);
                    $zip->addFile($videoPath, 'Video/'.$videoFileName);
                }
            }

            if ($type === 'full' && $song->lyrics) {
                $lyricsPath = $this->generateLyricsPdf($album, $song);
                if ($lyricsPath && file_exists($lyricsPath)) {
                    $lyricsFileName = sprintf('%02d - %s - Lyrics.pdf', $song->track_number, $song->title);
                    $zip->addFile($lyricsPath, 'Lyrics/'.$lyricsFileName);
                    $lyricsPaths[] = $lyricsPath;
                }
            }
        }

        $zip->close();

        $stream = fopen($tempPath, 'r');
        if (! $stream) {
            @unlink($tempPath);
            foreach ($lyricsPaths as $path) {
                @unlink($path);
            }
            Log::error("Could not read zip file for album {$album->id}");

            return null;
        }

        Storage::disk('r2')->writeStream($r2Path, $stream, ['visibility' => 'public']);
        fclose($stream);

        @unlink($tempPath);
        foreach ($lyricsPaths as $path) {
            @unlink($path);
        }

        return $r2Path;
    }

    protected function generateLyricsPdf(Album $album, $song): ?string
    {
        if (! $song->lyrics) {
            return null;
        }

        $pdf = Pdf::loadView('pdf.lyrics', [
            'song' => $song,
            'album' => $album,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $tempDir = storage_path('app/temp/lyrics');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $lyricsHash = substr(md5($song->lyrics), 0, 8);
        $filename = sprintf('%02d - %s - Lyrics - %s.pdf', $song->track_number, $song->title, $lyricsHash);
        $path = $tempDir.'/'.$filename;

        $pdf->save($path);

        return $path;
    }
}
