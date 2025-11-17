<div class="flex flex-col items-center justify-center h-full text-center px-24 py-16">
    <h2 class="text-7xl font-bold mb-16">
        {{ $title ?? 'Conclusion' }}
    </h2>

    @if(isset($summary))
        <p class="text-4xl leading-relaxed mb-12 max-w-4xl">
            {{ $summary }}
        </p>
    @endif

    @if(isset($callToAction))
        <div class="mt-16 px-16 py-8 bg-white bg-opacity-20 rounded-3xl backdrop-blur-sm">
            <p class="text-5xl font-semibold">
                {{ $callToAction }}
            </p>
        </div>
    @endif

    @if(isset($closingScripture))
        <p class="text-3xl mt-16 opacity-75 italic">
            {{ $closingScripture }}
        </p>
    @endif
</div>
