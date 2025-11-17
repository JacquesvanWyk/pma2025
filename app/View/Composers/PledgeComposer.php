<?php

namespace App\View\Composers;

use App\Models\PledgeProgress;
use App\Services\YouTubeService;
use Illuminate\View\View;

class PledgeComposer
{
    public function __construct(protected YouTubeService $youtubeService) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $pledgeProgress = PledgeProgress::current();
        $currentPledges = $pledgeProgress ? $pledgeProgress->current_amount : 0;
        $pledgeMonth = $pledgeProgress ? $pledgeProgress->month : now()->format('F');
        $pledgeGoal = $pledgeProgress ? $pledgeProgress->goal_amount : 35000;
        $pledgePercentage = $pledgeProgress ? $pledgeProgress->percentage : 0;

        $featuredStudies = collect([
            (object) [
                'title' => 'The Father and the Son',
                'excerpt' => 'Understanding the true relationship between God the Father and His Son Jesus Christ as taught by the Adventist pioneers.',
                'slug' => 'the-father-and-the-son',
                'read_time' => 15,
                'category' => (object) ['name' => 'Pioneers'],
            ],
            (object) [
                'title' => 'Where it all began',
                'excerpt' => 'Explore the foundational truths that sparked the Advent movement and shaped our understanding of present truth.',
                'slug' => 'where-it-all-began',
                'read_time' => 10,
                'category' => (object) ['name' => 'Pioneers'],
            ],
            (object) [
                'title' => 'Understanding Revelation 18',
                'excerpt' => 'The Loud Cry message of Revelation 18 and its significance for God\'s people in these last days.',
                'slug' => 'understanding-revelation-18',
                'read_time' => 20,
                'category' => (object) ['name' => 'Bible Scriptures'],
            ],
        ]);

        $videos = $this->youtubeService->getChannelVideos(6);

        $latestSermons = collect($videos)->take(3)->map(function ($video) {
            return (object) [
                'youtube_id' => $video['id'],
                'title' => $video['title'],
                'speaker' => $video['channel_title'],
                'date_preached' => \Carbon\Carbon::parse($video['published_at']),
            ];
        });

        $view->with(compact('currentPledges', 'pledgeMonth', 'pledgeGoal', 'pledgePercentage', 'featuredStudies', 'latestSermons'));
    }
}
