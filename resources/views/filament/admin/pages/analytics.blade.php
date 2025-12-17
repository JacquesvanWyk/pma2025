<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Date Range Filter -->
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Period:</span>
                <select
                    wire:model.live="dateRange"
                    wire:change="$refresh"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
                <div wire:loading wire:target="dateRange" class="ml-2">
                    <x-filament::loading-indicator class="h-5 w-5" />
                </div>
            </div>
            @if(isset($activeVisitors['visitors']) && $activeVisitors['visitors'] > 0)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-sm font-medium text-green-700 dark:text-green-400">
                        {{ $activeVisitors['visitors'] }} active {{ Str::plural('visitor', $activeVisitors['visitors']) }}
                    </span>
                </div>
            @endif
        </div>

        @if($hasError)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-red-600 dark:text-red-400" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Analytics Error</h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Overview Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Visitors -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-users" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ number_format($summary['visitors'] ?? 0) }}</p>
                    <p class="text-sm opacity-80">Visitors</p>
                </div>
            </div>

            <!-- Page Views -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-eye" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ number_format($summary['views'] ?? 0) }}</p>
                    <p class="text-sm opacity-80">Page Views</p>
                </div>
            </div>

            <!-- Bounce Rate -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-arrow-uturn-left" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ $summary['bounce_rate'] ?? 0 }}%</p>
                    <p class="text-sm opacity-80">Bounce Rate</p>
                </div>
            </div>

            <!-- Total Downloads -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ number_format($downloadStats['total_downloads'] ?? 0) }}</p>
                    <p class="text-sm opacity-80">Total Downloads</p>
                </div>
            </div>

            <!-- Users -->
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-user-group" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ number_format($downloadStats['users']['total'] ?? 0) }}</p>
                    <p class="text-sm opacity-80">Users</p>
                </div>
            </div>

            <!-- Sessions -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <x-filament::icon icon="heroicon-o-clock" class="w-8 h-8 opacity-80" />
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-bold">{{ number_format($summary['sessions'] ?? 0) }}</p>
                    <p class="text-sm opacity-80">Sessions</p>
                </div>
            </div>
        </div>

        <!-- Visitors Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Visitor Trends</h3>
            @if(!empty($dailyStats))
                <div
                    class="h-64"
                    x-data="{
                        chart: null,
                        init() {
                            this.$nextTick(() => this.renderChart());
                            Livewire.on('analyticsUpdated', (event) => {
                                this.updateChart(event.chartData);
                            });
                        },
                        renderChart() {
                            const ctx = this.$refs.canvas;
                            if (!ctx) return;

                            const chartData = {{ Js::from($this->getChartData()) }};
                            const isDark = document.documentElement.classList.contains('dark');
                            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                            const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';

                            if (this.chart) {
                                this.chart.destroy();
                            }

                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: chartData.labels,
                                    datasets: [
                                        {
                                            label: 'Visitors',
                                            data: chartData.visitors,
                                            borderColor: 'rgb(59, 130, 246)',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            fill: true,
                                            tension: 0.4,
                                        },
                                        {
                                            label: 'Page Views',
                                            data: chartData.views,
                                            borderColor: 'rgb(168, 85, 247)',
                                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                            fill: true,
                                            tension: 0.4,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: { intersect: false, mode: 'index' },
                                    plugins: {
                                        legend: { position: 'top', labels: { color: textColor } },
                                    },
                                    scales: {
                                        x: { grid: { color: gridColor }, ticks: { color: textColor } },
                                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } }
                                    }
                                }
                            });
                        },
                        updateChart(chartData) {
                            if (this.chart && chartData) {
                                this.chart.data.labels = chartData.labels;
                                this.chart.data.datasets[0].data = chartData.visitors;
                                this.chart.data.datasets[1].data = chartData.views;
                                this.chart.update();
                            }
                        }
                    }"
                >
                    <canvas x-ref="canvas"></canvas>
                </div>
            @else
                <div class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400">
                    <p>No data available for the selected period</p>
                </div>
            @endif
        </div>

        <!-- Download Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Download Statistics</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Tracts -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                            <x-filament::icon icon="heroicon-o-document-text" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tracts</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($downloadStats['tracts']['downloads'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $downloadStats['tracts']['published'] ?? 0 }} published</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Notes</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($downloadStats['notes']['downloads'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $downloadStats['notes']['published'] ?? 0 }} published</p>
                        </div>
                    </div>
                </div>

                <!-- Picture Studies -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <x-filament::icon icon="heroicon-o-photo" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Picture Studies</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($downloadStats['picture_studies']['downloads'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $downloadStats['picture_studies']['published'] ?? 0 }} published</p>
                        </div>
                    </div>
                </div>

                <!-- eBooks -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <x-filament::icon icon="heroicon-o-book-open" class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">eBooks</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($downloadStats['ebooks']['downloads'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $downloadStats['ebooks']['total'] ?? 0 }} total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Pages -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h3>
                @if(!empty($topPages))
                    <div class="space-y-3">
                        @foreach($topPages as $page)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $page['path'] ?: '/' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($page['views'] ?? 0) }} views
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ number_format($page['visitors'] ?? 0) }} visitors
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No page data available</p>
                @endif
            </div>

            <!-- Referrers -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h3>
                @if(!empty($referrers))
                    <div class="space-y-3">
                        @foreach($referrers as $referrer)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($referrer['referrer_name'])
                                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-300">
                                                {{ strtoupper(substr($referrer['referrer_name'], 0, 1)) }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <x-filament::icon icon="heroicon-o-globe-alt" class="w-4 h-4 text-green-600 dark:text-green-400" />
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $referrer['referrer_name'] ?: 'Direct' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($referrer['visitors'] ?? 0) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No referrer data available</p>
                @endif
            </div>
        </div>

        <!-- Countries and Browsers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Countries -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Countries</h3>
                @if(!empty($countries))
                    <div class="space-y-3">
                        @foreach($countries as $country)
                            @php
                                $countryName = $this->getCountryName($country['country_code'] ?? '');
                                $percentage = round(($country['relative_visitors'] ?? 0) * 100, 1);
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="text-xl">
                                    @switch(strtolower($country['country_code'] ?? ''))
                                        @case('za') {{ '🇿🇦' }} @break
                                        @case('us') {{ '🇺🇸' }} @break
                                        @case('gb') {{ '🇬🇧' }} @break
                                        @case('br') {{ '🇧🇷' }} @break
                                        @case('ke') {{ '🇰🇪' }} @break
                                        @case('ng') {{ '🇳🇬' }} @break
                                        @case('cn') {{ '🇨🇳' }} @break
                                        @case('de') {{ '🇩🇪' }} @break
                                        @case('mw') {{ '🇲🇼' }} @break
                                        @case('zw') {{ '🇿🇼' }} @break
                                        @default {{ '🌍' }}
                                    @endswitch
                                </span>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $countryName }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $country['visitors'] ?? 0 }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No country data available</p>
                @endif
            </div>

            <!-- Browsers -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browsers</h3>
                @if(!empty($browsers))
                    <div class="space-y-3">
                        @foreach($browsers as $browser)
                            @php
                                $percentage = round(($browser['relative_visitors'] ?? 0) * 100, 1);
                                $colors = [
                                    'Chrome' => 'bg-yellow-500',
                                    'Safari' => 'bg-blue-500',
                                    'Firefox' => 'bg-orange-500',
                                    'Edge' => 'bg-blue-600',
                                    'Android WebView' => 'bg-green-500',
                                    'Samsung Internet' => 'bg-purple-500',
                                ];
                                $color = $colors[$browser['browser'] ?? ''] ?? 'bg-gray-500';
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 {{ $color }} rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">
                                        {{ strtoupper(substr($browser['browser'] ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $browser['browser'] ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $browser['visitors'] ?? 0 }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No browser data available</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-filament-panels::page>
