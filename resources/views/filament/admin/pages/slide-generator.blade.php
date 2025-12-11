<x-filament-panels::page>
    <div class="flex gap-6 min-h-[calc(100vh-200px)]" @if($step === 'generating') wire:poll.3s="checkProgress" @endif>

        <!-- Left Sidebar -->
        <div class="w-1/3 space-y-6">
            @if($step === 'input')
                <!-- Input Form -->
                <x-filament::section>
                    <x-slot name="heading">
                        Create Presentation
                    </x-slot>
                    <x-slot name="description">
                        Enter your content and the AI will create illustrated slides with text baked into the images.
                    </x-slot>

                    {{ $this->form }}
                </x-filament::section>

            @elseif($step === 'outline')
                <!-- Outline Actions -->
                <x-filament::section>
                    <x-slot name="heading">
                        Review Outline
                    </x-slot>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Review the slide outline. Each slide will be generated as a complete image with text baked in.
                        </p>

                        <div class="flex flex-col gap-2">
                            <button
                                wire:click="approveAndGenerate"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <x-filament::icon icon="heroicon-o-sparkles" class="w-5 h-5" />
                                Generate Slides ({{ count($outline['slides'] ?? []) }} images)
                            </button>

                            <button
                                wire:click="backToInput"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4" />
                                Edit Content
                            </button>
                        </div>
                    </div>
                </x-filament::section>

            @elseif($step === 'generating')
                <!-- Generation Progress -->
                <x-filament::section>
                    <x-slot name="heading">
                        Generating Images
                    </x-slot>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <x-filament::loading-indicator class="w-5 h-5" />
                            <span class="font-medium text-gray-900 dark:text-white">Creating your slides...</span>
                        </div>

                        @if($currentStatus)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $currentStatus }}
                            </p>
                        @endif

                        @if($presentation && $presentation->total_slides > 0)
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $presentation->current_slide }} / {{ $presentation->total_slides }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div
                                        class="bg-primary-600 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $presentation->getProgressPercentage() }}%"
                                    ></div>
                                </div>
                            </div>
                        @endif

                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            Each slide takes ~30-60 seconds to generate. Please wait...
                        </p>
                    </div>
                </x-filament::section>

            @elseif($step === 'complete')
                <!-- Complete Actions -->
                <x-filament::section>
                    <x-slot name="heading">
                        Presentation Ready!
                    </x-slot>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-green-600 dark:text-green-400">
                            <x-filament::icon icon="heroicon-o-check-circle" class="w-6 h-6" />
                            <span class="font-medium">{{ count($presentation->slides ?? []) }} slides generated</span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button
                                wire:click="startNew"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                                <x-filament::icon icon="heroicon-o-plus" class="w-4 h-4" />
                                Create New Presentation
                            </button>
                        </div>
                    </div>
                </x-filament::section>
            @endif

            <!-- Loading Indicator -->
            @if($isGeneratingOutline)
                <x-filament::section>
                    <div class="flex items-center gap-3">
                        <x-filament::loading-indicator class="w-5 h-5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $currentStatus }}</span>
                    </div>
                </x-filament::section>
            @endif
        </div>

        <!-- Right Preview Area -->
        <div class="w-2/3 overflow-y-auto">
            @if($step === 'input')
                <!-- Empty State -->
                <x-filament::section>
                    <div class="text-center py-16">
                        <x-filament::icon
                            icon="heroicon-o-presentation-chart-bar"
                            class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6"
                        />
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                            Kimi-Style Slides
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                            Create beautiful presentation slides with illustrations and text baked directly into the images.
                        </p>
                        <div class="flex justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-2">
                                <x-filament::icon icon="heroicon-o-sparkles" class="w-4 h-4" />
                                AI-Generated Illustrations
                            </span>
                            <span class="flex items-center gap-2">
                                <x-filament::icon icon="heroicon-o-photo" class="w-4 h-4" />
                                Pure Image Output
                            </span>
                        </div>
                    </div>
                </x-filament::section>

            @elseif($step === 'outline' && $outline)
                <!-- Outline Preview -->
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between">
                            <span>Slide Outline: {{ $outline['title'] ?? 'Untitled' }}</span>
                            <span class="text-sm font-normal text-gray-500">
                                {{ count($outline['slides'] ?? []) }} slides
                            </span>
                        </div>
                    </x-slot>

                    <div class="space-y-4">
                        @foreach($outline['slides'] ?? [] as $index => $slide)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center shrink-0">
                                        <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                            {{ $slide['number'] ?? ($index + 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $slide['title'] ?? 'Untitled Slide' }}
                                            </h4>
                                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                                {{ $slide['type'] ?? 'content' }}
                                            </span>
                                        </div>
                                        @if(isset($slide['summary']))
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $slide['summary'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>

            @elseif($step === 'generating')
                <!-- Generation Animation -->
                <x-filament::section>
                    <div class="text-center py-12">
                        <div class="relative w-32 h-32 mx-auto mb-6">
                            <div class="absolute inset-0 border-4 border-primary-200 dark:border-primary-800 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-primary-600 border-t-transparent rounded-full animate-spin"></div>
                            <div class="absolute inset-4 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                                <x-filament::icon icon="heroicon-o-photo" class="w-10 h-10 text-primary-600 dark:text-primary-400" />
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Creating Your Slides
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            AI is generating illustrated images for each slide...
                        </p>

                        @if($presentation && $presentation->slides)
                            <!-- Show completed slides as they come in -->
                            <div class="grid grid-cols-3 gap-4 mt-8">
                                @foreach($presentation->slides as $index => $slide)
                                    @if($slide['image_path'])
                                        <div class="aspect-video rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-sm">
                                            <img
                                                src="{{ asset('storage/' . $slide['image_path']) }}"
                                                alt="Slide {{ $index + 1 }}"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-filament::section>

            @elseif($step === 'complete' && $presentation && $presentation->slides)
                <!-- Final Slides Gallery -->
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between">
                            <span>{{ $presentation->title }}</span>
                            <span class="text-sm font-normal text-gray-500">
                                {{ count($presentation->slides) }} slides
                            </span>
                        </div>
                    </x-slot>

                    <div class="space-y-6">
                        @foreach($presentation->slides as $index => $slide)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <!-- Slide Header -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded flex items-center justify-center">
                                            <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                                {{ $slide['number'] ?? ($index + 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $slide['title'] ?? 'Slide ' . ($index + 1) }}
                                            </h4>
                                            <span class="text-xs text-gray-500">{{ $slide['type'] ?? 'content' }}</span>
                                        </div>
                                    </div>

                                    @if($slide['image_path'])
                                        <a
                                            href="{{ asset('storage/' . $slide['image_path']) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-4 h-4" />
                                            Download
                                        </a>
                                    @endif
                                </div>

                                <!-- Slide Image -->
                                <div class="relative bg-gray-100 dark:bg-gray-950" style="aspect-ratio: 16/9;">
                                    @if($slide['image_path'])
                                        <img
                                            src="{{ asset('storage/' . $slide['image_path']) }}"
                                            alt="{{ $slide['title'] ?? 'Slide ' . ($index + 1) }}"
                                            class="w-full h-full object-contain"
                                        />
                                    @elseif(isset($slide['error']))
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center">
                                                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-12 h-12 mx-auto text-red-400 mb-2" />
                                                <p class="text-sm text-red-600 dark:text-red-400">{{ $slide['error'] }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <x-filament::loading-indicator class="w-8 h-8" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>
