<div class="flex flex-col h-full px-20 py-16">
    <h2 class="text-7xl font-semibold mb-16 text-center">
        {{ $title ?? 'Outline' }}
    </h2>

    <div class="flex-1 flex items-center justify-center">
        @if(isset($points) && is_array($points))
            <ol class="text-5xl space-y-8 max-w-4xl">
                @foreach($points as $index => $point)
                    <li class="flex items-start">
                        <span class="font-bold mr-8 text-accent">{{ $index + 1 }}.</span>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
