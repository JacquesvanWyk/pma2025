<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Media</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Images</p>
                <p class="text-2xl font-bold text-blue-600">{{ $this->stats['images'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Music</p>
                <p class="text-2xl font-bold text-purple-600">{{ $this->stats['music'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Videos</p>
                <p class="text-2xl font-bold text-green-600">{{ $this->stats['videos'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Cost</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($this->stats['total_cost'], 2) }}</p>
            </div>
        </div>

        <!-- Tabs and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap items-center justify-between px-4">
                    <nav class="flex -mb-px space-x-6">
                        @foreach (['all' => 'All', 'image' => 'Images', 'music' => 'Music', 'video' => 'Videos'] as $tab => $label)
                            <button
                                wire:click="setTab('{{ $tab }}')"
                                class="py-4 px-1 border-b-2 text-sm font-medium transition-colors {{ $this->activeTab === $tab ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                            >
                                {{ $label }}
                                @if ($tab === 'all')
                                    <span class="ml-1 text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $this->stats['total'] }}</span>
                                @elseif ($tab === 'image')
                                    <span class="ml-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $this->stats['images'] }}</span>
                                @elseif ($tab === 'music')
                                    <span class="ml-1 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-2 py-0.5 rounded-full">{{ $this->stats['music'] }}</span>
                                @elseif ($tab === 'video')
                                    <span class="ml-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-2 py-0.5 rounded-full">{{ $this->stats['videos'] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </nav>

                    <div class="py-2">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search media..."
                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                        />
                    </div>
                </div>
            </div>

            <!-- Media Grid -->
            <div class="p-4">
                @if ($this->media->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($this->media as $item)
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden group">
                                <!-- Preview -->
                                <div class="relative aspect-square">
                                    @if ($item->type === 'image')
                                        <img
                                            src="{{ $item->file_url }}"
                                            alt="{{ $item->title ?? $item->prompt }}"
                                            class="w-full h-full object-cover"
                                        />
                                    @elseif ($item->type === 'music')
                                        @php
                                            $imageUrl = $item->metadata['tracks'][0]['image_url'] ?? null;
                                        @endphp
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                                <x-filament::icon icon="heroicon-o-musical-note" class="w-16 h-16 text-white" />
                                            </div>
                                        @endif
                                    @elseif ($item->type === 'video')
                                        @if ($item->thumbnail_url)
                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                                <x-filament::icon icon="heroicon-o-video-camera" class="w-16 h-16 text-white" />
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Type Badge -->
                                    <div class="absolute top-2 left-2">
                                        @if ($item->type === 'image')
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-500 text-white rounded-full">Image</span>
                                        @elseif ($item->type === 'music')
                                            <span class="px-2 py-1 text-xs font-medium bg-purple-500 text-white rounded-full">Music</span>
                                        @elseif ($item->type === 'video')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-500 text-white rounded-full">Video</span>
                                        @endif
                                    </div>

                                    <!-- Overlay Actions -->
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        @if ($item->file_url)
                                            <a
                                                href="{{ $item->file_url }}"
                                                target="_blank"
                                                class="p-2 bg-white rounded-full hover:bg-gray-100"
                                                title="View"
                                            >
                                                <x-filament::icon icon="heroicon-o-eye" class="w-5 h-5 text-gray-700" />
                                            </a>
                                            <a
                                                href="{{ $item->file_url }}"
                                                download
                                                class="p-2 bg-white rounded-full hover:bg-gray-100"
                                                title="Download"
                                            >
                                                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-5 h-5 text-gray-700" />
                                            </a>
                                        @endif
                                        <button
                                            wire:click="deleteMedia({{ $item->id }})"
                                            wire:confirm="Are you sure you want to delete this media?"
                                            class="p-2 bg-red-500 rounded-full hover:bg-red-600"
                                            title="Delete"
                                        >
                                            <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5 text-white" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                        {{ $item->title ?? Str::limit($item->prompt, 30) }}
                                    </h3>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                                        @if ($item->cost_usd)
                                            <p class="text-xs text-gray-400">${{ number_format($item->cost_usd, 3) }}</p>
                                        @endif
                                    </div>

                                    @if ($item->type === 'music' && $item->formatted_duration)
                                        <p class="text-xs text-gray-400 mt-1">{{ $item->formatted_duration }}</p>
                                    @endif

                                    <!-- Audio Player for Music -->
                                    @if ($item->type === 'music')
                                        @php
                                            $audioUrl = $item->metadata['tracks'][0]['audio_url'] ?? $item->file_url;
                                        @endphp
                                        @if ($audioUrl)
                                            <audio controls class="w-full mt-2 h-8">
                                                <source src="{{ $audioUrl }}" type="audio/mpeg">
                                            </audio>
                                        @endif
                                    @endif

                                    <!-- Video Player -->
                                    @if ($item->type === 'video' && $item->file_url)
                                        <video controls class="w-full mt-2 rounded">
                                            <source src="{{ $item->file_url }}" type="video/mp4">
                                        </video>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $this->media->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-filament::icon icon="heroicon-o-folder-open" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                        <p class="text-gray-500 dark:text-gray-400">No media found</p>
                        <p class="text-sm text-gray-400 mt-1">Generate some images, music, or videos to see them here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
