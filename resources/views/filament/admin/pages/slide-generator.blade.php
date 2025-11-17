@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-filament-panels::page>
    <div class="flex gap-6 h-[calc(100vh-200px)]" wire:poll.2s="checkGenerationProgress">

        <!-- Left Sidebar: Form or AI Prompt -->
        <div class="w-1/3 overflow-y-auto border-r border-gray-200 dark:border-gray-700 pr-6">
            @if($isGenerating || count($generatedSlides) > 0)
                <!-- AI Edit Prompt -->
                <x-filament::section>
                    <x-slot name="heading">
                        AI Slide Editor
                    </x-slot>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Ask AI to edit your slides. For example: "Make slide 3 background red" or "Change the title on slide 1 to 'Welcome'"
                        </p>

                        <textarea
                            wire:model="aiPrompt"
                            placeholder="Describe what you'd like to change..."
                            rows="4"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            {{ $isGenerating ? 'disabled' : '' }}
                        ></textarea>

                        <div class="flex gap-2">
                            <button
                                wire:click="applyAiEdit"
                                {{ $isGenerating ? 'disabled' : '' }}
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <x-filament::icon icon="heroicon-o-sparkles" class="w-4 h-4" />
                                Apply Changes
                            </button>

                            <button
                                wire:click="resetToForm"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </x-filament::section>
            @else
                <!-- Initial Configuration Form -->
                <x-filament::section>
                    <x-slot name="heading">
                        Slide Configuration
                    </x-slot>

                    {{ $this->form }}
                </x-filament::section>
            @endif

            <!-- Progress Indicator -->
            @if($isGenerating || $currentStatus)
                <x-filament::section class="mt-6">
                    <div class="space-y-3">
                        @if($isGenerating)
                            <div class="flex items-center gap-3">
                                <x-filament::loading-indicator class="w-5 h-5" />
                                <span class="font-medium text-gray-900 dark:text-white">Generating Slides...</span>
                            </div>
                        @endif

                        @if($currentStatus)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $currentStatus }}
                            </p>
                        @endif

                        @if($totalSlides > 0)
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $currentSlideNumber }} / {{ $totalSlides }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div
                                        class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ $totalSlides > 0 ? ($currentSlideNumber / $totalSlides * 100) : 0 }}%"
                                    ></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-filament::section>
            @endif
        </div>

        <!-- Right Preview Area: Generated Slides -->
        <div class="w-2/3 overflow-y-auto">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span>Preview</span>
                            @if(count($generatedSlides) > 0)
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                    {{ count($generatedSlides) }} {{ Str::plural('slide', count($generatedSlides)) }} generated
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if(count($generatedSlides) > 0 && !$isGenerating)
                                <!-- Export Buttons -->
                                <button wire:click="exportAsPowerPoint"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    <x-filament::icon icon="heroicon-o-document" class="w-4 h-4" />
                                    Export PowerPoint
                                </button>

                                <button wire:click="exportAsPdf"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4" />
                                    Export PDF
                                </button>
                            @endif
                        </div>
                    </div>
                </x-slot>

                <div class="space-y-6" wire:key="slides-container-{{ count($generatedSlides) }}">
                    @if(count($generatedSlides) === 0 && $isGenerating)
                        <!-- Robot Animation While Generating -->
                        <div class="relative overflow-hidden bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-8 flex flex-col items-center justify-center" style="min-height: 400px;"
                            wire:key="robot-animation"
                            x-data="{
                                animation: null,
                                initAnimation() {
                                    if (typeof window.lottie !== 'undefined') {
                                        const container = this.$refs.lottieContainer;
                                        if (container && !this.animation) {
                                            container.innerHTML = '';
                                            this.animation = window.lottie.loadAnimation({
                                                container: container,
                                                renderer: 'svg',
                                                loop: true,
                                                autoplay: true,
                                                path: '/animations/robot-ai.json',
                                                rendererSettings: {
                                                    preserveAspectRatio: 'xMidYMid meet'
                                                }
                                            });

                                            this.animation.addEventListener('DOMLoaded', () => {
                                                const svg = container.querySelector('svg');
                                                if (svg) {
                                                    svg.setAttribute('width', '100%');
                                                    svg.setAttribute('height', '100%');
                                                }
                                            });
                                        }
                                    }
                                }
                            }"
                            x-init="$nextTick(() => initAnimation())">

                            <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-primary-600 to-primary-400 dark:from-primary-400 dark:to-primary-200 bg-clip-text text-transparent mb-8">
                                AI is crafting your slides...
                            </h3>

                            <div class="w-48 h-48 overflow-hidden flex items-center justify-center">
                                <div x-ref="lottieContainer" class="w-full h-full" wire:ignore></div>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-6">
                                Your slides will appear below as they are generated
                            </p>
                        </div>
                    @elseif(count($generatedSlides) === 0)
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <x-filament::icon
                                icon="heroicon-o-presentation-chart-bar"
                                class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4"
                            />
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                No slides yet
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Click "Generate Slides" to create your presentation
                            </p>
                        </div>
                    @else
                        @foreach($generatedSlides as $index => $slide)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden group">
                                <!-- Slide Header -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded flex items-center justify-center">
                                            <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                                {{ $slide['slide_number'] ?? ($index + 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $slide['type'] ?? 'Slide' }}
                                            </h4>
                                        </div>
                                    </div>

                                    @if($isGenerating && $index === count($generatedSlides) - 1)
                                        <div class="flex items-center gap-2 text-xs text-green-600 dark:text-green-400">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            </span>
                                            <span>Latest</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Slide Preview -->
                                <div class="relative bg-gray-100 dark:bg-gray-950" style="width: 100%; aspect-ratio: 16/9; overflow: hidden;">
                                    <!-- View Toggle Buttons (Hover Only) -->
                                    <div class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <div class="inline-flex rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-lg" role="group">
                                            <button wire:click="toggleSlideView({{ $index }})"
                                                class="px-3 py-1.5 text-xs font-medium {{ ($slideViewModes[$index] ?? 'image') === 'image' ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }} rounded-l-lg">
                                                Preview
                                            </button>
                                            <button wire:click="toggleSlideView({{ $index }})"
                                                class="px-3 py-1.5 text-xs font-medium {{ ($slideViewModes[$index] ?? 'image') === 'html' ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }} rounded-r-lg border-l border-gray-300 dark:border-gray-600">
                                                Code
                                            </button>
                                        </div>
                                    </div>

                                    @if(($slideViewModes[$index] ?? 'image') === 'html')
                                        <!-- HTML Code View -->
                                        <div class="relative w-full h-full">
                                            <pre class="w-full h-full p-4 overflow-auto text-xs font-mono bg-gray-900 text-gray-100" style="white-space: pre; word-wrap: normal;"><code>{{ $slide['html_content'] ?? '' }}</code></pre>

                                            <!-- Copy Button (Hover Only) -->
                                            <button
                                                x-data="{ copied: false }"
                                                @click="
                                                    navigator.clipboard.writeText('{{ addslashes($slide['html_content'] ?? '') }}');
                                                    copied = true;
                                                    setTimeout(() => copied = false, 2000);
                                                "
                                                class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200 inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 shadow-lg">
                                                <x-filament::icon x-show="!copied" icon="heroicon-o-clipboard-document" class="w-4 h-4" />
                                                <x-filament::icon x-show="copied" icon="heroicon-o-check" class="w-4 h-4 text-green-400" />
                                                <span x-text="copied ? 'Copied!' : 'Copy HTML'"></span>
                                            </button>
                                        </div>
                                    @else
                                        <!-- Image/Rendered View -->
                                        <iframe
                                            srcdoc="{!! htmlspecialchars($slide['html_content'] ?? '', ENT_QUOTES, 'UTF-8') !!}"
                                            class="w-full h-full border-0"
                                        ></iframe>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($isGenerating)
                            <div class="text-center py-6">
                                <x-filament::loading-indicator class="w-6 h-6 mx-auto" />
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Generating more slides...
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </x-filament::section>
        </div>
    </div>

    <!-- Auto-scroll to latest slide -->
    @if(count($generatedSlides) > 0)
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.hook('morph.updated', ({ component, cleanup }) => {
                    const slidePreview = document.querySelector('.space-y-6 > div:last-of-type');
                    if (slidePreview) {
                        slidePreview.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });
        </script>
    @endif
</x-filament-panels::page>
