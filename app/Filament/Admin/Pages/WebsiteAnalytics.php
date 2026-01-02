<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Services\PirschAnalyticsService;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class WebsiteAnalytics extends Page
{
    use HasRoleAccess;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::GlobeAlt;

    protected static \UnitEnum|string|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Website Analytics';

    protected static ?string $title = 'Website Analytics';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.admin.pages.website-analytics';

    public string $dateRange = '30';

    public array $summary = [];

    public array $dailyStats = [];

    public array $topPages = [];

    public array $referrers = [];

    public array $countries = [];

    public array $browsers = [];

    public array $activeVisitors = [];

    public bool $hasError = false;

    public string $errorMessage = '';

    public function mount(): void
    {
        $this->loadPirschData();
    }

    public function updatedDateRange(): void
    {
        $this->loadPirschData();
        $this->dispatch('analyticsUpdated', chartData: $this->getChartData());
    }

    protected function loadPirschData(): void
    {
        try {
            $pirsch = PirschAnalyticsService::make();
            $from = now()->subDays((int) $this->dateRange)->format('Y-m-d');
            $to = now()->format('Y-m-d');

            $this->summary = $pirsch->getSummary($from, $to);
            $this->dailyStats = $this->summary['daily_stats'] ?? [];
            $this->topPages = $pirsch->getTopPages($from, $to, 10);
            $this->referrers = $pirsch->getReferrers($from, $to, 10);
            $this->countries = $pirsch->getCountries($from, $to, 10);
            $this->browsers = $pirsch->getBrowsers($from, $to, 5);
            $this->activeVisitors = $pirsch->getActiveVisitors();

            $this->hasError = false;
            $this->errorMessage = '';
        } catch (\Exception $e) {
            $this->hasError = true;
            $this->errorMessage = $e->getMessage();
            $this->summary = ['visitors' => 0, 'views' => 0, 'sessions' => 0, 'bounce_rate' => 0];
            $this->dailyStats = [];
            $this->topPages = [];
            $this->referrers = [];
            $this->countries = [];
            $this->browsers = [];
            $this->activeVisitors = ['visitors' => 0];
        }
    }

    public function getChartData(): array
    {
        $labels = [];
        $visitors = [];
        $views = [];

        foreach ($this->dailyStats as $day) {
            $date = \Carbon\Carbon::parse($day['day']);
            $labels[] = $date->format('M d');
            $visitors[] = $day['visitors'] ?? 0;
            $views[] = $day['views'] ?? 0;
        }

        return [
            'labels' => $labels,
            'visitors' => $visitors,
            'views' => $views,
        ];
    }

    public function getCountryName(string $code): string
    {
        return PirschAnalyticsService::make()->getCountryName($code);
    }
}
