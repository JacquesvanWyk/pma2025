<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\ApiUsageStat;
use Filament\Actions\Action;
use Filament\Pages\Page;

class ApiUsageStats extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'API Usage Stats';

    protected static ?string $title = 'API Usage Statistics';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.admin.pages.api-usage-stats';

    public array $recentActivity = [];

    public array $geminiStats = [];

    public array $nanobananaStats = [];

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        try {
            $this->recentActivity = ApiUsageStat::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->toArray();

            $this->geminiStats = [
                'total_requests' => ApiUsageStat::forProvider('gemini')->today()->count(),
                'successful_requests' => ApiUsageStat::forProvider('gemini')->successful()->today()->count(),
                'rate_limited_requests' => ApiUsageStat::forProvider('gemini')->rateLimited()->today()->count(),
                'failed_requests' => ApiUsageStat::forProvider('gemini')->failed()->today()->count(),
            ];

            $this->nanobananaStats = [
                'total_requests' => ApiUsageStat::forProvider('nanobanana')->today()->count(),
                'successful_requests' => ApiUsageStat::forProvider('nanobanana')->successful()->today()->count(),
                'rate_limited_requests' => ApiUsageStat::forProvider('nanobanana')->rateLimited()->today()->count(),
                'failed_requests' => ApiUsageStat::forProvider('nanobanana')->failed()->today()->count(),
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesn't exist yet - provide empty data
            if (str_contains($e->getMessage(), "doesn't exist")) {
                $this->recentActivity = [];
                $this->geminiStats = [
                    'total_requests' => 0,
                    'successful_requests' => 0,
                    'rate_limited_requests' => 0,
                    'failed_requests' => 0,
                ];
                $this->nanobananaStats = [
                    'total_requests' => 0,
                    'successful_requests' => 0,
                    'rate_limited_requests' => 0,
                    'failed_requests' => 0,
                ];
            } else {
                throw $e;
            }
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Stats')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->loadData()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\ApiUsageOverview::class,
        ];
    }
}
