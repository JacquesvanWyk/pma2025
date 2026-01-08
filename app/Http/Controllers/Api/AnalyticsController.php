<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Ebook;
use App\Models\Note;
use App\Models\PictureStudy;
use App\Models\Song;
use App\Models\Tract;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function downloads(): JsonResponse
    {
        $data = [
            'timestamp' => now()->toIso8601String(),
            'summary' => $this->getSummary(),
            'top_downloads' => $this->getTopDownloads(),
        ];

        return response()->json($data);
    }

    protected function getSummary(): array
    {
        return [
            'tracts' => [
                'total' => Tract::count(),
                'published' => Tract::published()->count(),
                'downloads' => (int) Tract::sum('download_count'),
            ],
            'notes' => [
                'total' => Note::count(),
                'published' => Note::published()->count(),
                'downloads' => (int) Note::sum('download_count'),
            ],
            'picture_studies' => [
                'total' => PictureStudy::count(),
                'published' => PictureStudy::published()->count(),
                'downloads' => (int) PictureStudy::sum('download_count'),
            ],
            'ebooks' => [
                'total' => Ebook::count(),
                'downloads' => (int) Ebook::sum('download_count'),
            ],
            'albums' => [
                'total' => Album::count(),
                'published' => Album::where('is_published', true)->count(),
                'downloads' => (int) Album::sum('download_count'),
                'audio_downloads' => (int) Album::sum('audio_download_count'),
                'video_downloads' => (int) Album::sum('video_download_count'),
                'full_downloads' => (int) Album::sum('full_download_count'),
            ],
            'songs' => [
                'total' => Song::count(),
                'published' => Song::where('is_published', true)->count(),
                'downloads' => (int) Song::sum('download_count'),
                'plays' => (int) Song::sum('play_count'),
                'audio_downloads' => (int) Song::sum('audio_download_count'),
                'video_downloads' => (int) Song::sum('video_download_count'),
                'lyrics_downloads' => (int) Song::sum('lyrics_download_count'),
                'bundle_downloads' => (int) Song::sum('bundle_download_count'),
            ],
            'users' => [
                'total' => User::count(),
                'recent_30_days' => User::where('created_at', '>=', now()->subDays(30))->count(),
            ],
            'total_downloads' => (int) Tract::sum('download_count')
                + (int) Note::sum('download_count')
                + (int) PictureStudy::sum('download_count')
                + (int) Ebook::sum('download_count')
                + (int) Album::sum('download_count')
                + (int) Song::sum('download_count'),
        ];
    }

    protected function getTopDownloads(): array
    {
        return [
            'tracts' => Tract::orderByDesc('download_count')->limit(5)->get(['title', 'download_count']),
            'notes' => Note::orderByDesc('download_count')->limit(5)->get(['title', 'download_count']),
            'picture_studies' => PictureStudy::orderByDesc('download_count')->limit(5)->get(['title', 'download_count']),
            'ebooks' => Ebook::orderByDesc('download_count')->limit(5)->get(['title', 'download_count']),
            'songs' => Song::orderByDesc('download_count')->limit(5)->get(['title', 'download_count', 'play_count']),
        ];
    }
}
