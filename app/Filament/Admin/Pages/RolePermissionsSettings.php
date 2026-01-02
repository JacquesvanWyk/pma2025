<?php

namespace App\Filament\Admin\Pages;

use App\Settings\RolePermissions;
use Filament\Forms\Components\CheckboxList;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class RolePermissionsSettings extends SettingsPage
{
    protected static string $settings = RolePermissions::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Role Permissions';

    protected static ?string $title = 'Role Permissions';

    protected static ?int $navigationSort = 100;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user && $user->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function form(Schema $schema): Schema
    {
        $pageOptions = $this->getPageOptions();

        return $schema
            ->components([
                Section::make('Pastor Pages')
                    ->description('Select which pages and resources pastors can access.')
                    ->schema([
                        CheckboxList::make('pastor_pages')
                            ->label('Allowed Pages')
                            ->options($pageOptions)
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2),
                    ]),

                Section::make('Team Member Pages')
                    ->description('Select which pages and resources team members can access.')
                    ->schema([
                        CheckboxList::make('team_member_pages')
                            ->label('Allowed Pages')
                            ->options($pageOptions)
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2),
                    ]),
            ]);
    }

    /**
     * Get all available pages and resources as options.
     *
     * @return array<string, string>
     */
    protected function getPageOptions(): array
    {
        $settings = app(RolePermissions::class);
        $allPages = $settings->admin_pages;

        $options = [];
        foreach ($allPages as $page) {
            $options[$page] = $this->formatPageName($page);
        }

        asort($options);

        return $options;
    }

    /**
     * Format a page class name to a human-readable label.
     */
    protected function formatPageName(string $pageClass): string
    {
        $className = class_basename($pageClass);

        // Remove common suffixes
        $className = preg_replace('/(Resource|Page)$/', '', $className);

        // Convert camelCase to Title Case with spaces
        $formatted = preg_replace('/(?<!^)([A-Z])/', ' $1', $className);

        // Determine the type
        if (str_contains($pageClass, 'Resources\\')) {
            return $formatted.' (Resource)';
        }

        return $formatted.' (Page)';
    }
}
