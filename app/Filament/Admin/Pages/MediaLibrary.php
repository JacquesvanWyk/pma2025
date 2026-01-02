<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\GeneratedMedia;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder-open';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?string $title = 'Generated Media Library';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.admin.pages.media-library';

    public string $activeTab = 'all';

    public ?string $search = null;

    public function mount(): void
    {
        $type = request()->query('type');
        if ($type && in_array($type, ['image', 'video', 'music'])) {
            $this->activeTab = $type;
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getMediaProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = GeneratedMedia::where('user_id', auth()->id())
            ->completed()
            ->latest();

        if ($this->activeTab !== 'all') {
            $query->where('type', $this->activeTab);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('prompt', 'like', "%{$this->search}%");
            });
        }

        return $query->paginate(12);
    }

    public function getStatsProperty(): array
    {
        $userId = auth()->id();

        return [
            'total' => GeneratedMedia::where('user_id', $userId)->completed()->count(),
            'images' => GeneratedMedia::where('user_id', $userId)->images()->completed()->count(),
            'music' => GeneratedMedia::where('user_id', $userId)->music()->completed()->count(),
            'videos' => GeneratedMedia::where('user_id', $userId)->videos()->completed()->count(),
            'total_cost' => GeneratedMedia::where('user_id', $userId)->completed()->sum('cost_usd'),
        ];
    }

    public function deleteMedia(int $id): void
    {
        $media = GeneratedMedia::where('user_id', auth()->id())->find($id);

        if ($media) {
            if ($media->file_path) {
                Storage::disk(config('kie.storage.disk', 'public'))->delete($media->file_path);
            }
            if ($media->thumbnail_path) {
                Storage::disk(config('kie.storage.disk', 'public'))->delete($media->thumbnail_path);
            }

            $media->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Media deleted successfully',
            ]);
        }
    }

    public function downloadMedia(int $id): ?\Symfony\Component\HttpFoundation\StreamedResponse
    {
        $media = GeneratedMedia::where('user_id', auth()->id())->find($id);

        if (! $media) {
            return null;
        }

        if ($media->file_path && Storage::disk(config('kie.storage.disk', 'public'))->exists($media->file_path)) {
            return Storage::disk(config('kie.storage.disk', 'public'))->download($media->file_path);
        }

        if ($media->remote_url) {
            return redirect($media->remote_url);
        }

        return null;
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
