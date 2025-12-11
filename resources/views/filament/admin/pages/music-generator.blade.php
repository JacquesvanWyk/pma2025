<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Credits Display -->
        @if ($this->credits !== null)
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">KIE.ai Credits Balance</p>
                        <p class="text-2xl font-bold">{{ number_format($this->credits) }} credits</p>
                        <p class="text-sm opacity-75">${{ number_format($this->credits * 0.005, 2) }} USD value</p>
                    </div>
                    <x-filament::icon icon="heroicon-o-currency-dollar" class="w-12 h-12 opacity-50" />
                </div>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Input Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon icon="heroicon-o-musical-note" class="w-5 h-5 mr-2 text-primary-600" />
                        Music Generation Settings
                    </h2>

                    {{ $this->form }}

                    <!-- Lyrics Preview -->
                    @if ($this->editedLyrics)
                        <div class="mt-4 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-200">Lyrics Ready</h3>
                                <button
                                    wire:click="$set('showLyricsModal', true)"
                                    class="text-xs text-purple-600 hover:text-purple-800 dark:text-purple-400"
                                >
                                    Edit
                                </button>
                            </div>
                            <p class="text-xs text-purple-600 dark:text-purple-300 line-clamp-3 whitespace-pre-line">{{ Str::limit($this->editedLyrics, 200) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview / Player Area -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-full">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon icon="heroicon-o-play" class="w-5 h-5 mr-2 text-primary-600" />
                        Generated Music
                    </h2>

                    @if ($this->isGenerating || $this->taskStatus === 'PENDING')
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Generating your music...</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">This may take 30-120 seconds</p>
                            @if ($this->taskId)
                                <p class="text-xs text-gray-400 mt-2">Task: {{ $this->taskId }}</p>
                            @endif
                            <button
                                wire:click="checkStatus"
                                wire:loading.attr="disabled"
                                class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4 mr-2" />
                                Check Status
                            </button>
                        </div>
                    @elseif ($this->generatedTracks && count($this->generatedTracks) > 0)
                        <div class="space-y-4">
                            @foreach ($this->generatedTracks as $index => $track)
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                    @if (!empty($track['image_url']))
                                        <img
                                            src="{{ $track['image_url'] }}"
                                            alt="{{ $track['title'] ?? 'Generated Music' }}"
                                            class="w-full h-32 object-cover rounded-lg mb-3"
                                        />
                                    @endif

                                    @if (!empty($track['title']))
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $track['title'] }}</h3>
                                    @endif

                                    @if (!empty($track['duration']))
                                        <p class="text-xs text-gray-500 mb-2">
                                            Duration: {{ gmdate('i:s', (int)$track['duration']) }}
                                        </p>
                                    @endif

                                    @if (!empty($track['audio_url']))
                                        <audio controls class="w-full mb-3">
                                            <source src="{{ $track['audio_url'] }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>

                                        <a
                                            href="{{ $track['audio_url'] }}"
                                            download="music-{{ now()->format('Y-m-d-H-i') }}.mp3"
                                            target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700"
                                        >
                                            <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-3 h-3 mr-1" />
                                            Download
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @elseif ($this->generationError)
                        <div class="flex flex-col items-center justify-center py-12 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="w-8 h-8 text-red-500 mb-2" />
                            <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">Generation Failed</p>
                            <p class="text-xs text-red-600 dark:text-red-300 text-center px-4">{{ $this->generationError }}</p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <x-filament::icon icon="heroicon-o-musical-note" class="w-12 h-12 text-gray-400 mb-3" />
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">No music generated yet</p>
                            <p class="text-xs text-gray-500 text-center px-4">Generate lyrics first, then create your music</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Music Library -->
        @if ($this->recentMusic->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <x-filament::icon icon="heroicon-o-folder" class="w-5 h-5 mr-2 text-primary-600" />
                    Your Music Library
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($this->recentMusic as $media)
                        <div
                            wire:click="playTrack({{ $media->id }})"
                            class="cursor-pointer bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-colors"
                        >
                            @php
                                $imageUrl = $media->metadata['tracks'][0]['image_url'] ?? null;
                            @endphp

                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $media->title }}" class="w-full h-24 object-cover rounded mb-2" />
                            @else
                                <div class="w-full h-24 bg-gradient-to-br from-purple-500 to-indigo-600 rounded mb-2 flex items-center justify-center">
                                    <x-filament::icon icon="heroicon-o-musical-note" class="w-8 h-8 text-white" />
                                </div>
                            @endif

                            <h3 class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                {{ $media->title ?? 'Untitled' }}
                            </h3>
                            <p class="text-xs text-gray-500">{{ $media->created_at->diffForHumans() }}</p>

                            @if ($media->formatted_duration)
                                <p class="text-xs text-gray-400 mt-1">{{ $media->formatted_duration }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tips -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <x-filament::icon icon="heroicon-o-light-bulb" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Tips for Better Results</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Generate Lyrics First:</strong> Use AI to create lyrics, then edit them</li>
                            <li><strong>Save Style Presets:</strong> Create presets for worship, hymns, contemporary, etc.</li>
                            <li><strong>Be Descriptive:</strong> Include mood, tempo, and instrument preferences</li>
                            <li><strong>Cost:</strong> ~12 credits ($0.06) per song</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lyrics Modal -->
    <div
        x-data="{ open: @entangle('showLyricsModal') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="open = false"
            ></div>

            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle bg-white dark:bg-gray-800 rounded-xl shadow-xl transform transition-all"
            >
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <x-filament::icon icon="heroicon-o-sparkles" class="w-5 h-5 inline mr-2 text-warning-500" />
                    Lyrics Editor
                </h3>

                <!-- Theme Input for AI Generation -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Theme / Topic for AI Generation
                    </label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="lyricsTheme"
                            placeholder="e.g., God's faithfulness, grace, redemption..."
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        />
                        <button
                            wire:click="generateLyricsWithAi"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-warning-600 rounded-md hover:bg-warning-700 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="generateLyricsWithAi">
                                <x-filament::icon icon="heroicon-o-sparkles" class="w-4 h-4 mr-1" />
                                Generate
                            </span>
                            <span wire:loading wire:target="generateLyricsWithAi">
                                <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4 mr-1 animate-spin" />
                                Generating...
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Lyrics Editor -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lyrics (edit as needed)
                    </label>
                    <textarea
                        wire:model="editedLyrics"
                        rows="12"
                        placeholder="[Verse 1]
Your lyrics here...

[Chorus]
Your chorus here...

[Verse 2]
More lyrics...

[Bridge]
Bridge section..."
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Use [Verse], [Chorus], [Bridge] tags to structure your song</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="saveLyrics"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700"
                    >
                        Save Lyrics
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Style Preset Modal -->
    <div
        x-data="{ open: @entangle('showStyleModal') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="open = false"
            ></div>

            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative inline-block w-full max-w-lg p-6 my-8 text-left align-middle bg-white dark:bg-gray-800 rounded-xl shadow-xl transform transition-all"
            >
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <x-filament::icon icon="heroicon-o-bookmark" class="w-5 h-5 inline mr-2 text-primary-500" />
                    Create Style Preset
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                        <input
                            type="text"
                            wire:model="newStyleName"
                            placeholder="e.g., Modern Worship"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Style Description *</label>
                        <textarea
                            wire:model="newStyleDescription"
                            rows="2"
                            placeholder="e.g., Contemporary Christian, uplifting, inspirational"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Genre</label>
                            <input
                                type="text"
                                wire:model="newStyleGenre"
                                placeholder="e.g., Gospel, Hymn"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mood</label>
                            <input
                                type="text"
                                wire:model="newStyleMood"
                                placeholder="e.g., Peaceful, Joyful"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instruments</label>
                            <input
                                type="text"
                                wire:model="newStyleInstruments"
                                placeholder="e.g., Piano, Guitar"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempo</label>
                            <input
                                type="text"
                                wire:model="newStyleTempo"
                                placeholder="e.g., Slow, Medium"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="saveStylePreset"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700"
                    >
                        Save Preset
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
