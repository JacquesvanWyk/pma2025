<?php

namespace App\Jobs;

use App\Models\GeneratedMedia;
use App\Services\KieAiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PollKieMusicTaskJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 120;

    public int $timeout = 600;

    public function __construct(
        public int $mediaId,
        public string $taskId,
        public string $componentId
    ) {}

    public function handle(): void
    {
        $media = GeneratedMedia::find($this->mediaId);
        if (! $media) {
            $this->dispatchError('Media record not found');

            return;
        }

        $service = app(KieAiService::class);
        $maxAttempts = config('kie.polling.max_attempts', 120);
        $interval = config('kie.polling.interval_seconds', 5);

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $this->dispatchStatus("Checking generation status (attempt {$attempt}/{$maxAttempts})...");

            $result = $service->getMusicTaskStatus($this->taskId);

            if (! $result['success']) {
                Log::error('Music task status check failed', [
                    'task_id' => $this->taskId,
                    'error' => $result['error'] ?? 'Unknown error',
                    'attempt' => $attempt,
                ]);
                sleep($interval);

                continue;
            }

            if ($result['is_complete']) {
                $this->handleComplete($media, $result);

                return;
            }

            if ($result['is_failed']) {
                $this->handleFailed($media, $result['status']);

                return;
            }

            sleep($interval);
        }

        $this->handleTimeout($media);
    }

    protected function handleComplete(GeneratedMedia $media, array $result): void
    {
        $tracks = $result['tracks'] ?? [];

        if (empty($tracks)) {
            $this->handleFailed($media, 'No tracks returned');

            return;
        }

        $track = $tracks[0];
        $service = app(KieAiService::class);
        $localPath = null;

        if (! empty($track['audio_url'])) {
            $localPath = $service->downloadAndStore($track['audio_url'], 'music');
        }

        $media->update([
            'status' => 'completed',
            'file_path' => $localPath,
            'remote_url' => $track['audio_url'] ?? null,
            'duration_seconds' => (int) ($track['duration'] ?? 0),
            'metadata' => [
                'title' => $track['title'] ?? null,
                'image_url' => $track['image_url'] ?? null,
                'tracks' => $tracks,
            ],
        ]);

        $this->dispatchComplete($tracks);

        Log::info('Music generation completed', [
            'media_id' => $media->id,
            'task_id' => $this->taskId,
            'duration' => $track['duration'] ?? 0,
        ]);
    }

    protected function handleFailed(GeneratedMedia $media, string $status): void
    {
        $media->update([
            'status' => 'failed',
            'metadata' => ['error' => $status],
        ]);

        $this->dispatchError("Music generation failed: {$status}");

        Log::error('Music generation failed', [
            'media_id' => $media->id,
            'task_id' => $this->taskId,
            'status' => $status,
        ]);
    }

    protected function handleTimeout(GeneratedMedia $media): void
    {
        $media->update([
            'status' => 'failed',
            'metadata' => ['error' => 'Generation timed out'],
        ]);

        $this->dispatchError('Music generation timed out after 10 minutes');

        Log::error('Music generation timed out', [
            'media_id' => $media->id,
            'task_id' => $this->taskId,
        ]);
    }

    protected function dispatchStatus(string $message): void
    {
        Cache::put(
            "music-generation-{$this->componentId}-status",
            $message,
            now()->addMinutes(15)
        );
    }

    protected function dispatchComplete(array $tracks): void
    {
        Cache::put(
            "music-generation-{$this->componentId}-complete",
            ['tracks' => $tracks],
            now()->addMinutes(15)
        );
    }

    protected function dispatchError(string $error): void
    {
        Cache::put(
            "music-generation-{$this->componentId}-error",
            $error,
            now()->addMinutes(15)
        );
    }
}
