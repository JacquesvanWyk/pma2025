@extends('layouts.public')

@section('title', 'Shorts - Pioneer Missions Africa')
@section('description', 'Watch short encouraging videos from Pastor Virgil.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Shorts
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "Preach the word; be instant in season, out of season; reprove, rebuke, exhort with all longsuffering and doctrine."
            </p>
            <p class="text-white/70 text-sm">— 2 Timothy 4:2</p>
        </div>
    </div>
</section>

<!-- Livewire Shorts List with Filters -->
@livewire('shorts-list')

<!-- Video Modal -->
<div id="shortModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4">
    <div class="relative w-full max-w-4xl">
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

@push('scripts')
<script>
    let shortsData = [];

    // Update shorts data when Livewire updates the DOM
    document.addEventListener('livewire:initialized', function() {
        updateShortsData();
    });

    document.addEventListener('livewire:navigated', function() {
        updateShortsData();
    });

    // Also update on any Livewire update
    Livewire.hook('morph.updated', () => {
        updateShortsData();
    });

    function updateShortsData() {
        // Get all short cards and extract data
        shortsData = [];
        document.querySelectorAll('[wire\\:key^="short-"]').forEach(el => {
            const id = parseInt(el.getAttribute('wire:key').replace('short-', ''));
            const title = el.querySelector('h3')?.textContent?.trim() || '';
            const isYoutube = el.querySelector('.bg-red-600') !== null;
            shortsData.push({ id, title, isYoutube });
        });
    }

    function openShortModal(id) {
        // Fetch the short data via API or use embedded data
        fetch(`/api/shorts/${id}`)
            .then(response => response.json())
            .then(short => {
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
            })
            .catch(error => console.error('Error loading short:', error));
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

        // Initial data load
        updateShortsData();
    });
</script>
@endpush
@endsection
