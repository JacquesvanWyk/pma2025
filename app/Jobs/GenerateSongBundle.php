<?php

namespace App\Jobs;

use App\Models\Song;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class GenerateSongBundle implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(public Song $song) {}

    public function handle(): void
    {
        $song = $this->song->fresh();
        $album = $song->album;

        if (! $album) {
            Log::warning("Song {$song->id} has no album, skipping bundle generation");

            return;
        }

        $song->clearBundle();

        if (! $song->wav_file && ! $song->mp4_video && ! $song->lyrics) {
            Log::info("Song {$song->id} has no downloadable content, skipping bundle generation");

            return;
        }

        $zipFileName = Str::slug($album->artist.' - '.$song->title).'.zip';
        $r2Path = 'downloads/bundles/'.$zipFileName;

        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.$zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("Could not create zip file for song {$song->id}");

            return;
        }

        $lyricsPath = null;

        if ($song->wav_file) {
            $audioPath = Storage::disk('public')->path($song->wav_file);
            if (file_exists($audioPath)) {
                $audioFileName = Str::slug($song->title).'.wav';
                $zip->addFile($audioPath, $audioFileName);
            }
        }

        if ($song->mp4_video) {
            $videoPath = Storage::disk('public')->path($song->mp4_video);
            if (file_exists($videoPath)) {
                $videoFileName = Str::slug($song->title).'.mp4';
                $zip->addFile($videoPath, $videoFileName);
            }
        }

        if ($song->lyrics) {
            $lyricsPath = $this->generateLyricsPdf($album, $song);
            if ($lyricsPath && file_exists($lyricsPath)) {
                $lyricsFileName = Str::slug($song->title).' - Lyrics.pdf';
                $zip->addFile($lyricsPath, $lyricsFileName);
            }
        }

        $zip->close();

        $stream = fopen($tempPath, 'r');
        if (! $stream) {
            @unlink($tempPath);
            if ($lyricsPath) {
                @unlink($lyricsPath);
            }
            Log::error("Could not read zip file for song {$song->id}");

            return;
        }

        Storage::disk('r2')->writeStream($r2Path, $stream, ['visibility' => 'public']);
        fclose($stream);

        @unlink($tempPath);
        if ($lyricsPath) {
            @unlink($lyricsPath);
        }

        $song->update([
            'bundle_path' => $r2Path,
            'bundle_generated_at' => now(),
        ]);

        Log::info("Song bundle generated for song {$song->id}");
    }

    protected function generateLyricsPdf($album, Song $song): ?string
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

        $filename = Str::slug($song->title).'-lyrics.pdf';
        $path = $tempDir.'/'.$filename;

        $pdf->save($path);

        return $path;
    }
}
