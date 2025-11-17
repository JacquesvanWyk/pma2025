@extends('layouts.public')

@section('title', $networkMember->name . ' - Believer Network')
@section('description', $networkMember->bio ? substr($networkMember->bio, 0, 155) : 'Connect with ' . $networkMember->name . ' on the Pioneer Missions Africa believer network.')

@section('content')
<!-- Hero Section -->
<section class="relative py-12 lg:py-16 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-white/80 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('network.index') }}" class="hover:text-white transition-colors">Network</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-white">{{ $networkMember->name }}</li>
                </ol>
            </nav>

            <!-- Profile Header -->
            <div class="pma-animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl mr-4"
                         style="background: {{ $networkMember->type === 'individual' ? 'var(--color-indigo)' : 'var(--color-pma-green)' }}; border: 4px solid white;">
                        {{ $networkMember->type === 'individual' ? '=d' : 'ê' }}
                    </div>
                    <div>
                        <h1 class="pma-hero-title pma-display text-white mb-2">
                            {{ $networkMember->name }}
                        </h1>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                              style="background: rgba(255, 255, 255, 0.2); color: white;">
                            {{ $networkMember->type === 'individual' ? 'Individual Believer' : 'Fellowship Group' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-16 lg:py-24" style="background: var(--color-cream);">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- About Section -->
                    @if($networkMember->bio)
                    <div class="pma-card p-6 pma-animate-on-scroll">
                        <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">
                            {{ $networkMember->type === 'individual' ? 'About' : 'About Our Fellowship' }}
                        </h2>
                        <p class="pma-body whitespace-pre-line" style="color: var(--color-olive);">{{ $networkMember->bio }}</p>
                    </div>
                    @endif

                    <!-- Meeting Times (Groups Only) -->
                    @if($networkMember->type === 'group' && $networkMember->meeting_times)
                    <div class="pma-card p-6 pma-animate-on-scroll pma-stagger-1">
                        <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">
                            <svg class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Meeting Times
                        </h2>
                        <p class="pma-body whitespace-pre-line" style="color: var(--color-olive);">{{ $networkMember->meeting_times }}</p>
                    </div>
                    @endif

                    <!-- Location Map -->
                    <div class="pma-card p-6 pma-animate-on-scroll pma-stagger-2">
                        <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">
                            <svg class="h-6 w-6 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Location
                        </h2>
                        @if($networkMember->address)
                        <p class="pma-body mb-4" style="color: var(--color-olive);">{{ $networkMember->address }}</p>
                        @endif
                        <div id="profile-map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Contact Information -->
                    <div class="pma-card p-6 pma-animate-on-scroll pma-stagger-3">
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Contact Information</h3>

                        @if($networkMember->show_email)
                        <div class="mb-4">
                            <div class="flex items-center mb-1">
                                <svg class="h-5 w-5 mr-2" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="pma-body text-sm font-medium" style="color: var(--color-olive);">Email</span>
                            </div>
                            <a href="mailto:{{ $networkMember->email }}" class="pma-body text-sm" style="color: var(--color-pma-green);">
                                {{ $networkMember->email }}
                            </a>
                        </div>
                        @endif

                        @if($networkMember->show_phone && $networkMember->phone)
                        <div class="mb-4">
                            <div class="flex items-center mb-1">
                                <svg class="h-5 w-5 mr-2" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="pma-body text-sm font-medium" style="color: var(--color-olive);">Phone</span>
                            </div>
                            <a href="tel:{{ $networkMember->phone }}" class="pma-body text-sm" style="color: var(--color-pma-green);">
                                {{ $networkMember->phone }}
                            </a>
                        </div>
                        @endif

                        @if(!$networkMember->show_email && !$networkMember->show_phone)
                        <p class="pma-body text-sm" style="color: var(--color-olive);">
                            Contact information is not publicly available. You can find them on the network map.
                        </p>
                        @endif
                    </div>

                    <!-- Languages -->
                    @if($networkMember->languages->count() > 0)
                    <div class="pma-card p-6 pma-animate-on-scroll pma-stagger-4">
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Languages</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($networkMember->languages as $language)
                            <span class="inline-block px-3 py-1 rounded-full text-sm"
                                  style="background: var(--color-olive-light); color: white;">
                                {{ $language->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Back to Network Button -->
                    <div class="pma-animate-on-scroll pma-stagger-5">
                        <a href="{{ route('network.index') }}" class="pma-btn pma-btn-secondary w-full text-center">
                            <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Network Map
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize profile map
    const profileMap = L.map('profile-map').setView([{{ $networkMember->latitude }}, {{ $networkMember->longitude }}], 13);

    // Add CartoDB Voyager tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors © CARTO',
        subdomains: 'abcd',
        maxZoom: 20,
    }).addTo(profileMap);

    // Create custom marker
    const icon = L.divIcon({
        html: `<div style="background: {{ $networkMember->type === 'individual' ? 'var(--color-indigo)' : 'var(--color-pma-green)' }};
             color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
             font-weight: bold; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.3); font-size: 20px;">
            {{ $networkMember->type === 'individual' ? '=d' : 'ê' }}
        </div>`,
        className: 'custom-marker-icon',
        iconSize: [40, 40],
        iconAnchor: [20, 20],
    });

    // Add marker
    L.marker([{{ $networkMember->latitude }}, {{ $networkMember->longitude }}], { icon: icon })
        .addTo(profileMap)
        .bindPopup(`<strong>{{ $networkMember->name }}</strong>`);

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
