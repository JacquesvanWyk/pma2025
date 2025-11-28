<?php

namespace App\Http\Controllers;

use App\Services\YouTubeService;

class SermonController extends Controller
{
    public function __construct(protected YouTubeService $youtubeService) {}

    public function index()
    {
        $videos = $this->youtubeService->getChannelVideos(50);

        $featuredVideo = ! empty($videos) ? array_shift($videos) : null;
        $archiveVideos = $videos;

        return view('sermons.index', [
            'featuredVideo' => $featuredVideo,
            'archiveVideos' => $archiveVideos,
        ]);
    }
}
