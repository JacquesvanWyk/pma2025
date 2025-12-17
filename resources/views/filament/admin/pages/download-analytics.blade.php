<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Summary Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <!-- Total Downloads -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg">
                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['total_downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Total Downloads</p>
                </div>
            </div>

            <!-- Tracts -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('tracts')">
                <x-filament::icon icon="heroicon-o-document-text" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['tracts']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Tracts ({{ $summary['tracts']['published'] ?? 0 }})</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('notes')">
                <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['notes']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Notes ({{ $summary['notes']['published'] ?? 0 }})</p>
                </div>
            </div>

            <!-- Picture Studies -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('picture_studies')">
                <x-filament::icon icon="heroicon-o-photo" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['picture_studies']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Picture Studies ({{ $summary['picture_studies']['published'] ?? 0 }})</p>
                </div>
            </div>

            <!-- eBooks -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('ebooks')">
                <x-filament::icon icon="heroicon-o-book-open" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['ebooks']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">eBooks ({{ $summary['ebooks']['total'] ?? 0 }})</p>
                </div>
            </div>

            <!-- Albums -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('albums')">
                <x-filament::icon icon="heroicon-o-musical-note" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['albums']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Albums ({{ $summary['albums']['published'] ?? 0 }})</p>
                </div>
            </div>

            <!-- Songs -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('songs')">
                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['songs']['downloads'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Song Downloads</p>
                </div>
            </div>

            <!-- Song Plays -->
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-4 text-white shadow-lg cursor-pointer hover:scale-105 transition-transform" wire:click="setActiveTab('songs')">
                <x-filament::icon icon="heroicon-o-play" class="w-6 h-6 opacity-80" />
                <div class="mt-2">
                    <p class="text-2xl font-bold">{{ number_format($summary['songs']['plays'] ?? 0) }}</p>
                    <p class="text-xs opacity-80">Song Plays</p>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                        <x-filament::icon icon="heroicon-o-user-group" class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Registered Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($userCount) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 30 days</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">+{{ $recentUserCount }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-4 overflow-x-auto" aria-label="Tabs">
                @foreach([
                    'overview' => 'Overview',
                    'tracts' => 'Tracts',
                    'notes' => 'Notes',
                    'picture_studies' => 'Picture Studies',
                    'ebooks' => 'eBooks',
                    'albums' => 'Albums',
                    'songs' => 'Songs',
                ] as $tab => $label)
                    <button
                        wire:click="setActiveTab('{{ $tab }}')"
                        class="whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm transition-colors {{ $activeTab === $tab ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            @if($activeTab === 'overview')
                <!-- Overview Tab -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Download Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Album Downloads Breakdown -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Album Downloads</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Audio Bundles</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['albums']['audio_downloads'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Video Bundles</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['albums']['video_downloads'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Full Bundles</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['albums']['full_downloads'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Song Downloads Breakdown -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Song Downloads</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Audio Files</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['songs']['audio_downloads'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Video Files</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['songs']['video_downloads'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Lyrics</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['songs']['lyrics_downloads'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Song Bundles</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['songs']['bundle_downloads'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Song Plays Stats -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Song Play Stats</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Plays</span>
                                    <span class="font-medium text-pink-600 dark:text-pink-400">{{ number_format($summary['songs']['plays'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Songs</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($summary['songs']['published'] ?? 0) }}</span>
                                </div>
                                @if(($summary['songs']['published'] ?? 0) > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Avg Plays/Song</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format(($summary['songs']['plays'] ?? 0) / $summary['songs']['published'], 1) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Top Downloaded Content -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Top 5 Downloads</h4>
                            <div class="space-y-2">
                                @foreach($tracts->take(5) as $tract)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[180px]">{{ $tract->title }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($tract->download_count) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($activeTab === 'tracts')
                <!-- Tracts Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tracts as $tract)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $tract->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($tract->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($tract->download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No tracts found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'notes')
                <!-- Notes Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($notes as $note)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $note->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($note->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($note->download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No notes found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'picture_studies')
                <!-- Picture Studies Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pictureStudies as $study)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $study->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($study->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($study->download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No picture studies found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'ebooks')
                <!-- eBooks Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ebooks as $ebook)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $ebook->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($ebook->download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No eBooks found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'albums')
                <!-- Albums Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Album</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Songs</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Audio</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Video</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($albums as $album)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($album->cover_image)
                                                <img src="{{ Storage::url($album->cover_image) }}" alt="{{ $album->title }}" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                    <x-filament::icon icon="heroicon-o-musical-note" class="w-5 h-5 text-gray-400" />
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $album->title }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $album->artist }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">{{ $album->songs_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($album->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($album->download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($album->audio_download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($album->video_download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($album->full_download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No albums found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'songs')
                <!-- Songs Tab -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Song</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Album</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-pink-500 dark:text-pink-400 uppercase tracking-wider">Plays</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Audio</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Video</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lyrics</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bundle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($songs as $song)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $song->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Track {{ $song->track_number }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $song->album?->title ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-pink-600 dark:text-pink-400">{{ number_format($song->play_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($song->download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($song->audio_download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($song->video_download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($song->lyrics_download_count) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($song->bundle_download_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No songs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
