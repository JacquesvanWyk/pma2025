<?php

namespace App\Jobs;

use App\Models\SlidePresentation;
use App\Services\NanoBananaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RegenerateSlideJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public int $tries = 2;

    public function __construct(
        public int $presentationId,
        public int $slideIndex
    ) {}

    public function handle(NanoBananaService $nanoBanana): void
    {
        $presentation = SlidePresentation::find($this->presentationId);

        if (! $presentation) {
            Log::error('Presentation not found for regeneration', ['id' => $this->presentationId]);

            return;
        }

        $outline = $presentation->outline;
        $slides = $presentation->slides ?? [];

        if (! isset($outline['slides'][$this->slideIndex])) {
            Log::error('Slide outline not found', [
                'presentation_id' => $this->presentationId,
                'slide_index' => $this->slideIndex,
            ]);

            return;
        }

        $outlineSlide = $outline['slides'][$this->slideIndex];
        $imagePrompt = $outlineSlide['image_prompt'] ?? '';

        if (empty($imagePrompt)) {
            Log::warning('No image prompt for slide regeneration', [
                'presentation_id' => $this->presentationId,
                'slide_index' => $this->slideIndex,
            ]);

            return;
        }

        Log::info('Regenerating slide', [
            'presentation_id' => $this->presentationId,
            'slide_index' => $this->slideIndex,
        ]);

        $result = $nanoBanana->generateImage(
            prompt: $imagePrompt,
            aspectRatio: '16:9',
            resolution: '2K'
        );

        if (! $result['success']) {
            Log::error('Failed to start slide regeneration', [
                'presentation_id' => $this->presentationId,
                'slide_index' => $this->slideIndex,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            return;
        }

        $taskId = $result['task_id'];
        $imageUrl = $this->waitForCompletion($nanoBanana, $taskId);

        if (! $imageUrl) {
            Log::error('Slide regeneration timed out or failed', [
                'presentation_id' => $this->presentationId,
                'slide_index' => $this->slideIndex,
            ]);

            $slides[$this->slideIndex]['error'] = 'Regeneration failed';
            $presentation->update(['slides' => $slides]);

            return;
        }

        $slideNumber = $this->slideIndex + 1;
        $filename = "presentation_{$this->presentationId}_slide_{$slideNumber}_".time().'.png';
        $localPath = $nanoBanana->downloadAndStore($imageUrl, $filename);

        $slides[$this->slideIndex] = [
            'number' => $slideNumber,
            'title' => $outlineSlide['title'] ?? '',
            'type' => $outlineSlide['type'] ?? 'content',
            'summary' => $outlineSlide['summary'] ?? '',
            'image_path' => $localPath,
            'remote_url' => $imageUrl,
            'task_id' => $taskId,
            'regenerated_at' => now()->toIso8601String(),
        ];

        $presentation->update(['slides' => $slides]);

        Log::info('Slide regenerated successfully', [
            'presentation_id' => $this->presentationId,
            'slide_index' => $this->slideIndex,
            'local_path' => $localPath,
        ]);
    }

    protected function waitForCompletion(NanoBananaService $nanoBanana, string $taskId): ?string
    {
        $maxAttempts = 60;
        $pollInterval = 3;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            sleep($pollInterval);

            $status = $nanoBanana->getTaskStatus($taskId);

            if ($status['is_complete']) {
                return $status['image_url'];
            }

            if ($status['is_failed']) {
                return null;
            }
        }

        return null;
    }
}
