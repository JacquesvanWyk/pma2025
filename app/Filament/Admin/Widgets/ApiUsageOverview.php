<?php

namespace App\Filament\Admin\Widgets;

use App\Services\ApiUsageTracker;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApiUsageOverview extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            $todayStats = ApiUsageTracker::getUsageStats('today');
            $hourStats = ApiUsageTracker::getUsageStats('this_hour');
            $weekStats = ApiUsageTracker::getUsageStats('this_week');
            $monthStats = ApiUsageTracker::getUsageStats('this_month');
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesn't exist yet - provide empty stats
            if (str_contains($e->getMessage(), "doesn't exist")) {
                $todayStats = [
                    'total_requests' => 0,
                    'successful_requests' => 0,
                    'rate_limited_requests' => 0,
                    'failed_requests' => 0,
                    'gemini_requests' => 0,
                    'nanobanana_requests' => 0,
                    'image_generation_requests' => 0,
                ];
                $hourStats = $todayStats;
                $weekStats = $todayStats;
                $monthStats = $todayStats;
            } else {
                throw $e;
            }
        }

        return [
            Stat::make('Today\'s Requests', $todayStats['total_requests'])
                ->description($todayStats['successful_requests'].' successful')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 12, 10, 14, 15, 18, $todayStats['total_requests']]),

            Stat::make('This Hour', $hourStats['total_requests'])
                ->description($hourStats['rate_limited_requests'].' rate limited')
                ->descriptionIcon('heroicon-m-clock')
                ->color($hourStats['rate_limited_requests'] > 0 ? 'warning' : 'primary'),

            Stat::make('Gemini Usage', $todayStats['gemini_requests'])
                ->description($todayStats['image_generation_requests'].' image generations')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),

            Stat::make('Nano Banana Usage', $todayStats['nanobanana_requests'])
                ->description($todayStats['image_generation_requests'].' image generations')
                ->descriptionIcon('heroicon-m-photo')
                ->color('warning'),

            Stat::make('Success Rate', $todayStats['total_requests'] > 0
                ? round(($todayStats['successful_requests'] / $todayStats['total_requests']) * 100, 1).'%'
                : '0%')
                ->description($todayStats['failed_requests'].' failed')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($todayStats['failed_requests'] > 0 ? 'danger' : 'success'),

            Stat::make('This Week', $weekStats['total_requests'])
                ->description($monthStats['total_requests'].' this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
        ];
    }
}
