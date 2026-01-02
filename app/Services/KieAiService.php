<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KieAiService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected ?string $callbackUrl;

    public function __construct()
    {
        $this->baseUrl = config('kie.base_url');
        $this->apiKey = config('kie.api_key');
        $this->callbackUrl = config('kie.callback_url');
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->timeout(120)
            ->connectTimeout(30);
    }

    /**
     * Get remaining account credits
     */
    public function getCredits(): array
    {
        $response = $this->client()->get('/api/v1/chat/credit');

        if ($response->successful() && $response->json('code') === 200) {
            $credits = $response->json('data');

            return [
                'success' => true,
                'credits' => $credits,
                'cost_usd' => $credits * 0.005,
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('msg') ?? 'Failed to get credits',
            'credits' => 0,
        ];
    }

    /**
     * Generate music using Suno API
     */
    public function generateMusic(
        string $prompt,
        string $model = 'V4_5',
        bool $instrumental = false,
        bool $customMode = false,
        ?string $style = null,
        ?string $title = null,
        ?string $lyrics = null
    ): array {
        $payload = [
            'prompt' => $customMode && $lyrics ? $lyrics : $prompt,
            'customMode' => $customMode,
            'instrumental' => $instrumental,
            'model' => $model,
        ];

        if ($customMode) {
            if ($style) {
                $payload['style'] = Str::limit($style, config('kie.music.style_max_length', 1000));
            }
            if ($title) {
                $payload['title'] = Str::limit($title, config('kie.music.title_max_length', 80));
            }
        }

        if ($this->callbackUrl) {
            $payload['callBackUrl'] = $this->callbackUrl;
        }

        Log::info('KIE Music Generation Request', [
            'model' => $model,
            'instrumental' => $instrumental,
            'custom_mode' => $customMode,
            'prompt_length' => strlen($prompt),
        ]);

        $response = $this->client()->post('/api/v1/generate', $payload);

        if ($response->successful() && $response->json('code') === 200) {
            $taskId = $response->json('data.taskId');

            ApiUsageTracker::trackSuccess(
                provider: 'kie',
                service: 'music-generation',
                action: 'generate',
                requestData: [
                    'model' => $model,
                    'instrumental' => $instrumental,
                    'custom_mode' => $customMode,
                    'prompt_length' => strlen($prompt),
                ],
                model: $model
            );

            return [
                'success' => true,
                'task_id' => $taskId,
                'status' => 'PENDING',
            ];
        }

        $errorMsg = $response->json('msg') ?? 'Failed to start music generation';

        ApiUsageTracker::trackError(
            provider: 'kie',
            service: 'music-generation',
            action: 'generate',
            errorMessage: $errorMsg,
            requestData: [
                'model' => $model,
                'instrumental' => $instrumental,
            ],
            model: $model
        );

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    /**
     * Get music generation task status
     */
    public function getMusicTaskStatus(string $taskId): array
    {
        $response = $this->client()->get('/api/v1/generate/record-info', [
            'taskId' => $taskId,
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');
            $status = $data['status'] ?? 'UNKNOWN';

            $result = [
                'success' => true,
                'task_id' => $taskId,
                'status' => $status,
                'is_complete' => in_array($status, ['SUCCESS', 'FIRST_SUCCESS']),
                'is_failed' => in_array($status, ['CREATE_TASK_FAILED', 'GENERATE_AUDIO_FAILED', 'SENSITIVE_WORD_ERROR']),
                'is_pending' => $status === 'PENDING',
            ];

            if ($result['is_complete'] && isset($data['response']['sunoData'])) {
                $result['tracks'] = collect($data['response']['sunoData'])->map(fn ($track) => [
                    'id' => $track['id'] ?? null,
                    'audio_url' => $track['audioUrl'] ?? null,
                    'image_url' => $track['imageUrl'] ?? null,
                    'title' => $track['title'] ?? null,
                    'duration' => $track['duration'] ?? null,
                ])->toArray();
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

    /**
     * Generate video using Veo 3.1 API
     */
    public function generateVideo(
        string $prompt,
        string $model = 'veo3.1_fast',
        ?string $imageUrl = null
    ): array {
        $endpoint = $imageUrl ? '/api/v1/veo/i2v' : '/api/v1/veo/t2v';

        $payload = [
            'prompt' => $prompt,
            'model' => $model,
        ];

        if ($imageUrl) {
            $payload['imageUrl'] = $imageUrl;
        }

        if ($this->callbackUrl) {
            $payload['callBackUrl'] = $this->callbackUrl;
        }

        Log::info('KIE Video Generation Request', [
            'model' => $model,
            'has_image' => ! empty($imageUrl),
            'prompt_length' => strlen($prompt),
        ]);

        $response = $this->client()->post($endpoint, $payload);

        if ($response->successful() && $response->json('code') === 200) {
            $taskId = $response->json('data.taskId');

            ApiUsageTracker::trackSuccess(
                provider: 'kie',
                service: 'video-generation',
                action: 'generate',
                requestData: [
                    'model' => $model,
                    'has_image' => ! empty($imageUrl),
                    'prompt_length' => strlen($prompt),
                ],
                model: $model
            );

            return [
                'success' => true,
                'task_id' => $taskId,
                'status' => 'PENDING',
            ];
        }

        $errorMsg = $response->json('msg') ?? 'Failed to start video generation';

        ApiUsageTracker::trackError(
            provider: 'kie',
            service: 'video-generation',
            action: 'generate',
            errorMessage: $errorMsg,
            requestData: ['model' => $model],
            model: $model
        );

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    /**
     * Get video generation task status
     */
    public function getVideoTaskStatus(string $taskId): array
    {
        $response = $this->client()->get('/api/v1/veo/record-info', [
            'taskId' => $taskId,
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');
            $status = $data['status'] ?? 'UNKNOWN';

            $result = [
                'success' => true,
                'task_id' => $taskId,
                'status' => $status,
                'is_complete' => $status === 'SUCCESS',
                'is_failed' => str_contains($status, 'FAILED'),
                'is_pending' => in_array($status, ['PENDING', 'PROCESSING']),
            ];

            if ($result['is_complete'] && isset($data['response'])) {
                $result['video_url'] = $data['response']['videoUrl'] ?? null;
                $result['thumbnail_url'] = $data['response']['thumbnailUrl'] ?? null;
                $result['duration'] = $data['response']['duration'] ?? null;
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

    /**
     * Generate image using Seedream or NanoBanana
     */
    public function generateImage(
        string $prompt,
        string $model = 'seedream',
        string $size = '1024x1024'
    ): array {
        $endpoint = match ($model) {
            'seedream' => '/api/v1/seedream/generate',
            'nanobanana' => '/api/v1/nanobanana/generate',
            '4o' => '/api/v1/4o/generate',
            'flux_kontext' => '/api/v1/flux-kontext/generate',
            default => '/api/v1/seedream/generate',
        };

        // Build payload based on model requirements
        if ($model === 'nanobanana') {
            // NanoBanana uses different parameters
            $imageSize = match ($size) {
                '1024x1024' => '1:1',
                '1024x1792' => '9:16',
                '1792x1024' => '16:9',
                default => '1:1',
            };

            $payload = [
                'prompt' => $prompt,
                'type' => 'TEXTTOIAMGE', // Note: API typo is intentional
                'image_size' => $imageSize,
                'numImages' => 1,
            ];

            // callBackUrl is required for nanobanana
            if ($this->callbackUrl) {
                $payload['callBackUrl'] = $this->callbackUrl;
            } else {
                // Use a placeholder if no callback configured
                $payload['callBackUrl'] = config('app.url').'/api/kie/callback';
            }
        } else {
            $payload = [
                'prompt' => $prompt,
                'size' => $size,
            ];

            if ($this->callbackUrl) {
                $payload['callBackUrl'] = $this->callbackUrl;
            }
        }

        Log::info('KIE Image Generation Request', [
            'model' => $model,
            'size' => $size,
            'prompt_length' => strlen($prompt),
            'payload' => array_diff_key($payload, ['prompt' => '']), // Log without prompt for brevity
        ]);

        $response = $this->client()->post($endpoint, $payload);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');

            // Some endpoints return immediate URLs, others return task IDs
            if (isset($data['imageUrl'])) {
                ApiUsageTracker::trackSuccess(
                    provider: 'kie',
                    service: 'image-generation',
                    action: 'generate',
                    requestData: ['model' => $model, 'size' => $size],
                    model: $model
                );

                return [
                    'success' => true,
                    'image_url' => $data['imageUrl'],
                    'is_complete' => true,
                ];
            }

            if (isset($data['taskId'])) {
                return [
                    'success' => true,
                    'task_id' => $data['taskId'],
                    'status' => 'PENDING',
                    'is_complete' => false,
                ];
            }
        }

        $errorMsg = $response->json('msg') ?? 'Failed to generate image';

        Log::error('KIE Image Generation Failed', [
            'model' => $model,
            'status' => $response->status(),
            'code' => $response->json('code'),
            'msg' => $errorMsg,
            'full_response' => $response->json(),
        ]);

        ApiUsageTracker::trackError(
            provider: 'kie',
            service: 'image-generation',
            action: 'generate',
            errorMessage: $errorMsg,
            requestData: ['model' => $model, 'size' => $size],
            model: $model
        );

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    /**
     * Generate image using NanoBanana Pro
     */
    public function generateImagePro(
        string $prompt,
        string $aspectRatio = '1:1',
        string $resolution = '1K',
        array $imageUrls = [],
        string $outputFormat = 'png'
    ): array {
        $payload = [
            'model' => 'nano-banana-pro',
            'callBackUrl' => $this->callbackUrl ?? config('app.url').'/api/kie/callback',
            'input' => [
                'prompt' => $prompt,
                'aspect_ratio' => $aspectRatio,
                'resolution' => $resolution,
                'output_format' => $outputFormat,
            ],
        ];

        if (! empty($imageUrls)) {
            $payload['input']['image_input'] = array_slice($imageUrls, 0, 8);
        }

        Log::info('KIE NanoBanana Pro Request', [
            'aspectRatio' => $aspectRatio,
            'resolution' => $resolution,
            'prompt_length' => strlen($prompt),
            'image_count' => count($imageUrls),
        ]);

        $response = $this->client()->post('/api/v1/jobs/createTask', $payload);

        Log::info('KIE NanoBanana Pro Response', [
            'status' => $response->status(),
            'code' => $response->json('code'),
            'data' => $response->json('data'),
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');

            if (isset($data['imageUrl'])) {
                ApiUsageTracker::trackSuccess(
                    provider: 'kie',
                    service: 'image-generation',
                    action: 'generate-pro',
                    requestData: ['aspectRatio' => $aspectRatio, 'resolution' => $resolution],
                    model: 'nanobanana_pro'
                );

                return [
                    'success' => true,
                    'image_url' => $data['imageUrl'],
                    'is_complete' => true,
                ];
            }

            if (isset($data['taskId'])) {
                return [
                    'success' => true,
                    'task_id' => $data['taskId'],
                    'status' => 'PENDING',
                    'is_complete' => false,
                ];
            }
        }

        $errorMsg = $response->json('msg') ?? 'Failed to generate image';

        Log::error('KIE NanoBanana Pro Failed', [
            'status' => $response->status(),
            'code' => $response->json('code'),
            'msg' => $errorMsg,
            'full_response' => $response->json(),
        ]);

        ApiUsageTracker::trackError(
            provider: 'kie',
            service: 'image-generation',
            action: 'generate-pro',
            errorMessage: $errorMsg,
            requestData: ['aspectRatio' => $aspectRatio, 'resolution' => $resolution],
            model: 'nanobanana_pro'
        );

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    /**
     * Get NanoBanana image task status
     */
    public function getNanoBananaTaskStatus(string $taskId): array
    {
        $response = $this->client()->get('/api/v1/nanobanana/record-info', [
            'taskId' => $taskId,
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');
            $status = $data['status'] ?? 'UNKNOWN';

            $result = [
                'success' => true,
                'task_id' => $taskId,
                'status' => $status,
                'is_complete' => $status === 'SUCCESS',
                'is_failed' => str_contains($status, 'FAILED'),
                'is_pending' => in_array($status, ['PENDING', 'PROCESSING']),
            ];

            if ($result['is_complete'] && isset($data['response']['imageUrls'])) {
                $result['image_urls'] = $data['response']['imageUrls'];
                $result['image_url'] = $data['response']['imageUrls'][0] ?? null;
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

    /**
     * Get job task status (for NanoBanana Pro and other market models)
     */
    public function getJobTaskStatus(string $taskId): array
    {
        $response = $this->client()->get('/api/v1/jobs/recordInfo', [
            'taskId' => $taskId,
        ]);

        Log::info('KIE Job Status Check', [
            'task_id' => $taskId,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');
            // API uses 'state' not 'status', and lowercase values
            $state = strtolower($data['state'] ?? $data['status'] ?? 'unknown');

            $result = [
                'success' => true,
                'task_id' => $taskId,
                'status' => $state,
                'is_complete' => $state === 'success',
                'is_failed' => str_contains($state, 'fail'),
                'is_pending' => in_array($state, ['pending', 'processing', 'submitted', 'waiting']),
            ];

            if ($result['is_complete']) {
                $imageUrl = null;

                // Check resultJson (JSON string with resultUrls array)
                if (! empty($data['resultJson'])) {
                    $resultData = json_decode($data['resultJson'], true);
                    if (isset($resultData['resultUrls'][0])) {
                        $imageUrl = $resultData['resultUrls'][0];
                        $result['image_urls'] = $resultData['resultUrls'];
                    }
                }
                // Fallback: Check data.output formats
                elseif (isset($data['output']['image_url'])) {
                    $imageUrl = $data['output']['image_url'];
                } elseif (isset($data['output']['images'][0])) {
                    $imageUrl = $data['output']['images'][0];
                    $result['image_urls'] = $data['output']['images'];
                }
                // Fallback: Check data.response formats
                elseif (isset($data['response']['resultImageUrl'])) {
                    $imageUrl = $data['response']['resultImageUrl'];
                } elseif (isset($data['response']['imageUrl'])) {
                    $imageUrl = $data['response']['imageUrl'];
                } elseif (isset($data['response']['imageUrls'][0])) {
                    $imageUrl = $data['response']['imageUrls'][0];
                    $result['image_urls'] = $data['response']['imageUrls'];
                }

                $result['image_url'] = $imageUrl;
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

    /**
     * Edit image using Flux Kontext
     */
    public function editImage(
        string $imageUrl,
        string $prompt
    ): array {
        $payload = [
            'imageUrl' => $imageUrl,
            'prompt' => $prompt,
        ];

        if ($this->callbackUrl) {
            $payload['callBackUrl'] = $this->callbackUrl;
        }

        $response = $this->client()->post('/api/v1/flux-kontext/edit', $payload);

        if ($response->successful() && $response->json('code') === 200) {
            $data = $response->json('data');

            ApiUsageTracker::trackSuccess(
                provider: 'kie',
                service: 'image-editing',
                action: 'edit',
                requestData: ['prompt_length' => strlen($prompt)],
                model: 'flux_kontext'
            );

            return [
                'success' => true,
                'task_id' => $data['taskId'] ?? null,
                'image_url' => $data['imageUrl'] ?? null,
            ];
        }

        $errorMsg = $response->json('msg') ?? 'Failed to edit image';

        ApiUsageTracker::trackError(
            provider: 'kie',
            service: 'image-editing',
            action: 'edit',
            errorMessage: $errorMsg,
            model: 'flux_kontext'
        );

        return [
            'success' => false,
            'error' => $errorMsg,
        ];
    }

    /**
     * Download remote file and store locally
     */
    public function downloadAndStore(
        string $url,
        string $type,
        ?string $filename = null
    ): ?string {
        $extension = match ($type) {
            'image' => 'png',
            'video' => 'mp4',
            'music' => 'mp3',
            default => 'bin',
        };

        $filename = $filename ?? Str::random(16).'.'.$extension;
        $path = config('kie.storage.paths.'.$type, 'generated').'/'.$filename;
        $disk = config('kie.storage.disk', 'public');

        try {
            $contents = Http::timeout(300)->get($url)->body();
            Storage::disk($disk)->put($path, $contents);

            return $path;
        } catch (\Exception $e) {
            Log::error('KIE Download Failed', [
                'url' => $url,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }
}
