<x-filament-panels::page>
    <div class="flex gap-6 h-[calc(100vh-200px)]" x-data="{
        dragging: false,
        draggedSlideId: null,
        dragOverSlideId: null,
    }">
        <!-- Left Sidebar: Slide Thumbnails (1/4) -->
        <div class="w-1/4 flex flex-col gap-4 overflow-y-auto pr-2">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Slides ({{ $sermon->slides->count() }})
            </div>

            @forelse($sermon->slides as $slide)
                <div
                    wire:key="slide-{{ $slide->id }}"
                    class="relative group cursor-pointer rounded-lg border-2 transition-all {{ $activeSlideId === $slide->id ? 'border-primary-500 shadow-lg' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300' }}"
                    wire:click="selectSlide({{ $slide->id }})"
                    draggable="true"
                    @dragstart="dragging = true; draggedSlideId = {{ $slide->id }}"
                    @dragend="dragging = false; draggedSlideId = null; dragOverSlideId = null"
                    @dragover.prevent="dragOverSlideId = {{ $slide->id }}"
                    @drop.prevent="
                        if (draggedSlideId !== {{ $slide->id }}) {
                            $wire.reorderSlides([draggedSlideId, {{ $slide->id }}]);
                        }
                    "
                >
                    <!-- Slide Number Badge -->
                    <div class="absolute top-2 left-2 z-10 bg-gray-900 bg-opacity-75 text-white text-xs font-semibold px-2 py-1 rounded">
                        {{ $slide->slide_number }}
                    </div>

                    <!-- Slide Preview -->
                    <div class="aspect-video bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center text-xs p-2 relative"
                            style="transform: scale(0.15); transform-origin: top left; width: 666.67%; height: 666.67%;">
                            {!! $slide->rendered_html !!}
                        </div>
                    </div>

                    <!-- Slide Actions (Show on hover) -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                        <button
                            wire:click.stop="moveSlideUp({{ $slide->id }})"
                            class="p-2 bg-white dark:bg-gray-700 rounded-full shadow hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                            title="Move Up"
                        >
                            <x-filament::icon icon="heroicon-o-arrow-up" class="w-4 h-4" />
                        </button>

                        <button
                            wire:click.stop="moveSlideDown({{ $slide->id }})"
                            class="p-2 bg-white dark:bg-gray-700 rounded-full shadow hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                            title="Move Down"
                        >
                            <x-filament::icon icon="heroicon-o-arrow-down" class="w-4 h-4" />
                        </button>

                        <button
                            wire:click.stop="duplicateSlide({{ $slide->id }})"
                            class="p-2 bg-white dark:bg-gray-700 rounded-full shadow hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                            title="Duplicate"
                        >
                            <x-filament::icon icon="heroicon-o-document-duplicate" class="w-4 h-4" />
                        </button>

                        <button
                            wire:click.stop="deleteSlide({{ $slide->id }})"
                            wire:confirm="Are you sure you want to delete this slide?"
                            class="p-2 bg-red-500 text-white rounded-full shadow hover:bg-red-600 transition"
                            title="Delete"
                        >
                            <x-filament::icon icon="heroicon-o-trash" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    No slides yet. Click "Add Slide" to get started.
                </div>
            @endforelse
        </div>

        <!-- Right Side: Active Slide Preview & AI Editor (3/4) -->
        <div class="flex-1 flex flex-col gap-6">
            @if($activeSlideId && $sermon->slides->where('id', $activeSlideId)->first())
                @php
                    $activeSlide = $sermon->slides->where('id', $activeSlideId)->first();
                @endphp

                <!-- Slide Preview -->
                <x-filament::section class="flex-1">
                    <x-slot name="heading">
                        Slide {{ $activeSlide->slide_number }}: {{ ucfirst($activeSlide->slide_type) }}
                    </x-slot>

                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-2xl">
                        <div class="aspect-video flex items-center justify-center">
                            <div class="w-full h-full flex items-center justify-center relative"
                                style="transform: scale(0.5); transform-origin: center; width: 200%; height: 200%;">
                                {!! $activeSlide->rendered_html !!}
                            </div>
                        </div>
                    </div>
                </x-filament::section>

                <!-- AI Prompt Editor -->
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <x-filament::icon icon="heroicon-o-sparkles" class="w-5 h-5 text-primary-500" />
                            AI Slide Editor
                        </div>
                    </x-slot>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">
                                Describe your changes
                            </label>
                            <textarea
                                wire:model="aiPrompt"
                                rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary-500 focus:ring-primary-500"
                                placeholder="e.g., 'Make the title larger', 'Change background to blue', 'Add bullet points about faith', 'Make slide 3 a scripture slide with John 3:16'"
                                {{ $isProcessingAi ? 'disabled' : '' }}
                            ></textarea>
                        </div>

                        <div class="flex gap-2">
                            <x-filament::button
                                wire:click="processAiEdit"
                                color="primary"
                                icon="heroicon-o-sparkles"
                                :disabled="$isProcessingAi || !$aiPrompt"
                            >
                                {{ $isProcessingAi ? 'Processing...' : 'Apply AI Edit' }}
                            </x-filament::button>

                            @if($isProcessingAi)
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <x-filament::loading-indicator class="w-4 h-4" />
                                    <span>Processing your request...</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <p><strong>Examples:</strong></p>
                            <ul class="list-disc list-inside space-y-1 ml-2">
                                <li>Change the background to a warm gradient</li>
                                <li>Make the title text larger and bold</li>
                                <li>Add 3 bullet points about the sermon's main themes</li>
                                <li>Convert this to a scripture slide with Romans 8:28</li>
                                <li>Add a call to action at the bottom</li>
                            </ul>
                        </div>
                    </div>
                </x-filament::section>

                <!-- Slide Metadata -->
                <x-filament::section collapsible collapsed>
                    <x-slot name="heading">
                        Slide Details
                    </x-slot>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Type:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ ucfirst($activeSlide->slide_type) }}</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Background:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ ucfirst($activeSlide->background_type) }}</span>
                        </div>

                        @if($activeSlide->metadata && isset($activeSlide->metadata['theme']))
                            <div>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Theme:</span>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $activeSlide->metadata['theme'] }}</span>
                            </div>
                        @endif

                        @if($activeSlide->ai_prompt_history && count($activeSlide->ai_prompt_history) > 0)
                            <div>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Edit History:</span>
                                <ul class="ml-6 mt-2 space-y-1 list-disc text-gray-600 dark:text-gray-400">
                                    @foreach($activeSlide->ai_prompt_history as $history)
                                        <li>
                                            <span class="text-xs">{{ $history['timestamp'] ?? '' }}</span>
                                            - {{ $history['prompt'] ?? $history['action'] ?? 'Unknown action' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </x-filament::section>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400">
                    Select a slide to preview and edit
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
