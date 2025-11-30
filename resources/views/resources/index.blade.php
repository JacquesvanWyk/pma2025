@extends('layouts.public')

@section('title', 'Resources - Pioneer Missions Africa')
@section('description', 'Download free gospel resources including tracts, e-books, study notes, and DVD ministry materials from Pioneer Missions Africa.')

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
                Ministry Resources
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "Ho, every one that thirsteth, come ye to the waters, and he that hath no money; come ye, buy, and eat; yea, come, buy wine and milk without money and without price."
            </p>
            <p class="text-white/70 text-sm mb-8">— Isaiah 55:1</p>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="inline-block px-6 py-2 rounded-full pma-body text-sm"
                      style="background: rgba(255, 255, 255, 0.2); color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Free Downloads
                </span>
                <span class="inline-block px-6 py-2 rounded-full pma-body text-sm"
                      style="background: rgba(255, 255, 255, 0.2); color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                    Multiple Languages
                </span>
                <span class="inline-block px-6 py-2 rounded-full pma-body text-sm"
                      style="background: rgba(255, 255, 255, 0.2); color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Print Ready
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Resource Categories -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Browse Resources</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Choose a category to explore our free ministry materials</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Tracts -->
            <a href="{{ route('resources.tracts') }}" class="pma-card pma-animate-on-scroll pma-stagger-1 block group">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center transition-all group-hover:scale-110"
                         style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">Gospel Tracts</h3>
                    <p class="pma-body mb-4" style="color: var(--color-olive);">Short, shareable gospel literature perfect for evangelism and outreach</p>
                    <span class="inline-block px-4 py-1 rounded-full text-xs pma-heading-light mb-4"
                          style="background: var(--color-pma-green); color: white;">{{ \App\Models\Tract::whereNull('deleted_at')->count() }} Available</span>
                    <div>
                        <span class="pma-btn pma-btn-primary text-sm">Browse Tracts</span>
                    </div>
                </div>
            </a>

            <!-- E-Books -->
            <a href="{{ route('resources.ebooks') }}" class="pma-card pma-animate-on-scroll pma-stagger-2 block group">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center transition-all group-hover:scale-110"
                         style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">E-Books</h3>
                    <p class="pma-body mb-4" style="color: var(--color-olive);">Comprehensive digital books on biblical truth and present-day messages</p>
                    <span class="inline-block px-4 py-1 rounded-full text-xs pma-heading-light mb-4"
                          style="background: var(--color-pma-green); color: white;">{{ \App\Models\Ebook::count() }} Available</span>
                    <div>
                        <span class="pma-btn pma-btn-primary text-sm">Browse E-Books</span>
                    </div>
                </div>
            </a>

            <!-- Picture Studies -->
            <a href="{{ route('resources.picture-studies') }}" class="pma-card pma-animate-on-scroll pma-stagger-3 block group">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center transition-all group-hover:scale-110"
                         style="background: linear-gradient(135deg, var(--color-ochre-light), var(--color-ochre));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">Picture Studies</h3>
                    <p class="pma-body mb-4" style="color: var(--color-olive);">Visual infographic studies for evangelism and Bible study</p>
                    <span class="inline-block px-4 py-1 rounded-full text-xs pma-heading-light mb-4"
                          style="background: var(--color-pma-green); color: white;">{{ \App\Models\PictureStudy::where('status', 'published')->count() }} Available</span>
                    <div>
                        <span class="pma-btn pma-btn-primary text-sm whitespace-nowrap">View All</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Resources -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Popular Downloads</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Our most downloaded and shared resources</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Featured Item 1 -->
            <div class="pma-card pma-animate-on-scroll pma-stagger-1">
                <div class="p-6">
                    <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light mb-3"
                          style="background: var(--color-pma-green); color: white;">Tract</span>
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">Which God?</h3>
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">Exploring the biblical teaching about the nature of God and the Godhead</p>
                    <div class="flex gap-2 mb-4">
                        <span class="px-2 py-1 rounded text-xs pma-body" style="background: var(--color-cream); color: var(--color-olive);">PDF</span>
                        <span class="px-2 py-1 rounded text-xs pma-body" style="background: var(--color-cream); color: var(--color-olive);">EN/AF</span>
                    </div>
                    <a href="{{ route('resources.tracts') }}" class="pma-btn pma-btn-primary text-sm w-full inline-block text-center">View Tracts</a>
                </div>
            </div>

            <!-- Featured Item 2 -->
            <div class="pma-card pma-animate-on-scroll pma-stagger-2">
                <div class="p-6">
                    <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light mb-3"
                          style="background: var(--color-pma-green); color: white;">E-Book</span>
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">Daniel and the Revelation</h3>
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">A detailed prophetic study connecting biblical prophecy to present truth</p>
                    <div class="flex gap-2 mb-4">
                        <span class="px-2 py-1 rounded text-xs pma-body" style="background: var(--color-cream); color: var(--color-olive);">PDF</span>
                        <span class="px-2 py-1 rounded text-xs pma-body" style="background: var(--color-cream); color: var(--color-olive);">EN</span>
                    </div>
                    <a href="{{ route('resources.ebooks') }}" class="pma-btn pma-btn-primary text-sm w-full inline-block text-center">View E-Books</a>
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
            <h2 class="pma-hero-title pma-display text-white mb-6">Looking for More Resources?</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                We're constantly adding new materials. Contact us to request specific resources
                or support our ministry to help us create more content.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-primary">
                    Request a Resource
                </a>
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Support Our Ministry
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
