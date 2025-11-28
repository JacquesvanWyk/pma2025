@extends('layouts.public')

@section('title', 'Study Notes - Resources - Pioneer Missions Africa')
@section('description', 'Download free study notes and presentations. Sermon notes, Bible study materials, and teaching resources.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Study Notes
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                Feel free to download and share our studies. Sermon notes, presentations, and Bible study materials.
            </p>
        </div>
    </div>
</section>

<!-- English Notes -->
@if(isset($notesByLanguage['English']) && $notesByLanguage['English']->count() > 0)
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">English Studies</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Study materials in English</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($notesByLanguage['English'] as $index => $note)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light uppercase"
                              style="background: var(--color-pma-green); color: white;">
                            {{ strtoupper($note->file_type) }}
                        </span>
                        @if($note->category)
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $note->category }}
                        </span>
                        @endif
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $note->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($note->download_count) }} downloads
                    </p>

                    @if($note->author)
                    <p class="pma-body text-sm mb-3" style="color: var(--color-olive);">
                        by {{ $note->author }}
                    </p>
                    @endif

                    @if($note->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($note->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/notes/' . $note->file_path) }}"
                       download
                       class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Afrikaans Notes -->
@if(isset($notesByLanguage['Afrikaans']) && $notesByLanguage['Afrikaans']->count() > 0)
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Afrikaanse Studies</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Studiemateriaal in Afrikaans</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($notesByLanguage['Afrikaans'] as $index => $note)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light uppercase"
                              style="background: var(--color-pma-green); color: white;">
                            {{ strtoupper($note->file_type) }}
                        </span>
                        @if($note->category)
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $note->category }}
                        </span>
                        @endif
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $note->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($note->download_count) }} downloads
                    </p>

                    @if($note->author)
                    <p class="pma-body text-sm mb-3" style="color: var(--color-olive);">
                        deur {{ $note->author }}
                    </p>
                    @endif

                    @if($note->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($note->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/notes/' . $note->file_path) }}"
                       download
                       class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Laai Af
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Support This Ministry</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Help us continue producing and distributing free study resources
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary">
                    Make a Donation
                </a>
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Contact Us
                </a>
            </div>
        </div>
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
