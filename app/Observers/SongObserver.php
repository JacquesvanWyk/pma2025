<?php

namespace App\Observers;

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

    public function created(Song $song): void
    {
        if ($song->album_id && $this->hasBundleableContent($song)) {
            GenerateSongBundle::dispatch($song);
            GenerateAlbumDownloads::dispatch($song->album);
        }
    }

    public function updated(Song $song): void
    {
        if ($this->shouldRegenerateBundles($song)) {
            if ($this->hasBundleableContent($song)) {
                GenerateSongBundle::dispatch($song);
            } else {
                $song->clearBundle();
            }

            if ($song->album_id) {
                GenerateAlbumDownloads::dispatch($song->album);
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
