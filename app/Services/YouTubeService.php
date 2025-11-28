<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    protected string $apiKey;

    protected string $channelId;

    protected string $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
        $this->channelId = config('services.youtube.channel_id');
    }

    public function getChannelVideos(int $maxResults = 20): array
    {
        $cacheKey = "youtube_videos_{$this->channelId}_{$maxResults}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($maxResults) {
            try {
                $response = Http::get("{$this->baseUrl}/search", [
                    'key' => $this->apiKey,
                    'channelId' => $this->channelId,
                    'part' => 'snippet',
                    'order' => 'date',
                    'maxResults' => $maxResults,
                    'type' => 'video',
                ]);

                if ($response->failed()) {
                    Log::error('YouTube API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return [];
                }

                $videos = $response->json('items', []);

                return $this->formatVideos($videos);
            } catch (\Exception $e) {
                Log::error('YouTube API exception', [
                    'message' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    public function getPlaylistVideos(string $playlistId, int $maxResults = 50): array
    {
        $cacheKey = "youtube_playlist_{$playlistId}_{$maxResults}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($playlistId, $maxResults) {
            try {
                $response = Http::get("{$this->baseUrl}/playlistItems", [
                    'key' => $this->apiKey,
                    'playlistId' => $playlistId,
                    'part' => 'snippet',
                    'maxResults' => $maxResults,
                ]);

                if ($response->failed()) {
                    Log::error('YouTube API playlist request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return [];
                }

                $items = $response->json('items', []);

                return collect($items)->map(function ($item) {
                    return [
                        'id' => $item['snippet']['resourceId']['videoId'] ?? null,
                        'title' => html_entity_decode($item['snippet']['title'] ?? 'Untitled', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'description' => html_entity_decode($item['snippet']['description'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'thumbnail' => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['default']['url'] ?? '',
                        'published_at' => $item['snippet']['publishedAt'] ?? null,
                        'channel_title' => html_entity_decode($item['snippet']['channelTitle'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    ];
                })->filter(fn ($video) => ! empty($video['id']))->toArray();

            } catch (\Exception $e) {
                Log::error('YouTube API playlist exception', [
                    'message' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    public function getVideoDetails(string $videoId): ?array
    {
        $cacheKey = "youtube_video_{$videoId}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($videoId) {
            try {
                $response = Http::get("{$this->baseUrl}/videos", [
                    'key' => $this->apiKey,
                    'id' => $videoId,
                    'part' => 'snippet,contentDetails,statistics',
                ]);

                if ($response->failed()) {
                    return null;
                }

                $video = $response->json('items.0');

                if (! $video) {
                    return null;
                }

                return [
                    'id' => $video['id'],
                    'title' => html_entity_decode($video['snippet']['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'description' => html_entity_decode($video['snippet']['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? $video['snippet']['thumbnails']['default']['url'],
                    'published_at' => $video['snippet']['publishedAt'],
                    'duration' => $this->parseDuration($video['contentDetails']['duration']),
                    'view_count' => $video['statistics']['viewCount'] ?? 0,
                ];
            } catch (\Exception $e) {
                Log::error('YouTube API exception for video details', [
                    'video_id' => $videoId,
                    'message' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    protected function formatVideos(array $videos): array
    {
        return collect($videos)->map(function ($video) {
            return [
                'id' => $video['id']['videoId'] ?? null,
                'title' => html_entity_decode($video['snippet']['title'] ?? 'Untitled', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'description' => html_entity_decode($video['snippet']['description'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? $video['snippet']['thumbnails']['default']['url'] ?? '',
                'published_at' => $video['snippet']['publishedAt'] ?? null,
                'channel_title' => html_entity_decode($video['snippet']['channelTitle'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            ];
        })->filter(fn ($video) => ! empty($video['id']))->toArray();
    }

    protected function parseDuration(string $duration): string
    {
        preg_match('/PT(\d+H)?(\d+M)?(\d+S)?/', $duration, $matches);

        $hours = isset($matches[1]) ? rtrim($matches[1], 'H') : 0;
        $minutes = isset($matches[2]) ? rtrim($matches[2], 'M') : 0;
        $seconds = isset($matches[3]) ? rtrim($matches[3], 'S') : 0;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function clearCache(): void
    {
        Cache::forget("youtube_videos_{$this->channelId}_20");
    }
}
