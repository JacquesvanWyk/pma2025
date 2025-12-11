<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class VideoEditor extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-film';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Video Editor';

    protected static ?string $title = 'Video Editor';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.admin.pages.video-editor';

    public ?string $audioUrl = null;

    public array $lyrics = [];

    public ?string $projectId = null;

    public function mount(): void
    {
        $this->audioUrl = request()->query('audio_url', '');
        $this->lyrics = json_decode(request()->query('lyrics', '[]'), true) ?? [];
        $this->projectId = request()->query('project_id');
    }

    public function getLyricsJson(): string
    {
        return json_encode($this->lyrics);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
