<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Album;
use App\Models\Ebook;
use App\Models\Note;
use App\Models\PictureStudy;
use App\Models\Song;
use App\Models\Tract;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;

class DownloadAnalytics extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::ArrowDownTray;

    protected static \UnitEnum|string|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Download Analytics';

    protected static ?string $title = 'Download Analytics';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.admin.pages.download-analytics';

    public string $activeTab = 'overview';

    public array $summary = [];

    public Collection $tracts;

    public Collection $notes;

    public Collection $pictureStudies;

    public Collection $ebooks;

    public Collection $albums;

    public Collection $songs;

    public int $userCount = 0;

    public int $recentUserCount = 0;

    public function mount(): void
    {
        $this->loadData();
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    protected function loadData(): void
    {
        $this->loadSummary();
        $this->loadTracts();
        $this->loadNotes();
        $this->loadPictureStudies();
        $this->loadEbooks();
        $this->loadAlbums();
        $this->loadSongs();
        $this->loadUserStats();
    }

    protected function loadSummary(): void
    {
        $this->summary = [
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
        ];

        $this->summary['total_downloads'] = $this->summary['tracts']['downloads']
            + $this->summary['notes']['downloads']
            + $this->summary['picture_studies']['downloads']
            + $this->summary['ebooks']['downloads']
            + $this->summary['albums']['downloads']
            + $this->summary['songs']['downloads'];
    }

    protected function loadTracts(): void
    {
        $this->tracts = Tract::orderByDesc('download_count')->get();
    }

    protected function loadNotes(): void
    {
        $this->notes = Note::orderByDesc('download_count')->get();
    }

    protected function loadPictureStudies(): void
    {
        $this->pictureStudies = PictureStudy::orderByDesc('download_count')->get();
    }

    protected function loadEbooks(): void
    {
        $this->ebooks = Ebook::orderByDesc('download_count')->get();
    }

    protected function loadAlbums(): void
    {
        $this->albums = Album::withCount('songs')->orderByDesc('download_count')->get();
    }

    protected function loadSongs(): void
    {
        $this->songs = Song::with('album')->orderByDesc('download_count')->get();
    }

    protected function loadUserStats(): void
    {
        $this->userCount = User::count();
        $this->recentUserCount = User::where('created_at', '>=', now()->subDays(30))->count();
    }
}
