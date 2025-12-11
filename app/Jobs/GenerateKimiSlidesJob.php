<?php

namespace App\Jobs;

use App\Models\SlidePresentation;
use App\Services\NanoBananaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateKimiSlidesJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800;

    public int $tries = 1;

    protected int $batchSize = 3;

    public function __construct(
        public int $presentationId
    ) {}

    public function handle(NanoBananaService $nanoBanana): void
    {
        $presentation = SlidePresentation::find($this->presentationId);

        if (! $presentation) {
            Log::error('Presentation not found', ['id' => $this->presentationId]);

            return;
        }

        if (! $nanoBanana->isConfigured()) {
            Log::error('NanoBanana not configured');
            $presentation->update(['status' => 'failed']);

            return;
        }

        $outline = $presentation->outline;

        if (! $outline || empty($outline['slides'])) {
            Log::error('No slides in outline', ['id' => $this->presentationId]);
            $presentation->update(['status' => 'failed']);

            return;
        }

        $slides = $outline['slides'];
        $totalSlides = count($slides);

        $presentation->update([
            'status' => 'generating',
            'total_slides' => $totalSlides,
            'current_slide' => 0,
        ]);

        $generatedSlides = [];

        $batches = array_chunk($slides, $this->batchSize, true);

        foreach ($batches as $batchIndex => $batch) {
            $pendingTasks = [];

            foreach ($batch as $index => $slide) {
                $slideNumber = $index + 1;
                $imagePrompt = $slide['image_prompt'] ?? '';

                if (empty($imagePrompt)) {
                    Log::warning("No image prompt for slide {$slideNumber}");
                    $generatedSlides[$index] = [
                        'number' => $slideNumber,
                        'title' => $slide['title'] ?? '',
                        'type' => $slide['type'] ?? 'content',
                        'summary' => $slide['summary'] ?? '',
                        'image_path' => null,
                        'error' => 'No image prompt provided',
                    ];

                    continue;
                }

                Log::info("Starting image generation for slide {$slideNumber}", [
                    'presentation_id' => $this->presentationId,
                    'batch' => $batchIndex + 1,
                ]);

                $result = $nanoBanana->generateImage(
                    prompt: $imagePrompt,
                    aspectRatio: '16:9',
                    resolution: '2K'
                );

                if ($result['success']) {
                    $pendingTasks[$index] = [
                        'task_id' => $result['task_id'],
                        'slide_number' => $slideNumber,
                        'slide' => $slide,
                    ];
                } else {
                    Log::error("Failed to start generation for slide {$slideNumber}", [
                        'error' => $result['error'] ?? 'Unknown error',
                    ]);
                    $generatedSlides[$index] = [
                        'number' => $slideNumber,
                        'title' => $slide['title'] ?? '',
                        'type' => $slide['type'] ?? 'content',
                        'summary' => $slide['summary'] ?? '',
                        'image_path' => null,
                        'error' => $result['error'] ?? 'Failed to start generation',
                    ];
                }
            }

            $completedInBatch = $this->waitForBatch($pendingTasks, $nanoBanana);

            foreach ($completedInBatch as $index => $result) {
                $generatedSlides[$index] = $result;
            }

            $completedCount = collect($generatedSlides)->whereNotNull('image_path')->count();
            $presentation->update(['current_slide' => $completedCount]);

            $presentation->update(['slides' => array_values($generatedSlides)]);
        }

        ksort($generatedSlides);

        $presentation->update([
            'slides' => array_values($generatedSlides),
            'status' => 'complete',
            'current_slide' => $totalSlides,
        ]);

        Log::info('Presentation generation complete', [
            'presentation_id' => $this->presentationId,
            'total_slides' => $totalSlides,
            'successful' => collect($generatedSlides)->whereNotNull('image_path')->count(),
        ]);
    }

    protected function waitForBatch(array $pendingTasks, NanoBananaService $nanoBanana): array
    {
        $results = [];
        $maxAttempts = 120;
        $pollInterval = 3;

        for ($attempt = 0; $attempt < $maxAttempts && count($pendingTasks) > 0; $attempt++) {
            sleep($pollInterval);

            foreach ($pendingTasks as $index => $task) {
                $status = $nanoBanana->getTaskStatus($task['task_id']);

                if ($status['is_complete']) {
                    $slideNumber = $task['slide_number'];
                    $slide = $task['slide'];

                    $filename = "presentation_{$this->presentationId}_slide_{$slideNumber}_".time().'.png';
                    $localPath = $nanoBanana->downloadAndStore($status['image_url'], $filename);

                    $results[$index] = [
                        'number' => $slideNumber,
                        'title' => $slide['title'] ?? '',
                        'type' => $slide['type'] ?? 'content',
                        'summary' => $slide['summary'] ?? '',
                        'image_path' => $localPath,
                        'remote_url' => $status['image_url'],
                        'task_id' => $task['task_id'],
                    ];

                    Log::info("Slide {$slideNumber} generated successfully", [
                        'local_path' => $localPath,
                    ]);

                    unset($pendingTasks[$index]);
                } elseif ($status['is_failed']) {
                    $slideNumber = $task['slide_number'];
                    $slide = $task['slide'];

                    Log::error("Slide {$slideNumber} generation failed", [
                        'error' => $status['error'] ?? 'Unknown error',
                    ]);

                    $results[$index] = [
                        'number' => $slideNumber,
                        'title' => $slide['title'] ?? '',
                        'type' => $slide['type'] ?? 'content',
                        'summary' => $slide['summary'] ?? '',
                        'image_path' => null,
                        'error' => $status['error'] ?? 'Generation failed',
                    ];

                    unset($pendingTasks[$index]);
                }
            }
        }

        foreach ($pendingTasks as $index => $task) {
            $slideNumber = $task['slide_number'];
            $slide = $task['slide'];

            $results[$index] = [
                'number' => $slideNumber,
                'title' => $slide['title'] ?? '',
                'type' => $slide['type'] ?? 'content',
                'summary' => $slide['summary'] ?? '',
                'image_path' => null,
                'error' => 'Generation timed out',
            ];
        }

        return $results;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateKimiSlidesJob failed', [
            'presentation_id' => $this->presentationId,
            'error' => $exception->getMessage(),
        ]);

        $presentation = SlidePresentation::find($this->presentationId);
        if ($presentation) {
            $presentation->update(['status' => 'failed']);
        }
    }
}
