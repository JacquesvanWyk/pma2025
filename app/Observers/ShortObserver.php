<?php

namespace App\Observers;

use App\Models\Short;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShortObserver
{
    public function created(Short $short): void
    {
        $this->generateThumbnail($short);
    }

    public function updated(Short $short): void
    {
        if ($short->isDirty('video_path') && $short->video_path && ! $short->thumbnail_path) {
            $this->generateThumbnail($short);
        }
    }

    public function deleted(Short $short): void
    {
        if ($short->video_path) {
            Storage::disk('r2')->delete($short->video_path);
        }
        if ($short->thumbnail_path) {
            Storage::disk('r2')->delete($short->thumbnail_path);
        }
    }

    private function generateThumbnail(Short $short): void
    {
        if (! $short->video_path || $short->thumbnail_path) {
            return;
        }

        $videoUrl = Storage::disk('r2')->url($short->video_path);
        $thumbnailFilename = 'shorts/thumbnails/'.Str::uuid().'.jpg';
        $tempPath = '/tmp/claude/thumbnail_'.Str::uuid().'.jpg';

        $result = Process::timeout(60)->run([
            'ffmpeg',
            '-i', $videoUrl,
            '-ss', '00:00:01',
            '-vframes', '1',
            '-q:v', '2',
            '-y',
            $tempPath,
        ]);

        if ($result->successful() && file_exists($tempPath)) {
            Storage::disk('r2')->put($thumbnailFilename, file_get_contents($tempPath), 'public');
            @unlink($tempPath);

            $short->updateQuietly(['thumbnail_path' => $thumbnailFilename]);
        }
    }
}
