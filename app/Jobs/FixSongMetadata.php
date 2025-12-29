<?php

namespace App\Jobs;

use App\Models\Song;
use getID3;
use getid3_writetags;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FixSongMetadata implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Song $song
    ) {}

    public function handle(): void
    {
        if (! $this->song->wav_file) {
            return;
        }

        $album = $this->song->album;
        if (! $album) {
            return;
        }

        $filePath = Storage::disk('public')->path($this->song->wav_file);

        if (! file_exists($filePath)) {
            Log::warning("FixSongMetadata: File not found - {$filePath}");

            return;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['mp3'])) {
            Log::info("FixSongMetadata: Skipping non-MP3 file - {$extension}");

            return;
        }

        // Initialize getID3 engine and read existing tags
        $getID3 = new getID3;
        $fileInfo = $getID3->analyze($filePath);

        $tagWriter = new getid3_writetags;
        $tagWriter->filename = $filePath;
        $tagWriter->tagformats = ['id3v2.3'];
        $tagWriter->overwrite_tags = true;
        $tagWriter->remove_other_tags = false;
        $tagWriter->tag_encoding = 'UTF-8';

        $tagWriter->tag_data = [
            'title' => [$this->song->title],
            'artist' => [$album->artist],
            'album' => [$album->title],
            'track_number' => [(string) $this->song->track_number],
            'band' => [$album->artist],
            'year' => [(string) now()->year],
            'genre' => ['Christian'],
            'comment' => ['PMA Worship - pioneermissionsafrica.co.za'],
        ];

        // Preserve existing cover art if present
        if (isset($fileInfo['id3v2']['APIC'])) {
            $tagWriter->tag_data['attached_picture'] = [];
            foreach ($fileInfo['id3v2']['APIC'] as $apic) {
                $tagWriter->tag_data['attached_picture'][] = [
                    'data' => $apic['data'],
                    'picturetypeid' => $apic['picturetypeid'] ?? 3,
                    'description' => $apic['description'] ?? 'Cover',
                    'mime' => $apic['mime'] ?? 'image/jpeg',
                ];
            }
        }

        if ($tagWriter->WriteTags()) {
            Log::info("FixSongMetadata: Updated metadata for {$this->song->title}");
        } else {
            Log::error("FixSongMetadata: Failed for {$this->song->title}", [
                'errors' => $tagWriter->errors,
                'warnings' => $tagWriter->warnings,
            ]);
        }
    }
}
