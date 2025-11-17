<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetYouTubeChannelId extends Command
{
    protected $signature = 'youtube:get-channel-id {handle}';

    protected $description = 'Get YouTube channel ID from channel handle';

    public function handle(): void
    {
        $handle = $this->argument('handle');
        $apiKey = config('services.youtube.api_key');

        if (! $apiKey) {
            $this->error('YouTube API key not configured. Please set YOUTUBE_API_KEY in your .env file.');

            return;
        }

        $cleanHandle = str_replace('@', '', $handle);

        $this->info("Searching for channel: @{$cleanHandle}");

        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'key' => $apiKey,
            'q' => $cleanHandle,
            'type' => 'channel',
            'part' => 'snippet',
            'maxResults' => 1,
        ]);

        if ($response->failed()) {
            $this->error('Failed to fetch channel data from YouTube API.');
            $this->error('Response: '.$response->body());

            return;
        }

        $items = $response->json('items', []);

        if (empty($items)) {
            $this->error('No channel found with that handle.');

            return;
        }

        $channelId = $items[0]['id']['channelId'] ?? null;
        $channelTitle = $items[0]['snippet']['title'] ?? 'Unknown';

        if ($channelId) {
            $this->info('');
            $this->info("Channel Found: {$channelTitle}");
            $this->info("Channel ID: {$channelId}");
            $this->info('');
            $this->info('Add this to your .env file:');
            $this->line("YOUTUBE_CHANNEL_ID={$channelId}");
        } else {
            $this->error('Could not extract channel ID from response.');
        }
    }
}
