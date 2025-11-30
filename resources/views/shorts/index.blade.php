@extends('layouts.public')

@section('title', 'Shorts - Pioneer Missions Africa')
@section('description', 'Watch short encouraging videos from Pastor Virgil.')

@section('content')
<!-- Hero Section -->
<section class="relative py-16 lg:py-24 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-4">
                Shorts
            </h1>
            <p class="pma-body text-lg text-white/90 max-w-2xl mx-auto">
                Quick, encouraging messages to strengthen your faith throughout the day.
            </p>
        </div>
    </div>
</section>

<!-- Shorts Grid -->
<section class="py-16 lg:py-24" style="background: white;">
    <div class="container mx-auto px-6">
        @if($shorts->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($shorts as $index => $short)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 8) + 1 }} group cursor-pointer"
                 onclick="openShortModal({{ $short->id }})">
                <!-- Thumbnail -->
                <div class="aspect-video w-full overflow-hidden rounded-t-lg bg-gray-200 relative">
                    @if($short->thumbnail_url)
                        <img src="{{ $short->thumbnail_url }}"
                             alt="{{ $short->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @elseif($short->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $short->youtube_id }}/maxresdefault.jpg"
                             alt="{{ $short->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='https://img.youtube.com/vi/{{ $short->youtube_id }}/hqdefault.jpg'">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                            <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Play Button Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Duration/Type Badge -->
                    <div class="absolute bottom-2 right-2">
                        @if($short->youtube_url)
                            <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">YouTube</span>
                        @else
                            <span class="px-2 py-1 bg-black/70 text-white text-xs font-bold rounded">Video</span>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="p-4">
                    <h3 class="pma-heading text-base mb-2 line-clamp-2" style="color: var(--color-indigo);">
                        {{ $short->title }}
                    </h3>
                    @if($short->tags && count($short->tags) > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice($short->tags, 0, 2) as $tag)
                            <span class="px-2 py-0.5 text-xs rounded-full" style="background: var(--color-cream); color: var(--color-olive);">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 pma-animate-on-scroll">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="w-10 h-10" style="color: var(--color-olive);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Coming Soon</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">
                Short videos will be available here soon. Check back later!
            </p>
        </div>
        @endif
    </div>
</section>

<!-- Video Modal -->
<div id="shortModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4">
    <div class="relative w-full max-w-lg">
        <!-- Close Button -->
        <button onclick="closeShortModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Video Container (16:9 aspect ratio) -->
        <div class="aspect-video w-full bg-black rounded-lg overflow-hidden">
            <div id="modalVideoContainer" class="w-full h-full"></div>
        </div>

        <!-- Title -->
        <h3 id="modalTitle" class="text-white text-lg font-semibold mt-4 text-center"></h3>
    </div>
</div>

@php
    $shortsJson = $shorts->map(function($s) {
        return [
            'id' => $s->id,
            'title' => $s->title,
            'video_url' => $s->video_url,
            'youtube_embed' => $s->youtube_embed_url,
        ];
    });
@endphp

@push('scripts')
<script>
    const shortsData = @json($shortsJson);

    function openShortModal(id) {
        const short = shortsData.find(s => s.id === id);
        if (!short) return;

        const modal = document.getElementById('shortModal');
        const container = document.getElementById('modalVideoContainer');
        const title = document.getElementById('modalTitle');

        title.textContent = short.title;

        if (short.youtube_embed) {
            container.innerHTML = `<iframe src="${short.youtube_embed}?autoplay=1" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        } else if (short.video_url) {
            container.innerHTML = `<video src="${short.video_url}" class="w-full h-full object-contain" controls autoplay></video>`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeShortModal() {
        const modal = document.getElementById('shortModal');
        const container = document.getElementById('modalVideoContainer');

        container.innerHTML = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeShortModal();
    });

    // Close on backdrop click
    document.getElementById('shortModal').addEventListener('click', function(e) {
        if (e.target === this) closeShortModal();
    });

    // Animation observer
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.pma-animate-on-scroll').forEach(el => observer.observe(el));
    });
</script>
@endpush
@endsection
