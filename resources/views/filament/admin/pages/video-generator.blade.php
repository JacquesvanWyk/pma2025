<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Input Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon icon="heroicon-o-video-camera" class="w-5 h-5 mr-2 text-primary-600" />
                        Lyric Video Settings
                    </h2>

                    {{ $this->form }}

                    <!-- Lyrics Summary -->
                    @if (count($this->lyricLines) > 0)
                        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 inline mr-1" />
                                    {{ count($this->lyricLines) }} Lyric Lines Ready
                                </h3>
                                <button
                                    wire:click="$set('showLyricsEditor', true)"
                                    class="text-xs text-green-600 hover:text-green-800 dark:text-green-400"
                                >
                                    Edit Timestamps
                                </button>
                            </div>
                            <p class="text-xs text-green-600 dark:text-green-300">
                                Duration: {{ $this->audioDurationMs ? gmdate('i:s', $this->audioDurationMs / 1000) : 'Unknown' }}
                            </p>
                        </div>
                    @elseif ($this->audioDurationMs)
                        <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-4 h-4 inline mr-1" />
                                No Lyrics Added
                            </h3>
                            <p class="text-xs text-yellow-600 dark:text-yellow-300 mt-1">
                                Click "Edit Lyrics & Timestamps" to add your song lyrics.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview / Status Area -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-full">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon icon="heroicon-o-play" class="w-5 h-5 mr-2 text-primary-600" />
                        Preview
                    </h2>

                    @if ($this->isGenerating)
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Generating your video...</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">This may take several minutes</p>
                            <button
                                wire:click="checkStatus"
                                wire:loading.attr="disabled"
                                class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4 mr-2" />
                                Check Status
                            </button>
                        </div>
                    @elseif ($this->generationError)
                        <div class="flex flex-col items-center justify-center py-12 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="w-8 h-8 text-red-500 mb-2" />
                            <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">Generation Failed</p>
                            <p class="text-xs text-red-600 dark:text-red-300 text-center px-4">{{ $this->generationError }}</p>
                        </div>
                    @elseif ($this->currentAudioUrl)
                        <div class="space-y-4">
                            <!-- Audio Player -->
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <p class="text-xs text-gray-500 mb-2">Audio Preview</p>
                                <audio controls class="w-full">
                                    <source src="{{ $this->currentAudioUrl }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>

                            <!-- Video Preview Placeholder -->
                            <div class="aspect-video bg-black rounded-lg flex items-center justify-center relative overflow-hidden">
                                @if (($this->data['background_type'] ?? 'color') === 'color')
                                    <div class="absolute inset-0" style="background-color: {{ $this->data['background_color'] ?? '#000000' }}"></div>
                                @endif
                                <div class="relative z-10 text-center p-8">
                                    <p
                                        class="font-bold"
                                        style="
                                            font-size: {{ ($this->data['font_size'] ?? 48) / 2 }}px;
                                            color: {{ $this->data['font_color'] ?? '#FFFFFF' }};
                                        "
                                    >
                                        {{ count($this->lyricLines) > 0 ? ($this->lyricLines[0]['text'] ?? 'Your lyrics here...') : 'Your lyrics here...' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <x-filament::icon icon="heroicon-o-video-camera" class="w-12 h-12 text-gray-400 mb-3" />
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">No video created yet</p>
                            <p class="text-xs text-gray-500 text-center px-4">
                                1. Select audio source<br>
                                2. Add lyrics with timestamps<br>
                                3. Generate video
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        @if ($this->recentProjects->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <x-filament::icon icon="heroicon-o-folder" class="w-5 h-5 mr-2 text-primary-600" />
                    Your Video Projects
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($this->recentProjects as $project)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <!-- Thumbnail / Preview -->
                            @if ($project->thumbnail_url)
                                <img src="{{ $project->thumbnail_url }}" alt="{{ $project->name }}" class="w-full h-32 object-cover" />
                            @else
                                <div class="w-full h-32 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <x-filament::icon icon="heroicon-o-video-camera" class="w-12 h-12 text-white/50" />
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="font-medium text-sm text-gray-900 dark:text-white truncate mb-1">
                                    {{ $project->name }}
                                </h3>

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span>{{ $project->created_at->diffForHumans() }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($project->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($project->status === 'processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($project->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                        @endif
                                    ">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    @if ($project->status === 'completed' && $project->output_url)
                                        <a
                                            href="{{ $project->output_url }}"
                                            download
                                            class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-white bg-primary-600 rounded hover:bg-primary-700"
                                        >
                                            <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-3 h-3 mr-1" />
                                            Download
                                        </a>
                                    @endif

                                    <button
                                        wire:click="loadProject({{ $project->id }})"
                                        class="flex-1 inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600"
                                    >
                                        <x-filament::icon icon="heroicon-o-pencil" class="w-3 h-3 mr-1" />
                                        Edit
                                    </button>

                                    <button
                                        wire:click="deleteProject({{ $project->id }})"
                                        wire:confirm="Are you sure you want to delete this project?"
                                        class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400"
                                    >
                                        <x-filament::icon icon="heroicon-o-trash" class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
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
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Tips for Better Lyric Videos</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Music Library:</strong> Use songs from your Music Generator for best results</li>
                            <li><strong>Timestamps:</strong> Auto-generated timestamps can be manually adjusted</li>
                            <li><strong>Background:</strong> Dark backgrounds work best for readable text</li>
                            <li><strong>Processing:</strong> Longer songs take more time to process</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lyrics Editor Modal -->
    <div
        x-data="{ open: @entangle('showLyricsEditor') }"
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
                class="relative inline-block w-full max-w-4xl p-6 my-8 text-left align-middle bg-white dark:bg-gray-800 rounded-xl shadow-xl transform transition-all"
            >
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <x-filament::icon icon="heroicon-o-pencil-square" class="w-5 h-5 inline mr-2 text-primary-500" />
                    Lyrics & Timestamp Editor
                </h3>

                <!-- Audio Player for Reference -->
                @if ($this->currentAudioUrl)
                    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Listen while editing:</p>
                        <audio controls class="w-full">
                            <source src="{{ $this->currentAudioUrl }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif

                <!-- Raw Lyrics Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Paste or Type Lyrics
                    </label>
                    <div class="flex gap-2">
                        <textarea
                            wire:model="rawLyrics"
                            rows="4"
                            placeholder="[Verse 1]
Amazing grace, how sweet the sound
That saved a wretch like me

[Chorus]
I once was lost, but now am found..."
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                        ></textarea>
                    </div>
                    <div class="mt-2">
                        <button
                            wire:click="autoGenerateTimestamps"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-warning-600 rounded-md hover:bg-warning-700"
                        >
                            <x-filament::icon icon="heroicon-o-sparkles" class="w-3 h-3 mr-1" />
                            Auto-Generate Timestamps
                        </button>
                    </div>
                </div>

                <!-- Timeline Editor -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Lyric Timeline ({{ count($this->lyricLines) }} lines)
                        </label>
                        <button
                            wire:click="addLyricLine"
                            class="text-xs text-primary-600 hover:text-primary-800"
                        >
                            + Add Line
                        </button>
                    </div>

                    <div class="max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg">
                        @forelse ($this->lyricLines as $index => $line)
                            <div class="flex items-center gap-2 p-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <span class="text-xs text-gray-400 w-6">{{ $index + 1 }}</span>

                                <input
                                    type="text"
                                    value="{{ $line['text'] }}"
                                    wire:change="updateLyricLine({{ $index }}, 'text', $event.target.value)"
                                    class="flex-1 text-sm rounded border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1 px-2"
                                    placeholder="Lyric text..."
                                />

                                <div class="flex items-center gap-1">
                                    <input
                                        type="number"
                                        value="{{ $line['start_ms'] }}"
                                        wire:change="updateLyricLine({{ $index }}, 'start_ms', $event.target.value)"
                                        class="w-20 text-xs rounded border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1 px-2 text-center"
                                        placeholder="Start (ms)"
                                        min="0"
                                    />
                                    <span class="text-gray-400">-</span>
                                    <input
                                        type="number"
                                        value="{{ $line['end_ms'] }}"
                                        wire:change="updateLyricLine({{ $index }}, 'end_ms', $event.target.value)"
                                        class="w-20 text-xs rounded border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1 px-2 text-center"
                                        placeholder="End (ms)"
                                        min="0"
                                    />
                                </div>

                                <button
                                    wire:click="removeLyricLine({{ $index }})"
                                    class="text-red-500 hover:text-red-700 p-1"
                                >
                                    <x-filament::icon icon="heroicon-o-x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <p class="text-sm">No lyrics added yet.</p>
                                <p class="text-xs mt-1">Paste lyrics above and click "Auto-Generate Timestamps"</p>
                            </div>
                        @endforelse
                    </div>
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
</x-filament-panels::page>
