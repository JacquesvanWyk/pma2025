@extends('layouts.public')

@section('title', 'Picture Studies - Resources - Pioneer Missions Africa')
@section('description', 'Download free infographic picture studies for evangelism and Bible study. Visual aids to help share the everlasting gospel.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Picture Studies
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "Write the vision, and make it plain upon tables, that he may run that readeth it."
            </p>
            <p class="text-white/70 text-sm">— Habakkuk 2:2</p>
        </div>
    </div>
</section>

<!-- Livewire Volt Component for reactive filtering -->
<livewire:pages.resources.picture-studies />

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto">
            <h2 class="pma-hero-title pma-display text-white mb-6">Support This Ministry</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Help us continue creating free visual resources for evangelism
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

@endsection
