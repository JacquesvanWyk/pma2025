<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Core Theological Beliefs -->
        <x-filament::section>
            <x-slot name="heading">
                Core Theological Beliefs
            </x-slot>

            <x-slot name="description">
                These beliefs guide the AI in generating biblically accurate content that aligns with Pioneer Adventist theology.
            </x-slot>

            <div class="space-y-6">
                @php
                    $beliefs = config('study-ai.beliefs');
                @endphp

                @foreach($beliefs as $key => $belief)
                    <div class="border-l-4 border-primary-500 pl-4 py-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 capitalize">
                            {{ str_replace('_', ' ', $key) }}
                        </h3>

                        @if(isset($belief['position']))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span class="font-medium">Position:</span> {{ $belief['position'] }}
                            </p>
                        @endif

                        @if(isset($belief['description']))
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                {{ $belief['description'] }}
                            </p>
                        @endif

                        @if(isset($belief['key_points']))
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Key Points:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    @foreach($belief['key_points'] as $point)
                                        <li>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(isset($belief['emphasis']))
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Emphasis:</p>
                                @if(is_array($belief['emphasis']))
                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        @foreach($belief['emphasis'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $belief['emphasis'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if(isset($belief['focus']))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                <span class="font-medium">Focus:</span> {{ $belief['focus'] }}
                            </p>
                        @endif

                        @if(isset($belief['note']))
                            <p class="text-sm italic text-gray-500 dark:text-gray-500 mt-2">
                                Note: {{ $belief['note'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <!-- Formatting Guidelines -->
        <x-filament::section>
            <x-slot name="heading">
                Content Formatting Guidelines
            </x-slot>

            <x-slot name="description">
                Rules for consistent, readable, and well-structured content.
            </x-slot>

            <div class="space-y-4">
                @php
                    $formatting = config('study-ai.formatting');
                @endphp

                @foreach($formatting as $category => $rules)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-3 capitalize">
                            {{ str_replace('_', ' ', $category) }}
                        </h3>

                        <dl class="space-y-2">
                            @foreach($rules as $rule => $value)
                                <div class="flex gap-2">
                                    <dt class="text-sm font-medium text-gray-700 dark:text-gray-300 min-w-[120px] capitalize">
                                        {{ str_replace('_', ' ', $rule) }}:
                                    </dt>
                                    <dd class="text-sm text-gray-600 dark:text-gray-400">
                                        @if(is_array($value))
                                            {{ implode(', ', $value) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <!-- AI Model Settings -->
        <x-filament::section>
            <x-slot name="heading">
                AI Model Configuration
            </x-slot>

            <x-slot name="description">
                Current AI provider and model settings.
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $models = config('study-ai.models');
                @endphp

                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Text Generation</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Provider</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $models['text']['provider'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Model</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $models['text']['model'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Temperature</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $models['text']['temperature'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Max Tokens</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($models['text']['max_tokens']) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Image Generation</h4>
                    @php
                        $images = config('study-ai.images');
                    @endphp
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $images['enabled'] ? 'Yes' : 'No' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Provider</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $images['provider'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Model</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $images['model'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Size</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400">{{ $images['settings']['size'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </x-filament::section>

        <!-- Quality Guidelines -->
        <x-filament::section>
            <x-slot name="heading">
                Content Quality Standards
            </x-slot>

            <div class="space-y-4">
                @php
                    $quality = config('study-ai.quality');
                @endphp

                @foreach($quality as $category => $guidelines)
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 capitalize">
                            {{ str_replace('_', ' ', $category) }}
                        </h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            @foreach($guidelines as $guideline)
                                <li>{{ $guideline }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
