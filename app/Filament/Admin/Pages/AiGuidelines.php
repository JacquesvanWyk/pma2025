<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use Filament\Pages\Page;

class AiGuidelines extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'AI Guidelines';

    protected static ?string $title = 'AI Content Guidelines';

    protected static ?int $navigationSort = 2;

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected string $view = 'filament.admin.pages.ai-guidelines';

    public function getHeading(): string
    {
        return 'AI Content Generation Guidelines';
    }

    public function getSubheading(): ?string
    {
        return 'Theological beliefs and formatting rules that guide AI-generated content';
    }
}
