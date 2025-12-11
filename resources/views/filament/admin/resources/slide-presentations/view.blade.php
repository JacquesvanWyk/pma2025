<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Presentation Info --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->title }}</h2>
                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $record->total_slides }} slides</span>
                        <span>&bull;</span>
                        <span>Created {{ $record->created_at->format('M j, Y') }}</span>
                        <span>&bull;</span>
                        <x-filament::badge :color="match($record->status) {
                            'complete' => 'success',
                            'generating' => 'warning',
                            'outline_ready' => 'info',
                            'failed' => 'danger',
                            default => 'gray',
                        }">
                            {{ ucfirst($record->status) }}
                        </x-filament::badge>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slides Grid --}}
        @if($record->slides && count($record->slides) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($record->slides as $index => $slide)
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden group">
                        {{-- Slide Image --}}
                        <div class="relative aspect-video bg-gray-100 dark:bg-gray-800">
                            @if($slide['image_path'] ?? null)
                                <img
                                    src="{{ asset('storage/' . $slide['image_path']) }}"
                                    alt="Slide {{ $slide['number'] ?? $index + 1 }}"
                                    class="w-full h-full object-cover"
                                >
                                {{-- Overlay with actions --}}
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <x-filament::button
                                        size="sm"
                                        icon="heroicon-o-arrow-down-tray"
                                        wire:click="downloadSlide({{ $index }}, false)"
                                    >
                                        Download
                                    </x-filament::button>
                                    <x-filament::button
                                        size="sm"
                                        color="info"
                                        icon="heroicon-o-photo"
                                        wire:click="downloadSlide({{ $index }}, true)"
                                    >
                                        + Logo
                                    </x-filament::button>
                                    <x-filament::button
                                        size="sm"
                                        color="warning"
                                        icon="heroicon-o-arrow-path"
                                        wire:click="regenerateSlide({{ $index }})"
                                        wire:loading.attr="disabled"
                                    >
                                        Regenerate
                                    </x-filament::button>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($slide['error'] ?? null)
                                        <div class="text-center p-4">
                                            <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-red-500 mx-auto mb-2" />
                                            <p class="text-sm text-red-500">{{ $slide['error'] }}</p>
                                            <x-filament::button
                                                size="sm"
                                                color="warning"
                                                icon="heroicon-o-arrow-path"
                                                wire:click="regenerateSlide({{ $index }})"
                                                class="mt-2"
                                            >
                                                Retry
                                            </x-filament::button>
                                        </div>
                                    @else
                                        <x-heroicon-o-photo class="w-12 h-12 text-gray-400" />
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Slide Info --}}
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Slide {{ $slide['number'] ?? $index + 1 }}
                                </span>
                                @if($slide['archetype'] ?? $slide['type'] ?? null)
                                    <x-filament::badge size="sm" color="gray">
                                        {{ ucfirst(str_replace('-', ' ', $slide['archetype'] ?? $slide['type'])) }}
                                    </x-filament::badge>
                                @endif
                            </div>
                            <h3 class="mt-1 font-semibold text-gray-900 dark:text-white truncate">
                                {{ $slide['title'] ?? 'Untitled' }}
                            </h3>
                            @if($slide['summary'] ?? null)
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                    {{ $slide['summary'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <x-heroicon-o-rectangle-stack class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Slides Yet</h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    This presentation doesn't have any slides generated yet.
                </p>
                <x-filament::button
                    class="mt-4"
                    :href="route('filament.admin.pages.slide-generator', ['presentation' => $record->id])"
                    tag="a"
                >
                    Continue in Generator
                </x-filament::button>
            </div>
        @endif

        {{-- Outline Section --}}
        @if($record->outline)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Original Outline</h3>
                <div class="space-y-3">
                    @foreach(($record->outline['slides'] ?? []) as $outlineSlide)
                        <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <span class="flex-shrink-0 w-8 h-8 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-sm font-medium">
                                {{ $outlineSlide['number'] ?? $loop->iteration }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $outlineSlide['title'] ?? 'Untitled' }}</p>
                                @if($outlineSlide['summary'] ?? null)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $outlineSlide['summary'] }}</p>
                                @endif
                            </div>
                            @if($outlineSlide['archetype'] ?? $outlineSlide['type'] ?? null)
                                <x-filament::badge size="sm" color="gray">
                                    {{ ucfirst(str_replace('-', ' ', $outlineSlide['archetype'] ?? $outlineSlide['type'])) }}
                                </x-filament::badge>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
