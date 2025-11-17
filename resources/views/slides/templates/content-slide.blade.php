<div class="flex flex-col h-full px-20 py-16">
    <h2 class="text-6xl font-semibold mb-12">
        {{ $title }}
    </h2>

    <div class="flex-1 text-4xl leading-relaxed">
        @if(isset($bullets) && is_array($bullets))
            <ul class="space-y-6">
                @foreach($bullets as $bullet)
                    <li class="flex items-start">
                        <span class="mr-6 opacity-75">•</span>
                        <span>{{ $bullet }}</span>
                    </li>
                @endforeach
            </ul>
        @elseif(isset($content))
            <div class="prose prose-invert max-w-none">
                {!! $content !!}
            </div>
        @endif
    </div>
</div>
