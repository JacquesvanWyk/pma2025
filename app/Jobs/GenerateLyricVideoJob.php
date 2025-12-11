<?php

namespace App\Jobs;

use App\Models\VideoProject;
use App\Services\LyricVideoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateLyricVideoJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 1800;

    public function __construct(
        public int $projectId,
        public ?string $componentId = null
    ) {}

    public function handle(LyricVideoService $service): void
    {
        $project = VideoProject::find($this->projectId);

        if (! $project) {
            Log::error('Video project not found', ['project_id' => $this->projectId]);

            return;
        }

        if ($this->componentId) {
            Cache::put(
                "video-generation-{$this->componentId}-status",
                'Processing video generation...',
                now()->addMinutes(30)
            );
        }

        $result = $service->generateLyricVideo($project);

        if ($this->componentId) {
            if ($result['success']) {
                Cache::put(
                    "video-generation-{$this->componentId}-complete",
                    [
                        'project_id' => $project->id,
                        'output_path' => $result['output_path'],
                        'thumbnail_path' => $result['thumbnail_path'] ?? null,
                    ],
                    now()->addMinutes(30)
                );
            } else {
                Cache::put(
                    "video-generation-{$this->componentId}-error",
                    $result['error'],
                    now()->addMinutes(30)
                );
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Lyric video generation job failed', [
            'project_id' => $this->projectId,
            'error' => $exception->getMessage(),
        ]);

        $project = VideoProject::find($this->projectId);
        if ($project) {
            $project->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'processing_completed_at' => now(),
            ]);
        }

        if ($this->componentId) {
            Cache::put(
                "video-generation-{$this->componentId}-error",
                $exception->getMessage(),
                now()->addMinutes(30)
            );
        }
    }
}
