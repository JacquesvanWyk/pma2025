<x-filament-panels::page>
    <!-- Instructions Card -->
    <div class="mb-6">
        <x-filament::section>
            <x-slot name="heading">
                How to Use the Study Guide Generator
            </x-slot>

            <x-slot name="description">
                This AI-powered tool helps you create Bible study guides instantly. Simply enter a topic or Bible passage, choose your preferred format, and let AI generate a comprehensive study guide based on Adventist theology and present truth.
            </x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <ul class="text-sm text-gray-600 dark:text-gray-400">
                    <li>Enter any Bible topic, passage, or doctrinal subject</li>
                    <li>Select the appropriate level and format for your audience</li>
                    <li>Add any specific points or emphasis you want included</li>
                    <li>Click "Generate" and review the AI-created content</li>
                    <li>Always verify theological accuracy before using</li>
                </ul>
            </div>
        </x-filament::section>
    </div>

    <!-- Split Layout: Form (1/3) + Preview (2/3) -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Side: Form (1/3) -->
        <div class="w-full lg:w-1/3">
            <x-filament::section>
                <x-slot name="heading">
                    Study Guide Configuration
                </x-slot>

                {{ $this->form }}
            </x-filament::section>
        </div>

        <!-- Right Side: Preview (2/3) -->
        <div class="w-full lg:w-2/3">
            <x-filament::section class="h-full">
                <x-slot name="heading">
                    Preview
                </x-slot>

                <div class="space-y-4" wire:poll.5s="checkGenerationStatus">
                    @if ($isGenerating)
                        <!-- AI Generating Animation -->
                        <div
                            x-data
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                        >
                            <x-ai-generating-animation
                                :statusMessage="$generationStatusMessage ?? 'Preparing AI generation...'"
                            />
                        </div>
                    @elseif ($generatedContent)
                        <!-- Generated Content Preview -->
                        <div
                            class="space-y-4"
                            x-data
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                        >
                            @if ($generatedImagePath)
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-start gap-3 mb-3">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Featured Image
                                            </p>
                                        </div>
                                    </div>
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($generatedImagePath) }}"
                                        alt="Generated featured image"
                                        class="w-full rounded-lg shadow-md"
                                    >
                                </div>
                            @endif

                            <!-- Content Preview -->
                            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-h-[600px] overflow-y-auto">
                                <div
                                    class="prose dark:prose-invert max-w-none text-sm"
                                    x-data="{
                                        content: '',
                                        fullContent: @js(\Illuminate\Support\Str::markdown($generatedContent)),
                                        currentIndex: 0,
                                        typeWriter() {
                                            if (this.currentIndex < this.fullContent.length) {
                                                this.content += this.fullContent.charAt(this.currentIndex);
                                                this.currentIndex++;
                                                setTimeout(() => this.typeWriter(), 1);
                                            }
                                        }
                                    }"
                                    x-init="typeWriter()"
                                    x-html="content"
                                ></div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <x-filament::button
                                    wire:click="$dispatch('copy-to-clipboard', { text: @js($generatedContent) })"
                                    size="sm"
                                    color="gray"
                                    icon="heroicon-o-clipboard-document"
                                    class="flex-1"
                                >
                                    Copy
                                </x-filament::button>

                                <x-filament::button
                                    wire:click="downloadStudyGuide"
                                    size="sm"
                                    color="gray"
                                    icon="heroicon-o-arrow-down-tray"
                                    class="flex-1"
                                >
                                    Download
                                </x-filament::button>
                            </div>

                            <!-- Save Info -->
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                            Ready to save?
                                        </p>
                                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                            Use the "Save as Study" button at the top to save this content.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                No content yet
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                Fill in the form and click Generate
                            </p>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>
    </div>


    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('copy-to-clipboard', (event) => {
                navigator.clipboard.writeText(event.text).then(() => {
                    new FilamentNotification()
                        .title('Copied to clipboard!')
                        .success()
                        .send();
                });
            });

            // Warn before leaving page during generation
            window.addEventListener('beforeunload', (event) => {
                const isGenerating = @js($isGenerating);

                if (isGenerating) {
                    event.preventDefault();
                    event.returnValue = 'Study guide generation is in progress. Are you sure you want to leave?';
                    return event.returnValue;
                }
            });

            // Update beforeunload listener when isGenerating changes
            Livewire.on('study-generation-complete', () => {
                // Allow navigation after completion
            });

            Livewire.on('study-generation-error', () => {
                // Allow navigation after error
            });
        });
    </script>
</x-filament-panels::page>
