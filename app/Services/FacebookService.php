<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    protected string $pageId;

    protected string $accessToken;

    protected string $graphUrl = 'https://graph.facebook.com/v18.0';

    public function __construct()
    {
        $this->pageId = config('services.facebook.page_id');
        $this->accessToken = config('services.facebook.page_access_token');
    }

    public function postPhoto(string $imageUrl, string $message): ?array
    {
        if (empty($this->pageId) || empty($this->accessToken)) {
            Log::warning('Facebook credentials not configured');

            return null;
        }

        try {
            $response = Http::post("{$this->graphUrl}/{$this->pageId}/photos", [
                'url' => $imageUrl,
                'message' => $message,
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Facebook photo posted successfully', ['post_id' => $data['post_id'] ?? $data['id'] ?? null]);

                return [
                    'success' => true,
                    'post_id' => $data['post_id'] ?? $data['id'] ?? null,
                ];
            }

            Log::error('Facebook post failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error',
            ];
        } catch (\Exception $e) {
            Log::error('Facebook post exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function postLink(string $url, string $message): ?array
    {
        if (empty($this->pageId) || empty($this->accessToken)) {
            Log::warning('Facebook credentials not configured');

            return null;
        }

        try {
            $response = Http::post("{$this->graphUrl}/{$this->pageId}/feed", [
                'link' => $url,
                'message' => $message,
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Facebook link posted successfully', ['post_id' => $data['id'] ?? null]);

                return [
                    'success' => true,
                    'post_id' => $data['id'] ?? null,
                ];
            }

            Log::error('Facebook link post failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error',
            ];
        } catch (\Exception $e) {
            Log::error('Facebook link post exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function isConfigured(): bool
    {
        return ! empty($this->pageId) && ! empty($this->accessToken);
    }
}
