<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaProcessingService
{
    protected string $apiUrl;

    protected string $apiKey;

    protected int $transcriptionTimeout;

    protected int $renderTimeout;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('media-processing.api_url'), '/');
        $this->apiKey = config('media-processing.api_key');
        $this->transcriptionTimeout = config('media-processing.timeout.transcription', 300);
        $this->renderTimeout = config('media-processing.timeout.render', 600);
    }

    public function transcribe(string $audioUrl, string $language = 'en'): array
    {
        $response = Http::timeout($this->transcriptionTimeout)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->post("{$this->apiUrl}/api/v1/transcribe", [
                'audio_url' => $audioUrl,
                'language' => $language,
            ]);

        if (! $response->successful()) {
            Log::error('Transcription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Transcription failed: ' . $response->body());
        }

        return $response->json();
    }

    public function transcribeAsync(string $audioUrl, string $callbackUrl, string $language = 'en'): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->post("{$this->apiUrl}/api/v1/transcribe/async", [
                'audio_url' => $audioUrl,
                'language' => $language,
                'callback_url' => $callbackUrl,
            ]);

        if (! $response->successful()) {
            throw new \Exception('Async transcription failed: ' . $response->body());
        }

        return $response->json();
    }

    public function renderLyricVideo(array $data): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->post("{$this->apiUrl}/api/v1/render/lyric-video", [
                'audioUrl' => $data['audio_url'],
                'lyrics' => $data['lyrics'],
                'durationMs' => $data['duration_ms'],
                'width' => $data['width'] ?? 1920,
                'height' => $data['height'] ?? 1080,
                'fps' => $data['fps'] ?? 30,
                'backgroundColor' => $data['background_color'] ?? '#000000',
                'backgroundImage' => $data['background_image'] ?? null,
                'backgroundVideo' => $data['background_video'] ?? null,
                'fontFamily' => $data['font_family'] ?? 'Arial',
                'fontSize' => $data['font_size'] ?? 48,
                'fontColor' => $data['font_color'] ?? '#ffffff',
                'textPosition' => $data['text_position'] ?? 'center',
                'textShadow' => $data['text_shadow'] ?? true,
                'logo' => $data['logo'] ?? null,
                'logoPosition' => $data['logo_position'] ?? 'bottom-right',
                'logoSize' => $data['logo_size'] ?? 80,
            ]);

        if (! $response->successful()) {
            throw new \Exception('Lyric video render failed: ' . $response->body());
        }

        return $response->json();
    }

    public function renderScriptureVideo(array $data): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->post("{$this->apiUrl}/api/v1/render/scripture", [
                'scripture' => $data['scripture'],
                'reference' => $data['reference'],
                'translation' => $data['translation'] ?? 'ESV',
                'durationSeconds' => $data['duration_seconds'] ?? 10,
                'width' => $data['width'] ?? 1920,
                'height' => $data['height'] ?? 1080,
                'backgroundColor' => $data['background_color'] ?? '#1a1a2e',
                'backgroundImage' => $data['background_image'] ?? null,
                'fontFamily' => $data['font_family'] ?? 'Georgia',
                'fontSize' => $data['font_size'] ?? 56,
                'fontColor' => $data['font_color'] ?? '#ffffff',
                'animation' => $data['animation'] ?? 'fade',
            ]);

        if (! $response->successful()) {
            throw new \Exception('Scripture video render failed: ' . $response->body());
        }

        return $response->json();
    }

    public function renderPromoVideo(array $data): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->post("{$this->apiUrl}/api/v1/render/promo", [
                'title' => $data['title'],
                'subtitle' => $data['subtitle'] ?? null,
                'date' => $data['date'] ?? null,
                'time' => $data['time'] ?? null,
                'location' => $data['location'] ?? null,
                'description' => $data['description'] ?? null,
                'images' => $data['images'] ?? [],
                'durationSeconds' => $data['duration_seconds'] ?? 15,
                'width' => $data['width'] ?? 1080,
                'height' => $data['height'] ?? 1920,
                'backgroundColor' => $data['background_color'] ?? '#000000',
                'backgroundImage' => $data['background_image'] ?? null,
                'primaryColor' => $data['primary_color'] ?? '#4a90d9',
                'fontFamily' => $data['font_family'] ?? 'Montserrat',
                'logo' => $data['logo'] ?? null,
            ]);

        if (! $response->successful()) {
            throw new \Exception('Promo video render failed: ' . $response->body());
        }

        return $response->json();
    }

    public function getRenderStatus(string $taskId): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->get("{$this->apiUrl}/api/v1/render/status/{$taskId}");

        if (! $response->successful()) {
            throw new \Exception('Failed to get render status: ' . $response->body());
        }

        return $response->json();
    }

    public function getDownloadUrl(string $taskId): string
    {
        return "{$this->apiUrl}/api/v1/render/download/{$taskId}";
    }

    public function getTemplates(): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['X-API-KEY' => $this->apiKey])
            ->get("{$this->apiUrl}/api/v1/templates");

        if (! $response->successful()) {
            throw new \Exception('Failed to get templates: ' . $response->body());
        }

        return $response->json();
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}/health");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
