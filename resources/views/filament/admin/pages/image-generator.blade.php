<x-filament-panels::page>
    {{-- Poll for task status when generating --}}
    @if ($this->isGenerating && $this->pendingTaskId)
        <div wire:poll.3s="checkTaskStatus"></div>
    @endif

    <div class="space-y-6">
        <!-- Credits Display -->
        @if ($this->kieCredits !== null)
            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">KIE.ai Credits Balance</p>
                        <p class="text-2xl font-bold">{{ number_format($this->kieCredits) }} credits</p>
                        <p class="text-sm opacity-75">${{ number_format($this->kieCredits * 0.005, 2) }} USD value</p>
                    </div>
                    <x-filament::icon icon="heroicon-o-currency-dollar" class="w-12 h-12 opacity-50" />
                </div>
            </div>
        @endif

        <!-- Form Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Input Form -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon
                            icon="heroicon-o-sparkles"
                            class="w-5 h-5 mr-2 text-primary-600"
                        />
                        Image Settings
                    </h2>

                    {{ $this->form }}
                </div>
            </div>

            <!-- Preview Area -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-full">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-filament::icon
                            icon="heroicon-o-photo"
                            class="w-5 h-5 mr-2 text-primary-600"
                        />
                        Generated Image
                    </h2>

                    @if ($this->isGenerating)
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Generating your image...</p>
                            <p class="text-xs text-gray-500 mt-2">This may take 30-60 seconds</p>
                            @if ($this->pollAttempts > 0)
                                <p class="text-xs text-gray-400 mt-1">Checking status... ({{ $this->pollAttempts }}/40)</p>
                            @endif
                        </div>
                    @elseif ($this->generatedImageUrl)
                        <div class="space-y-4">
                            <div class="relative">
                                <img
                                    src="{{ $this->generatedImageUrl }}"
                                    alt="Generated image"
                                    class="w-full h-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                />
                                <div class="hidden flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-8 h-8 text-gray-400 mb-2" />
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Image failed to load</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a
                                    href="{{ $this->generatedImageUrl }}"
                                    download="generated-image-{{ now()->format('Y-m-d-H-i') }}.png"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-4 h-4 mr-2" />
                                    Download
                                </a>

                                <button
                                    wire:click="clear"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <x-filament::icon icon="heroicon-o-trash" class="w-4 h-4 mr-2" />
                                    Clear
                                </button>
                            </div>

                            @if ($this->generatedImagePath)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <p>Saved: {{ $this->generatedImagePath }}</p>
                                </div>
                            @endif
                        </div>
                    @elseif ($this->generationError)
                        <div class="flex flex-col items-center justify-center py-12 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="w-8 h-8 text-red-500 mb-2" />
                            <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">Generation Failed</p>
                            <p class="text-xs text-red-600 dark:text-red-300 text-center px-4">{{ $this->generationError }}</p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <x-filament::icon icon="heroicon-o-photo" class="w-12 h-12 text-gray-400 mb-3" />
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">No image generated yet</p>
                            <p class="text-xs text-gray-500 text-center px-4">Fill in the form and click "Generate Image" to create your AI image</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pricing Info -->
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-currency-dollar" class="w-5 h-5 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">NanoBanana Pro Pricing</h3>
                    <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>1K Resolution:</strong> ~$0.02/image (4 credits)</li>
                            <li><strong>2K Resolution:</strong> ~$0.04/image (8 credits)</li>
                            <li><strong>4K Resolution:</strong> ~$0.08/image (16 credits)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-light-bulb" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Tips for Better Results</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Be Specific:</strong> "A vintage red 1965 Ford Mustang at sunset" beats "a car"</li>
                            <li><strong>Add Reference Images:</strong> Upload up to 8 images for style guidance or editing</li>
                            <li><strong>Add Details:</strong> Describe lighting, mood, setting, and atmosphere</li>
                            <li><strong>Example:</strong> "Jesus teaching by the Sea of Galilee, golden hour light, peaceful atmosphere, biblical accuracy"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
