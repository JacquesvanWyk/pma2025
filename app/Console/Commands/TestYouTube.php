<?php

namespace App\Console\Commands;

use App\Services\YouTubeService;
use Illuminate\Console\Command;

class TestYouTube extends Command
{
    protected $signature = 'youtube:test';

    protected $description = 'Test YouTube service integration';

    public function handle(YouTubeService $youtubeService): void
    {
        $this->info('Testing YouTube API integration...');
        $this->info('');

        $videos = $youtubeService->getChannelVideos(5);

        if (empty($videos)) {
            $this->error('No videos found. Please check:');
            $this->error('1. YOUTUBE_API_KEY is correct in .env');
            $this->error('2. YOUTUBE_CHANNEL_ID is correct in .env');
            $this->error('3. API is enabled in Google Console');

            return;
        }

        $this->info('Found '.count($videos).' videos:');
        $this->info('');

        foreach ($videos as $video) {
            $this->line("Title: {$video['title']}");
            $this->line("Video ID: {$video['id']}");
            $this->line("Published: {$video['published_at']}");
            $this->line('---');
        }

        $this->info('');
        $this->info('✅ YouTube integration is working!');
    }
}
