@extends('layouts.public')

@section('content')
    <!-- Hero Section - Monthly Pledge Campaign -->
    <section class="pma-hero">
        <!-- Geometric Light Rays Background -->
        <div class="pma-light-rays"></div>

        <!-- Organic Blobs -->
        <div class="pma-blob" style="top: 10%; left: 10%; width: 400px; height: 400px; background: var(--color-pma-green);"></div>
        <div class="pma-blob" style="bottom: 20%; right: 15%; width: 300px; height: 300px; background: var(--color-pma-green-light);"></div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-0"></div>

        <!-- Optional Background Image -->
        @if(file_exists(public_path('images/hero_1.png')))
        <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-overlay z-0"
             style="background-image: url('/images/hero_1.png');"></div>
        @endif

        <!-- Hero Content -->
        <div class="pma-hero-content container mx-auto px-6 py-20 lg:py-32 text-center">
            <div class="max-w-5xl mx-auto">
                <!-- Main Heading -->
                <h1 class="pma-hero-title pma-display text-white mb-6">
                    Monthly Pledge
                </h1>

                <!-- Subheading -->
                <p class="pma-hero-subtitle pma-accent text-white/90 mb-8">
                    Proclaiming the Everlasting Gospel
                </p>

                <!-- Essential Knowledge Quote -->
                <div class="pma-quote mb-12 max-w-3xl mx-auto">
                    <p class="pma-quote-text text-white/95">
                        "We believe a true Knowledge of God will lead to eternal life.
                        This true knowledge of God, and Jesus Christ whom He sent, is truly the essential knowledge."
                    </p>
                </div>

                <!-- Monthly Pledge Progress Card -->
                <div class="pma-card-elevated p-8 lg:p-12 mb-12 max-w-3xl mx-auto">
                    <h3 class="pma-heading text-3xl mb-6 text-center" style="color: var(--color-indigo);">
                        Monthly Pledge Goal
                    </h3>

                    <!-- Month Display -->
                    <div class="text-center mb-8">
                        <span class="inline-block px-6 py-3 rounded-full text-sm pma-heading"
                              style="background: var(--gradient-warm); color: white; letter-spacing: 0.05em;">
                            {{ $pledgeMonth }} PROGRESS
                        </span>
                    </div>

                    <!-- Current Amount Display -->
                    <div class="flex justify-between items-baseline mb-6">
                        <span class="pma-heading-light text-lg" style="color: var(--color-olive);">Current:</span>
                        <span class="pma-display text-5xl lg:text-6xl" style="color: var(--color-pma-green);">
                            R{{ number_format($currentPledges, 0) }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="pma-progress mb-4">
                        <div
                            id="hero-progress-bar"
                            class="pma-progress-bar"
                            style="width: 0%"
                            data-percentage="{{ $pledgePercentage }}"
                        ></div>
                    </div>

                    <!-- Progress Stats -->
                    <div class="flex justify-between mb-8 pma-body text-sm" style="color: var(--color-olive);">
                        <span class="font-semibold">{{ number_format($pledgePercentage, 1) }}% Complete</span>
                        <span class="font-semibold">Goal: R{{ number_format($pledgeGoal, 0) }}</span>
                    </div>

                    <div class="pma-divider"></div>

                    <!-- Call to Action -->
                    <p class="pma-body text-lg mb-8 leading-relaxed text-center" style="color: var(--color-indigo);">
                        Join us in supporting this vital ministry. Your monthly pledge helps us spread the knowledge
                        of the one true God across Africa.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('pledge') }}" class="pma-btn pma-btn-primary inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Support Our Ministry
                        </a>
                        <a href="{{ route('about') }}" class="pma-btn pma-btn-secondary inline-flex items-center justify-center">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Secondary CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center opacity-90">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-white hover:bg-white/20 btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost text-white hover:bg-white/20 btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login
                        </a>
                    @endauth
                    <a href="{{ route('studies') }}" class="btn btn-ghost text-white hover:bg-white/20 btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Browse Studies
                    </a>
                    <a href="{{ route('sermons') }}" class="btn btn-ghost text-white hover:bg-white/20 btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Watch Sermons
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="py-20 lg:py-32 relative" style="background: var(--gradient-spiritual);">
        <!-- Cross Pattern Overlay -->
        <div class="pma-cross-pattern absolute inset-0"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="order-2 lg:order-1 pma-animate-on-scroll">
                    <div class="pma-accent-line mb-4"></div>
                    <h2 class="pma-section-title pma-heading mb-6" style="color: var(--color-indigo);">
                        Welcome to Pioneer Missions Africa
                    </h2>
                    <p class="pma-body text-lg mb-6 leading-relaxed" style="color: var(--color-indigo);">
                        We are a ministry determined in our efforts to proclaim the Everlasting Gospel.
                        Our mission is to spread the knowledge of the one true God and His Son across Africa,
                        supporting individuals, groups, and home churches in their spiritual journey.
                    </p>
                    <p class="pma-body text-lg mb-8 leading-relaxed" style="color: var(--color-olive);">
                        Through biblical studies, resources, and fellowship, we seek to help God's people
                        understand present truth and prepare for the soon return of our Savior.
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="pma-stat pma-stagger-1">
                            <div class="pma-stat-number">8+</div>
                            <div class="pma-stat-label">Years Serving</div>
                        </div>
                        <div class="pma-stat pma-stagger-2">
                            <div class="pma-stat-number">100+</div>
                            <div class="pma-stat-label">Lives Touched</div>
                        </div>
                        <div class="pma-stat pma-stagger-3">
                            <div class="pma-stat-number">50+</div>
                            <div class="pma-stat-label">Resources</div>
                        </div>
                    </div>

                    <a href="{{ route('about') }}" class="pma-btn pma-btn-primary">Learn More About Us</a>
                </div>
                <div class="order-1 lg:order-2 pma-animate-on-scroll">
                    <!-- Image with fallback -->
                    <div class="relative rounded-3xl overflow-hidden" style="box-shadow: var(--shadow-xl);">
                        @if(file_exists(public_path('images/camp_1.jpg')))
                            <img src="/images/camp_1.jpg" alt="Ministry Team" class="w-full h-full object-cover aspect-[4/3]">
                        @else
                            <div class="w-full aspect-[4/3] flex items-center justify-center text-white p-8"
                                 style="background: var(--gradient-warm);">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto mb-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="pma-heading text-xl">Spreading the Gospel Across Africa</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Studies -->
    <section class="py-20 lg:py-32" style="background: white;">
        <div class="container mx-auto px-6">
            <div class="pma-section-header pma-animate-on-scroll">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Featured Studies</h2>
                <p class="pma-section-subtitle pma-body">
                    Explore our collection of biblical studies focused on present truth and the pioneering message
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredStudies as $index => $study)
                <div class="pma-card pma-animate-on-scroll pma-stagger-{{ $index + 1 }}">
                    <div class="p-6">
                        <div class="w-full h-48 mb-6 rounded-xl flex items-center justify-center relative overflow-hidden"
                             style="background: var(--gradient-spiritual);">
                            <div class="pma-cross-pattern absolute inset-0"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 relative z-10" fill="var(--color-ochre)" opacity="0.3" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"></path>
                            </svg>
                        </div>

                        <div class="flex gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full text-xs pma-heading-light"
                                  style="background: var(--color-pma-green); color: white;">
                                {{ $study->category->name }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs pma-body"
                                  style="background: var(--color-cream-dark); color: var(--color-olive);">
                                {{ $study->read_time }} min read
                            </span>
                        </div>

                        <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">
                            {{ $study->title }}
                        </h3>
                        <p class="pma-body mb-6" style="color: var(--color-olive);">
                            {{ $study->excerpt }}
                        </p>

                        <a href="{{ route('studies.show', $study->slug) }}" class="pma-btn pma-btn-secondary w-full text-center">
                            Read Study
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12 pma-animate-on-scroll">
                <a href="{{ route('studies') }}" class="pma-btn pma-btn-primary">View All Studies</a>
            </div>
        </div>
    </section>

    @include('partials.sermons-section')
    @include('partials.resources-section')
    @include('partials.support-section')
    @include('partials.newsletter-section')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress Bar Animation
    const heroProgressBar = document.getElementById('hero-progress-bar');
    if (heroProgressBar) {
        const targetPercentage = parseFloat(heroProgressBar.dataset.percentage);
        setTimeout(() => {
            heroProgressBar.style.width = targetPercentage + '%';
        }, 500);
    }

    // Scroll Animation Observer
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
