<div class="flex flex-col h-full px-20 py-16">
    <h2 class="text-6xl font-semibold mb-12">
        {{ $title }}
    </h2>

    <div class="flex-1 flex gap-12">
        <div class="flex-1 text-4xl leading-relaxed">
            @if(isset($leftTitle))
                <h3 class="text-5xl font-semibold mb-8 text-accent">{{ $leftTitle }}</h3>
            @endif

            @if(isset($leftContent))
                <div class="prose prose-invert max-w-none">
                    {!! $leftContent !!}
                </div>
            @endif
        </div>

        <div class="w-px bg-white opacity-30"></div>

        <div class="flex-1 text-4xl leading-relaxed">
            @if(isset($rightTitle))
                <h3 class="text-5xl font-semibold mb-8 text-accent">{{ $rightTitle }}</h3>
            @endif

            @if(isset($rightContent))
                <div class="prose prose-invert max-w-none">
                    {!! $rightContent !!}
                </div>
            @endif
        </div>
    </div>
</div>
