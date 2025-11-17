<div class="flex flex-col items-center justify-center h-full text-center px-16">
    <h1 class="text-8xl font-bold mb-8">
        {{ $title }}
    </h1>

    @if(isset($subtitle))
        <p class="text-5xl opacity-90">
            {{ $subtitle }}
        </p>
    @endif

    @if(isset($author))
        <p class="text-3xl mt-16 opacity-75">
            {{ $author }}
        </p>
    @endif

    @if(isset($date))
        <p class="text-2xl mt-4 opacity-60">
            {{ $date }}
        </p>
    @endif
</div>
