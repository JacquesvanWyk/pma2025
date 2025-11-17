<x-filament-panels::page>
    <div class="space-y-6">
        @if(!\Schema::hasTable('api_usage_stats'))
            <!-- Migration Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-yellow-600" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Migration Required</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>The API usage tracking table hasn't been created yet. Please run the migration to enable usage tracking:</p>
                            <code class="block mt-2 p-2 bg-yellow-100 rounded text-xs">php artisan migrate</code>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{ $this->headerWidgets }}

        <!-- Recent Activity Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent API Activity</h3>

            @if(!empty($recentActivity))
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Provider
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Model
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity['created_at'])->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst($activity['provider']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst(str_replace('-', ' ', $activity['service'])) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity['model'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($activity['status'] === 'success') bg-green-100 text-green-800
                                            @elseif($activity['status'] === 'rate_limited') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($activity['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity['user']['name'] ?? 'Guest' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <x-filament::icon icon="heroicon-o-chart-bar" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-sm font-medium text-gray-600 mb-1">No API activity yet</p>
                    <p class="text-xs text-gray-500">API usage statistics will appear here once you start using the AI tools.</p>
                </div>
            @endif
        </div>

        <!-- Provider Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gemini Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Requests Today</span>
                        <span class="text-sm font-medium text-gray-900">{{ $geminiStats['total_requests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Success Rate</span>
                        <span class="text-sm font-medium text-green-600">
                            {{ $geminiStats['total_requests'] > 0 ? round(($geminiStats['successful_requests'] / $geminiStats['total_requests']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rate Limited</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $geminiStats['rate_limited_requests'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nano Banana Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Requests Today</span>
                        <span class="text-sm font-medium text-gray-900">{{ $nanobananaStats['total_requests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Success Rate</span>
                        <span class="text-sm font-medium text-green-600">
                            {{ $nanobananaStats['total_requests'] > 0 ? round(($nanobananaStats['successful_requests'] / $nanobananaStats['total_requests']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Failed Requests</span>
                        <span class="text-sm font-medium text-red-600">{{ $nanobananaStats['failed_requests'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-blue-600" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">About API Usage Tracking</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>This page tracks API usage across all AI tools in the system. It monitors:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Request counts for Gemini and Nano Banana APIs</li>
                            <li>Success rates and error tracking</li>
                            <li>Rate limit occurrences and timing</li>
                            <li>Usage patterns by service and time period</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>