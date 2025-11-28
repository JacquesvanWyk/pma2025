<?php

namespace App\Http\Controllers;

use App\Services\YouTubeService;

class KingdomKidsController extends Controller
{
    public function __construct(protected YouTubeService $youtubeService) {}

    public function index()
    {
        // Playlist ID: PL-ByYzj89Ld4ziQAA1knVdiZ8piemQyb3
        $videos = $this->youtubeService->getPlaylistVideos('PL-ByYzj89Ld4ziQAA1knVdiZ8piemQyb3', 50);

        return view('kingdom-kids.index', [
            'videos' => $videos,
        ]);
    }
}
