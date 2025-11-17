@extends('layouts.public')

@section('title', 'Believer Network - Pioneer Missions Africa')
@section('description', 'Connect with believers and fellowship groups across South Africa. Find like-minded individuals in your area through our interactive faith community map.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Believer Network
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 mb-8">
                Connect with believers and fellowship groups across South Africa. Find like-minded individuals in your area and build meaningful faith relationships.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('network.join') }}" class="pma-btn pma-btn-primary">
                    <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Join the Network
                </a>
                <a href="#network-map" class="pma-btn pma-btn-secondary">
                    <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Network
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Network Map -->
<section id="network-map" class="relative" style="background: white;">
    <livewire:network-map />
</section>

<!-- How It Works -->
<section class="py-20 lg:py-32" style="background: var(--color-cream);">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto text-center mb-16">
            <h2 class="pma-section-title pma-display mb-6" style="color: var(--color-indigo);">
                How It Works
            </h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">
                Simple steps to connect with believers in your community
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center pma-animate-on-scroll pma-stagger-1">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Join the Network</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Create your profile with your location and interests. We'll review your submission to ensure community safety.
                </p>
            </div>

            <div class="text-center pma-animate-on-scroll pma-stagger-2">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Find Believers</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Search the interactive map to find individuals and fellowship groups in your area. Filter by type and language.
                </p>
            </div>

            <div class="text-center pma-animate-on-scroll pma-stagger-3">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-4.216-7.919M9 16a9.003 9.003 0 01-4.216-7.919M12 8c0 4.418-4.03 8-9 8a9.003 9.003 0 01-9-8c0-4.418 4.03-8 9-8a9.003 9.003 0 019 8" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Connect & Fellowship</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Reach out to believers in your area. Join or start fellowship groups, and build lasting faith relationships.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-section-title pma-display mb-6" style="color: var(--color-indigo);">
                Ready to Connect?
            </h2>
            <p class="pma-body text-lg mb-10" style="color: var(--color-olive);">
                Join thousands of believers who are building faith communities across South Africa.
                Your next spiritual connection might be just around the corner.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('network.join') }}" class="pma-btn pma-btn-primary">
                    Join the Network
                </a>
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate-on-scroll functionality
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