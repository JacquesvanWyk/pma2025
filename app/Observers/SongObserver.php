<?php

namespace App\Observers;

use App\Jobs\FixSongMetadata;
use App\Jobs\GenerateAlbumDownloads;
use App\Jobs\GenerateSongBundle;
use App\Models\Song;

class SongObserver
{
    protected array $bundleRelevantFields = [
        'wav_file',
        'mp4_video',
        'lyrics',
        'title',
        'track_number',
        'is_published',
    ];

    protected array $metadataRelevantFields = [
        'wav_file',
        'title',
        'track_number',
    ];

    public function created(Song $song): void
    {
        if ($song->album_id && $song->wav_file) {
            FixSongMetadata::dispatch($song);
        }

        if ($song->album_id && $this->hasBundleableContent($song)) {
            GenerateSongBundle::dispatch($song)->delay(now()->addSeconds(10));
            GenerateAlbumDownloads::dispatch($song->album)->delay(now()->addSeconds(15));
        }
    }

    public function updated(Song $song): void
    {
        if ($song->wasChanged($this->metadataRelevantFields) && $song->wav_file) {
            FixSongMetadata::dispatch($song);
        }

        if ($this->shouldRegenerateBundles($song)) {
            if ($this->hasBundleableContent($song)) {
                GenerateSongBundle::dispatch($song)->delay(now()->addSeconds(10));
            } else {
                $song->clearBundle();
            }

            if ($song->album_id) {
                GenerateAlbumDownloads::dispatch($song->album)->delay(now()->addSeconds(15));
            }
        }
    }

    public function deleted(Song $song): void
    {
        $song->clearBundle();

        if ($song->album_id) {
            GenerateAlbumDownloads::dispatch($song->album);
        }
    }

    public function restored(Song $song): void
    {
        if ($song->album_id && $this->hasBundleableContent($song)) {
            GenerateSongBundle::dispatch($song);
            GenerateAlbumDownloads::dispatch($song->album);
        }
    }

    public function forceDeleted(Song $song): void
    {
        $song->clearBundle();
    }

    protected function shouldRegenerateBundles(Song $song): bool
    {
        return $song->wasChanged($this->bundleRelevantFields);
    }

    protected function hasBundleableContent(Song $song): bool
    {
        return $song->wav_file || $song->mp4_video || $song->lyrics;
    }
}
