@extends('layouts.public')

@section('title', 'Kingdom Kids - Pioneer Missions Africa')
@section('description', 'Bible stories and songs for the little ones.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="mx-auto mb-8 flex items-center justify-center">
                <img src="{{ url('images/kingdomKids.png') }}" 
                     alt="Kingdom Kids" 
                     class="h-32 w-auto object-contain"
                     style="filter: brightness(0) invert(1) drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Kingdom Kids
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                Stories, songs, and lessons for our little pioneers!
            </p>
        </div>
    </div>
</section>

<!-- Videos Grid -->
<section class="py-20 lg:py-32" style="background: #FFFBF0;">
    <div class="container mx-auto px-6">
        @if(count($videos) > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($videos as $index => $video)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:transform hover:scale-105 transition-transform duration-300 border-4 border-white" style="border-color: {{ ['#FFB7B2', '#B5EAD7', '#C7CEEA', '#E2F0CB'][($index % 4)] }};">
                <div class="aspect-video w-full overflow-hidden bg-gray-200 relative group">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $video['id'] }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-xl mb-2 text-gray-800 line-clamp-2">{!! $video['title'] !!}</h3>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($video['published_at'])->format('F j, Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🎈</div>
            <h3 class="text-2xl font-bold text-gray-400">More fun videos coming soon!</h3>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.pma-animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
@endsection
