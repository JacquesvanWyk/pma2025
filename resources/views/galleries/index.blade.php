@extends('layouts.public')

@section('title', 'Photo Gallery - Pioneer Missions Africa')
@section('description', 'Browse photos from Pioneer Missions Africa events, camp meetings, and mission activities.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Photo Gallery
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "That which we have seen and heard declare we unto you, that ye also may have fellowship with us: and truly our fellowship is with the Father, and with his Son Jesus Christ."
            </p>
            <p class="text-white/70 text-sm">— 1 John 1:3</p>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        @if($galleries->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h2 class="pma-heading text-2xl mb-2" style="color: var(--color-indigo);">No galleries yet</h2>
                <p class="pma-body" style="color: var(--color-olive);">Check back soon for photos from our events and activities.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($galleries as $index => $gallery)
                <a href="{{ route('gallery.show', $gallery->slug) }}"
                   class="pma-card group pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }} overflow-hidden">
                    <div class="aspect-[4/3] overflow-hidden">
                        @if($gallery->cover_image)
                            <img src="{{ asset('storage/' . $gallery->cover_image) }}"
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @elseif($gallery->images->first())
                            <img src="{{ asset('storage/' . $gallery->images->first()->image_path) }}"
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background: var(--color-cream);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="pma-heading text-xl mb-2 group-hover:text-[var(--color-pma-green)] transition-colors" style="color: var(--color-indigo);">
                            {{ $gallery->title }}
                        </h3>

                        <div class="flex flex-wrap items-center gap-3 text-sm mb-3" style="color: var(--color-olive);">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $gallery->images_count }} {{ Str::plural('photo', $gallery->images_count) }}
                            </span>
                            @if($gallery->event)
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $gallery->event->start_date?->format('M d, Y') }}
                                </span>
                            @elseif($gallery->event_date)
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $gallery->event_date->format('M d, Y') }}
                                </span>
                            @endif
                            @php
                                $location = $gallery->event?->city ?? $gallery->event?->location ?? $gallery->location;
                            @endphp
                            @if($location)
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $location }}
                                </span>
                            @endif
                        </div>

                        @if($gallery->event?->event_type)
                            <span class="inline-block px-2 py-1 rounded text-xs mb-3 pma-heading-light"
                                  style="background: var(--color-pma-green); color: white;">
                                {{ str_replace('_', ' ', ucfirst($gallery->event->event_type)) }}
                            </span>
                        @endif

                        @if($gallery->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($gallery->tags->take(3) as $tag)
                                    <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                                          style="background: var(--color-cream-dark); color: var(--color-indigo);">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <span class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center text-sm mt-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            View Gallery
                        </span>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $galleries->links() }}
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
