<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Input Form -->
            <div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-filament::icon
                            icon="heroicon-o-sparkles"
                            class="w-5 h-5 mr-2 text-primary-600"
                        />
                        Gemini Image Settings
                    </h2>

                    {{ $this->form }}
                </div>
            </div>

            <!-- Preview Area -->
            <div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 h-full">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-filament::icon
                            icon="heroicon-o-photo"
                            class="w-5 h-5 mr-2 text-primary-600"
                        />
                        Gemini Generated Image
                    </h2>

                    @if ($this->isGenerating)
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                            <p class="mt-4 text-sm text-gray-600">Generating your image with Gemini...</p>
                            <p class="text-xs text-gray-500 mt-2">This may take a few moments</p>
                        </div>
                    @elseif ($this->generatedImageUrl)
                        <div class="space-y-4">
                            <div class="relative">
                                <img
                                    src="{{ $this->generatedImageUrl }}"
                                    alt="Generated image"
                                    class="w-full h-auto rounded-lg shadow-md border border-gray-200"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                />
                                <div class="hidden flex-col items-center justify-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                    <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-8 h-8 text-gray-400 mb-2" />
                                    <p class="text-sm text-gray-600">Image failed to load</p>
                                    <p class="text-xs text-gray-500 mt-1">The image may have been moved or deleted</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a
                                    href="{{ $this->generatedImageUrl }}"
                                    download="gemini-image-{{ now()->format('Y-m-d-H-i') }}.png"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-4 h-4 mr-2" />
                                    Download
                                </a>

                                <button
                                    wire:click="clear"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                >
                                    <x-filament::icon icon="heroicon-o-trash" class="w-4 h-4 mr-2" />
                                    Clear
                                </button>
                            </div>

                            @if ($this->generatedImagePath)
                                <div class="text-xs text-gray-500">
                                    <p>Saved as: {{ $this->generatedImagePath }}</p>
                                </div>
                            @endif
                        </div>
                    @elseif ($this->generationError)
                        <div class="flex flex-col items-center justify-center py-12 bg-red-50 rounded-lg border border-red-200">
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="w-8 h-8 text-red-500 mb-2" />
                            <p class="text-sm font-medium text-red-800 mb-1">Generation Failed</p>
                            <p class="text-xs text-red-600 text-center px-4">{{ $this->generationError }}</p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <x-filament::icon icon="heroicon-o-sparkles" class="w-12 h-12 text-gray-400 mb-3" />
                            <p class="text-sm font-medium text-gray-600 mb-1">No image generated yet</p>
                            <p class="text-xs text-gray-500 text-center px-4">Fill in the form and click "Generate Image with Gemini" to create an AI image using Google's Gemini Imagen 3</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Comparison Info -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-purple-600" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800">About Gemini Image Generation</h3>
                    <div class="mt-2 text-sm text-purple-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Service:</strong> Google Gemini Imagen 3 (different from Nano Banana API)</li>
                            <li><strong>Style:</strong> Uses Google's AI image generation model with Seventh-day Adventist theological guidelines</li>
                            <li><strong>Content:</strong> Automatically avoids Trinity symbolism and Catholic imagery per configuration</li>
                            <li><strong>Storage:</strong> Images are saved locally to your server for permanent access</li>
                            <li><strong>Comparison:</strong> Try generating the same topic with both services to compare results!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Tips -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-light-bulb" class="w-5 h-5 text-blue-600" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Tips for Better Results</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Be Specific:</strong> Instead of "Jesus", try "Jesus teaching the multitudes by the Sea of Galilee"</li>
                            <li><strong>Choose Right Style:</strong> Educational for diagrams, Inspirational for uplifting scenes, Historical for biblical accuracy</li>
                            <li><strong>Consider Audience:</strong> Children get bright simple images, Adults get detailed reverent illustrations</li>
                            <li><strong>Biblical Topics:</strong> Works best with well-known biblical stories and characters</li>
                            <li><strong>Example:</strong> "Daniel in the lions' den, peaceful, protective angels surrounding, historical setting"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>