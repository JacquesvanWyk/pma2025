@extends('layouts.public')

@section('title', 'E-Books - Resources - Pioneer Missions Africa')
@section('description', 'Download free Christian e-books on biblical truth, prophecy, and the pioneering message. Comprehensive studies in PDF format.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                E-Books Library
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "Search the scriptures; for in them ye think ye have eternal life: and they are they which testify of me."
            </p>
            <p class="text-white/70 text-sm">— John 5:39</p>
        </div>
    </div>
</section>

<!-- Featured E-Book -->
@if($featuredEbook)
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <span class="inline-block px-6 py-2 rounded-full text-sm pma-heading mb-4"
                  style="background: var(--gradient-warm); color: white; letter-spacing: 0.05em;">
                FEATURED BOOK
            </span>
            <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Recommended Reading</h2>
        </div>

        <div class="max-w-4xl mx-auto pma-animate-on-scroll">
            <div class="pma-card-elevated">
                <div class="grid md:grid-cols-2 gap-8 p-8">
                    <div class="flex items-center justify-center">
                        @if($featuredEbook->thumbnail)
                        <img src="{{ asset('storage/ebooks/' . $featuredEbook->thumbnail) }}"
                             alt="{{ $featuredEbook->title }}"
                             class="w-full max-w-sm rounded-lg shadow-2xl">
                        @else
                        <div class="w-full max-w-sm h-96 rounded-lg flex items-center justify-center"
                             style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="pma-heading text-3xl mb-3" style="color: var(--color-indigo);">
                            {{ $featuredEbook->title }}
                        </h3>
                        @if($featuredEbook->author)
                        <p class="pma-body text-lg mb-3" style="color: var(--color-olive);">
                            by {{ $featuredEbook->author }}
                        </p>
                        @endif
                        @if($featuredEbook->edition)
                        <p class="pma-body text-sm mb-4" style="color: var(--color-ochre);">
                            {{ $featuredEbook->edition }}
                        </p>
                        @endif
                        <p class="pma-body mb-6" style="color: var(--color-olive);">
                            {{ $featuredEbook->description }}
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ asset('storage/ebooks/' . $featuredEbook->pdf_file) }}"
                               download
                               class="pma-btn pma-btn-primary inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- English E-Books -->
@if(isset($ebooksByLanguage['English']) && $ebooksByLanguage['English']->count() > 0)
<section id="english" class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">English E-Books</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Comprehensive studies on biblical truth and prophecy</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($ebooksByLanguage['English'] as $index => $ebook)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="aspect-[3/4] w-full overflow-hidden rounded-t-lg bg-gray-200">
                    @if($ebook->thumbnail)
                    <img src="{{ asset('storage/ebooks/' . $ebook->thumbnail) }}"
                         alt="{{ $ebook->title }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">{{ $ebook->title }}</h3>
                    @if($ebook->author)
                    <p class="pma-body text-sm mb-2" style="color: var(--color-olive);">by {{ $ebook->author }}</p>
                    @endif
                    @if($ebook->description)
                    <p class="pma-body text-sm mb-3" style="color: var(--color-olive);">
                        {{ Str::limit($ebook->description, 80) }}
                    </p>
                    @endif
                    @if($ebook->download_count > 0)
                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($ebook->download_count) }} downloads
                    </p>
                    @endif
                    <a href="{{ asset('storage/ebooks/' . $ebook->pdf_file) }}"
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

<!-- Afrikaans E-Books -->
@if(isset($ebooksByLanguage['Afrikaans']) && $ebooksByLanguage['Afrikaans']->count() > 0)
<section id="afrikaans" class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Afrikaanse E-Boeke</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Bybelse studies in Afrikaans</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($ebooksByLanguage['Afrikaans'] as $index => $ebook)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="aspect-[3/4] w-full overflow-hidden rounded-t-lg bg-gray-200">
                    @if($ebook->thumbnail)
                    <img src="{{ asset('storage/ebooks/' . $ebook->thumbnail) }}"
                         alt="{{ $ebook->title }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-olive-light), var(--color-olive));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">{{ $ebook->title }}</h3>
                    @if($ebook->author)
                    <p class="pma-body text-sm mb-2" style="color: var(--color-olive);">deur {{ $ebook->author }}</p>
                    @endif
                    @if($ebook->description)
                    <p class="pma-body text-sm mb-3" style="color: var(--color-olive);">
                        {{ Str::limit($ebook->description, 80) }}
                    </p>
                    @endif
                    @if($ebook->download_count > 0)
                    <p class="pma-body text-xs mb-3" style="color: var(--color-olive);">
                        {{ number_format($ebook->download_count) }} downloads
                    </p>
                    @endif
                    <a href="{{ asset('storage/ebooks/' . $ebook->pdf_file) }}"
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

<!-- Reading Tips -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 pma-animate-on-scroll">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Reading Tips</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="pma-card pma-animate-on-scroll pma-stagger-1">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Use a PDF Reader</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Adobe Acrobat Reader or similar apps provide the best reading experience with bookmarks and search features.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-2">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Take Notes</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Highlight important passages and add personal notes as you study. Most PDF readers support annotations.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-3">
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
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Print if Preferred</h3>
                            <p class="pma-body" style="color: var(--color-olive);">All e-books are formatted for printing. Consider printing chapters as you study them.</p>
                        </div>
                    </div>
                </div>

                <div class="pma-card pma-animate-on-scroll pma-stagger-4">
                    <div class="p-6 flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-pma-green-light);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">Share Freely</h3>
                            <p class="pma-body" style="color: var(--color-olive);">These books are meant to be shared. Email them to friends or share on social media.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pma-card mt-8 pma-animate-on-scroll" style="border-left: 4px solid var(--color-pma-green);">
                <div class="p-6 flex gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="pma-body" style="color: var(--color-olive);">
                        All e-books are free for personal and ministry use. Commercial reproduction requires permission.
                    </p>
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
            <h2 class="pma-hero-title pma-display text-white mb-6">Support Our E-Book Ministry</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Help us continue providing free spiritual resources by supporting our ministry
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary">
                    Make a Donation
                </a>
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Request a Book
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
