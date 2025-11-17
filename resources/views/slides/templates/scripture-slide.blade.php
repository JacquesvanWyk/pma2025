<div class="flex flex-col items-center justify-center h-full text-center px-24 py-16">
    @if(isset($reference))
        <p class="text-4xl font-semibold mb-12 opacity-90">
            {{ $reference }}
        </p>
    @endif

    <div class="text-5xl leading-relaxed italic max-w-5xl">
        @if(isset($verses) && is_array($verses))
            @foreach($verses as $verse)
                <p class="mb-8">
                    @if(isset($verse['number']))
                        <sup class="text-3xl opacity-75 mr-2">{{ $verse['number'] }}</sup>
                    @endif
                    {{ $verse['text'] }}
                </p>
            @endforeach
        @elseif(isset($text))
            <p>{{ $text }}</p>
        @endif
    </div>

    @if(isset($reference))
        <p class="text-3xl mt-16 opacity-75">
            — {{ $reference }}
        </p>
    @endif
</div>
