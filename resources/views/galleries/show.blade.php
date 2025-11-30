@extends('layouts.public')

@section('title', $gallery->title . ' - Photo Gallery - Pioneer Missions Africa')
@section('description', $gallery->description ?? 'Browse photos from ' . $gallery->title)

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-6 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Gallery
            </a>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                {{ $gallery->title }}
            </h1>

            <div class="flex flex-wrap items-center justify-center gap-4 text-white/80 mb-4">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $gallery->images->count() }} {{ Str::plural('photo', $gallery->images->count()) }}
                </span>
                @if($gallery->event)
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $gallery->event->start_date?->format('M d, Y') }}
                        @if($gallery->event->end_date && $gallery->event->end_date->ne($gallery->event->start_date))
                            - {{ $gallery->event->end_date->format('M d, Y') }}
                        @endif
                    </span>
                @elseif($gallery->event_date)
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $gallery->event_date->format('F j, Y') }}
                    </span>
                @endif
                @php
                    $location = $gallery->event?->city ?? $gallery->event?->location ?? $gallery->location;
                @endphp
                @if($location)
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $location }}
                    </span>
                @endif
                @if($gallery->event?->event_type)
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-white/20">
                        {{ str_replace('_', ' ', ucfirst($gallery->event->event_type)) }}
                    </span>
                @endif
            </div>

            @if($gallery->description)
                <p class="pma-hero-subtitle pma-accent text-white/90">
                    {{ $gallery->description }}
                </p>
            @endif
        </div>
    </div>
</section>

<!-- Photo Grid -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        @if($gallery->images->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h2 class="pma-heading text-2xl mb-2" style="color: var(--color-indigo);">No photos yet</h2>
                <p class="pma-body" style="color: var(--color-olive);">Photos will be added soon.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightboxOpen: false, currentImage: null, currentIndex: 0, images: {{ $gallery->images->map(fn($img) => ['src' => asset('storage/' . $img->image_path), 'title' => $img->title, 'caption' => $img->caption])->toJson() }} }">
                @foreach($gallery->images as $index => $image)
                <div class="pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                    <button @click="lightboxOpen = true; currentIndex = {{ $index }}; currentImage = images[{{ $index }}]"
                            class="group relative aspect-square overflow-hidden rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-[var(--color-pma-green)] bg-gray-100">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="{{ $image->alt_text ?? $image->title ?? $gallery->title }}"
                             class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </button>
                </div>
                @endforeach

                <!-- Lightbox -->
                <div x-show="lightboxOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @keydown.escape.window="lightboxOpen = false"
                     @keydown.left.window="currentIndex = (currentIndex - 1 + images.length) % images.length; currentImage = images[currentIndex]"
                     @keydown.right.window="currentIndex = (currentIndex + 1) % images.length; currentImage = images[currentIndex]"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/95"
                     style="display: none;">
                    <!-- Close Button -->
                    <button @click="lightboxOpen = false"
                            class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Previous Button -->
                    <button @click="currentIndex = (currentIndex - 1 + images.length) % images.length; currentImage = images[currentIndex]"
                            class="absolute left-4 text-white/80 hover:text-white transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button @click="currentIndex = (currentIndex + 1) % images.length; currentImage = images[currentIndex]"
                            class="absolute right-4 text-white/80 hover:text-white transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Image Container -->
                    <div class="max-w-6xl max-h-[90vh] mx-4">
                        <img :src="currentImage?.src"
                             :alt="currentImage?.title || ''"
                             class="max-w-full max-h-[80vh] object-contain mx-auto">
                        <div x-show="currentImage?.title || currentImage?.caption" class="text-center mt-4">
                            <h3 x-text="currentImage?.title" class="text-white text-lg font-medium"></h3>
                            <p x-text="currentImage?.caption" class="text-white/70 text-sm mt-1"></p>
                        </div>
                        <div class="text-center mt-2 text-white/50 text-sm">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($gallery->tags->isNotEmpty())
            <div class="mt-12 pt-8 border-t" style="border-color: var(--color-cream-dark);">
                <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($gallery->tags as $tag)
                        <span class="inline-block px-3 py-1.5 rounded-full text-sm pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
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
