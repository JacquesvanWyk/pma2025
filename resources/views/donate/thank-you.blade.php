@extends('layouts.public')

@section('title', 'Thank You - Pioneer Missions Africa')
@section('description', 'Thank you for your generous donation to Pioneer Missions Africa.')

@section('content')
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center"
                 style="background: var(--color-pma-green);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="pma-hero-title pma-display text-white mb-6">
                Thank You!
            </h1>

            <p class="pma-hero-subtitle pma-accent text-white/90 mb-8">
                Your generous donation has been received. May God bless you abundantly for your partnership in spreading the Everlasting Gospel.
            </p>

            @if($reference)
                <p class="text-white/70 text-sm mb-8">
                    Reference: {{ $reference }}
                </p>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="pma-btn pma-btn-primary inline-flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Return Home
                </a>
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-outline inline-flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Donate Again
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-16" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto text-center">
            <div class="pma-quote" style="background: rgba(255, 255, 255, 0.5); border-color: var(--color-pma-green);">
                <p class="pma-quote-text" style="color: var(--color-indigo);">
                    "Every good gift and every perfect gift is from above, and cometh down from the Father of lights."
                </p>
                <p class="mt-4 pma-body font-semibold" style="color: var(--color-pma-green);">
                    — James 1:17
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
