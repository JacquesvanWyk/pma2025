<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SlideGenerationService
{
    private const CACHE_PREFIX = 'slide_generation_';

    private const CACHE_TTL = 3600; // 1 hour

    public function startGeneration(string $jobId, int $totalSlides): void
    {
        Cache::put($this->getCacheKey($jobId, 'status'), 'generating', self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'total_slides'), $totalSlides, self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'current_slide'), 0, self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'slides'), [], self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'message'), 'Starting generation...', self::CACHE_TTL);
    }

    public function addSlide(string $jobId, array $slideData): void
    {
        $slides = Cache::get($this->getCacheKey($jobId, 'slides'), []);
        $slides[] = $slideData;

        Cache::put($this->getCacheKey($jobId, 'slides'), $slides, self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'current_slide'), count($slides), self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'message'), "Generated slide {$slideData['slide_number']} of {$slideData['total']}", self::CACHE_TTL);
    }

    public function updateStatus(string $jobId, string $message): void
    {
        Cache::put($this->getCacheKey($jobId, 'message'), $message, self::CACHE_TTL);
    }

    public function markComplete(string $jobId): void
    {
        Cache::put($this->getCacheKey($jobId, 'status'), 'completed', self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'message'), 'All slides generated successfully!', self::CACHE_TTL);
    }

    public function markFailed(string $jobId, string $error): void
    {
        Cache::put($this->getCacheKey($jobId, 'status'), 'failed', self::CACHE_TTL);
        Cache::put($this->getCacheKey($jobId, 'message'), "Generation failed: {$error}", self::CACHE_TTL);
    }

    public function getProgress(string $jobId): array
    {
        return [
            'status' => Cache::get($this->getCacheKey($jobId, 'status'), 'unknown'),
            'total_slides' => Cache::get($this->getCacheKey($jobId, 'total_slides'), 0),
            'current_slide' => Cache::get($this->getCacheKey($jobId, 'current_slide'), 0),
            'slides' => Cache::get($this->getCacheKey($jobId, 'slides'), []),
            'message' => Cache::get($this->getCacheKey($jobId, 'message'), ''),
        ];
    }

    public function clearProgress(string $jobId): void
    {
        Cache::forget($this->getCacheKey($jobId, 'status'));
        Cache::forget($this->getCacheKey($jobId, 'total_slides'));
        Cache::forget($this->getCacheKey($jobId, 'current_slide'));
        Cache::forget($this->getCacheKey($jobId, 'slides'));
        Cache::forget($this->getCacheKey($jobId, 'message'));
    }

    private function getCacheKey(string $jobId, string $suffix): string
    {
        return self::CACHE_PREFIX.$jobId.'_'.$suffix;
    }
}
