@extends('layouts.public')

@section('title', 'Gospel Tracts - Resources - Pioneer Missions Africa')
@section('description', 'Download free gospel tracts in multiple languages. Short, shareable literature perfect for evangelism and spreading the everlasting gospel.')

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
                Gospel Tracts
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "Go ye into all the world, and preach the gospel to every creature."
            </p>
            <p class="text-white/70 text-sm">— Mark 16:15</p>
        </div>
    </div>
</section>

<!-- Introduction Section -->
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <h2 class="pma-section-title pma-heading mb-6" style="color: var(--color-indigo);">
                Welcome to PMA Tracts
            </h2>
            <div class="pma-body text-lg space-y-4" style="color: var(--color-olive);">
                <p>
                    These tracts have been formatted so anyone with access to a photocopier can print them out
                    and distribute them to family, friends, and neighbors.
                </p>
                <p>
                    For the cost of an A4 sheet of paper printed on both sides, you can take the truth about God
                    to your loved ones and your community.
                </p>
                <p class="pma-heading" style="color: var(--color-indigo);">
                    We encourage each person to get involved. Only you can reach the people you know.
                    Put these tracts in their hands. May God bless your efforts.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- English Tracts -->
@if(isset($tractsByLanguage['English']) && $tractsByLanguage['English']->count() > 0)
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">English Tracts</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Present truth in English for evangelism</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tractsByLanguage['English'] as $index => $tract)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                              style="background: var(--color-pma-green); color: white;">
                            {{ $tract->category }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $tract->code }}
                        </span>
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $tract->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($tract->download_count) }} downloads
                    </p>

                    @if($tract->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($tract->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/tracts/' . $tract->pdf_file) }}"
                       download
                       class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Afrikaans Tracts -->
@if(isset($tractsByLanguage['Afrikaans']) && $tractsByLanguage['Afrikaans']->count() > 0)
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Afrikaanse Traktate</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Huidige waarheid in Afrikaans</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tractsByLanguage['Afrikaans'] as $index => $tract)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                              style="background: var(--color-pma-green); color: white;">
                            {{ $tract->category }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $tract->code }}
                        </span>
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $tract->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($tract->download_count) }} downloads
                    </p>

                    @if($tract->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($tract->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/tracts/' . $tract->pdf_file) }}"
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

<!-- Xhosa Tracts -->
@if(isset($tractsByLanguage['Xhosa']) && $tractsByLanguage['Xhosa']->count() > 0)
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Xhosa Tracts</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Inyaniso yangoku ngesiXhosa</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tractsByLanguage['Xhosa'] as $index => $tract)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                              style="background: var(--color-pma-green); color: white;">
                            {{ $tract->category }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $tract->code }}
                        </span>
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $tract->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($tract->download_count) }} downloads
                    </p>

                    @if($tract->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($tract->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/tracts/' . $tract->pdf_file) }}"
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

<!-- Portuguese Tracts -->
@if(isset($tractsByLanguage['Portuguese']) && $tractsByLanguage['Portuguese']->count() > 0)
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Folhetos Portugueses</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Verdade presente em português</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tractsByLanguage['Portuguese'] as $index => $tract)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                              style="background: var(--color-pma-green); color: white;">
                            {{ $tract->category }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                            {{ $tract->code }}
                        </span>
                    </div>

                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {{ $tract->title }}
                    </h3>

                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($tract->download_count) }} downloads
                    </p>

                    @if($tract->description)
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                        {{ Str::limit($tract->description, 100) }}
                    </p>
                    @endif

                    <a href="{{ asset('storage/tracts/' . $tract->pdf_file) }}"
                       download
                       class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Baixar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- How to Use Tracts -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 pma-animate-on-scroll">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">How to Use These Tracts</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="pma-card pma-animate-on-scroll pma-stagger-1">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Print Double-Sided</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Print on both sides of an A4 sheet for cost-effective distribution.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-2">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Share Widely</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Distribute to family, friends, neighbors, and your community.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-3">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Use as Handouts</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Perfect for evangelistic events, church services, and Bible studies.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-4">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Share Digitally</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Email or share via social media to reach even more people.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Support This Ministry</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Help us continue producing and distributing free gospel literature
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary">
                    Make a Donation
                </a>
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Request Physical Tracts
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
