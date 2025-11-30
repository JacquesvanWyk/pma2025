<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Gallery;
use App\Models\Note;
use App\Models\PictureStudy;
use App\Models\PledgeProgress;
use App\Models\PrayerRoomSession;
use App\Models\Study;
use App\Models\Tract;
use App\Services\YouTubeService;

class HomeController extends Controller
{
    public function __construct(protected YouTubeService $youtubeService) {}

    public function index()
    {
        // Pledge progress
        $pledgeProgress = PledgeProgress::current();
        $currentPledges = $pledgeProgress ? $pledgeProgress->current_amount : 0;
        $pledgeMonth = $pledgeProgress ? $pledgeProgress->month : now()->format('F');
        $pledgeGoal = $pledgeProgress ? $pledgeProgress->goal_amount : 35000;
        $pledgePercentage = $pledgeProgress ? $pledgeProgress->percentage : 0;
        // Random published study
        $featuredStudy = Study::published()
            ->inRandomOrder()
            ->first();

        // Random English ebook
        $englishEbook = Ebook::where('language', 'English')
            ->inRandomOrder()
            ->first();

        // Random Afrikaans ebook
        $afrikaansEbook = Ebook::where('language', 'Afrikaans')
            ->inRandomOrder()
            ->first();

        // Latest tracts
        $latestTracts = Tract::published()
            ->latest()
            ->take(4)
            ->get();

        // Latest notes
        $latestNotes = Note::published()
            ->latest()
            ->take(4)
            ->get();

        // Latest sermon from YouTube
        $videos = $this->youtubeService->getChannelVideos(1);
        $latestSermon = ! empty($videos) ? $videos[0] : null;

        // Resource counts
        $resourceCounts = [
            'ebooks' => Ebook::count(),
            'tracts' => Tract::whereNull('deleted_at')->count(),
            'notes' => Note::where('status', 'published')->count(),
        ];

        // Upcoming prayer room session
        $upcomingPrayerSession = PrayerRoomSession::upcoming()
            ->orderBy('session_date')
            ->first();

        // Random gallery
        $randomGallery = Gallery::where('status', 'published')
            ->inRandomOrder()
            ->first();

        // Random picture study
        $randomPictureStudy = PictureStudy::where('status', 'published')
            ->inRandomOrder()
            ->first();

        return view('home', compact(
            'featuredStudy',
            'englishEbook',
            'afrikaansEbook',
            'latestTracts',
            'latestNotes',
            'latestSermon',
            'resourceCounts',
            'upcomingPrayerSession',
            'currentPledges',
            'pledgeMonth',
            'pledgeGoal',
            'pledgePercentage',
            'randomGallery',
            'randomPictureStudy'
        ));
    }
}
