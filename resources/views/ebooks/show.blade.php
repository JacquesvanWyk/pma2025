@extends('layouts.public')

@section('title', $ebook->title . ' - E-Book - Resources - Pioneer Missions Africa')
@section('description', 'Download and read ' . $ebook->title . ' - a comprehensive Christian e-book on biblical truth and prophecy.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="w-full h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <h1 class="pma-hero-title pma-display text-white mb-6">
                    {{ $ebook->title }}
                </h1>
                @if($ebook->author)
                <p class="pma-hero-subtitle pma-accent text-white/90">
                    by {{ $ebook->author }}
                </p>
                @endif
                @if($ebook->edition)
                <p class="pma-hero-subtitle pma-accent text-white/90">
                    {{ $ebook->edition }}
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Book Details Section -->
<section class="py-20" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Book Cover -->
                <div class="lg:col-span-1">
                    <div class="pma-card-elevated">
                        <div class="aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                            @if($ebook->thumbnail)
                                <img src="{{ asset('storage/ebooks/' . $ebook->thumbnail) }}"
                                     alt="{{ $ebook->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0 3.332 477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="pma-card pma-card-elevated">
                        <div class="p-6">
                            <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">
                                {{ $ebook->title }}
                            </h2>
                            @if($ebook->author)
                            <p class="pma-body text-lg mb-3" style="color: var(--color-olive);">
                                by {{ $ebook->author }}
                            </p>
                            @endif
                            @if($ebook->edition)
                            <p class="pma-body text-sm mb-4" style="color: var(--color-ochre);">
                                {{ $ebook->edition }}
                            </p>
                            @endif
                            @if($ebook->language)
                            <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                                Language: {{ $ebook->language }}
                            </p>
                            @endif
                            @if($ebook->description)
                            <div class="prose prose prose-sm text-gray-600 mt-4">
                                <p>{{ $ebook->description }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Download Button -->
                        <div class="p-6">
                            <a href="{{ route('ebooks.download', $ebook->slug) }}"
                               download="{{ $ebook->pdf_file }}"
                               class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>

                            @if($ebook->download_count > 0)
                            <p class="pma-body text-sm mt-3 text-center" style="color: var(--color-olive);">
                                Downloaded {{ $ebook->download_count }} times
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Books -->
@if(isset($relatedEbooks) && $relatedEbooks->count() > 0)
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 pma-animate-on-scroll">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Related Books</h2>
                <p class="pma-body text-lg" style="color: var(--color-olive);">Explore more resources on similar topics</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedEbooks as $index => $relatedBook)
                    <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                        <div class="aspect-[3/4] w-full overflow-hidden rounded-t-lg bg-gray-200">
                            @if($relatedBook->thumbnail)
                                <img src="{{ asset('storage/ebooks/' . $relatedBook->thumbnail) }}"
                                     alt="{{ $relatedBook->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0 3.332 477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">
                                {{ $relatedBook->title }}
                            </h3>
                            @if($relatedBook->author)
                            <p class="pma-body text-sm mb-2" style="color: var(--color-olive);">
                                by {{ $relatedBook->author }}
                            </p>
                            @endif
                            <a href="{{ route('ebooks.show', $relatedBook->slug) }}"
                               class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                View Book
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
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
                    Request an E-Book
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