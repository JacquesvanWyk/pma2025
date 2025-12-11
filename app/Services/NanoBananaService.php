<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NanoBananaService
{
    protected string $baseUrl = 'https://api.nanobananaapi.ai';

    protected string $apiKey;

    protected ?string $callbackUrl;

    public function __construct()
    {
        $this->apiKey = config('services.nanobanana.api_key');
        $this->callbackUrl = config('services.nanobanana.callback_url');
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->timeout(60)
            ->connectTimeout(30);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    public function getCredits(): array
    {
        $response = $this->client()->get('/api/v1/common/get-account-credits');

        if ($response->successful() && $response->json('code') === 200) {
            return [
                'success' => true,
                'credits' => $response->json('data.credits', 0),
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('msg') ?? 'Failed to get credits',
            'credits' => 0,
        ];
    }

    public function generateImage(
        string $prompt,
        string $aspectRatio = '16:9',
        string $resolution = '2K',
        ?array $imageUrls = null
    ): array {
        $payload = [
            'prompt' => $prompt,
            'aspectRatio' => $aspectRatio,
            'resolution' => $resolution,
            'callBackUrl' => $this->callbackUrl ?? config('app.url').'/api/nanobanana/callback',
        ];

        if ($imageUrls && count($imageUrls) > 0) {
            $payload['imageUrls'] = $imageUrls;
        }

        Log::info('NanoBanana Image Generation Request', [
            'prompt_length' => strlen($prompt),
            'aspect_ratio' => $aspectRatio,
            'resolution' => $resolution,
        ]);

        $response = $this->client()->post('/api/v1/nanobanana/generate-pro', $payload);

        if ($response->successful() && $response->json('code') === 200) {
            $taskId = $response->json('data.taskId');

            return [
                'success' => true,
                'task_id' => $taskId,
                'status' => 'GENERATING',
            ];
        }

        $errorMsg = $response->json('msg') ?? 'Failed to start image generation';

        Log::error('NanoBanana generation failed', [
            'error' => $errorMsg,
            'response' => $response->json(),
        ]);

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    public function getTaskStatus(string $taskId): array
    {
        $response = $this->client()->get('/api/v1/nanobanana/record-info', [
            'taskId' => $taskId,
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');
            $successFlag = $data['successFlag'] ?? 0;

            $status = match ($successFlag) {
                0 => 'GENERATING',
                1 => 'SUCCESS',
                2 => 'CREATE_TASK_FAILED',
                3 => 'GENERATE_FAILED',
                default => 'UNKNOWN',
            };

            $result = [
                'success' => true,
                'task_id' => $taskId,
                'status' => $status,
                'is_complete' => $successFlag === 1,
                'is_failed' => in_array($successFlag, [2, 3]),
                'is_pending' => $successFlag === 0,
            ];

            if ($result['is_complete'] && isset($data['response'])) {
                $result['image_url'] = $data['response']['resultImageUrl'] ?? $data['response']['originImageUrl'] ?? null;
                $result['origin_url'] = $data['response']['originImageUrl'] ?? null;
            }

            if ($result['is_failed']) {
                $result['error'] = $data['errorMessage'] ?? 'Generation failed';
            }

            return $result;
        }

        return [
            'success' => false,
            'task_id' => $taskId,
            'status' => 'ERROR',
            'error' => $response->json('msg') ?? 'Failed to get task status',
        ];
    }

    public function generateImageSync(
        string $prompt,
        string $aspectRatio = '16:9',
        string $resolution = '2K',
        int $maxAttempts = 60,
        int $pollInterval = 2
    ): array {
        $result = $this->generateImage($prompt, $aspectRatio, $resolution);

        if (! $result['success']) {
            return $result;
        }

        $taskId = $result['task_id'];

        for ($i = 0; $i < $maxAttempts; $i++) {
            sleep($pollInterval);

            $status = $this->getTaskStatus($taskId);

            if ($status['is_complete']) {
                return [
                    'success' => true,
                    'image_url' => $status['image_url'],
                    'task_id' => $taskId,
                ];
            }

            if ($status['is_failed']) {
                return [
                    'success' => false,
                    'error' => $status['error'] ?? 'Generation failed',
                    'task_id' => $taskId,
                ];
            }
        }

        return [
            'success' => false,
            'error' => 'Generation timed out',
            'task_id' => $taskId,
        ];
    }

    public function downloadAndStore(string $url, ?string $filename = null): ?string
    {
        $filename = $filename ?? Str::random(16).'.png';
        $path = 'generated/slides/'.$filename;
        $disk = 'public';

        try {
            $contents = Http::timeout(120)->get($url)->body();
            Storage::disk($disk)->put($path, $contents);

            return $path;
        } catch (\Exception $e) {
            Log::error('NanoBanana Download Failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
