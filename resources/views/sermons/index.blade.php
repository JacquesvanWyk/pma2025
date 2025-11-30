@extends('layouts.public')

@section('title', 'Sermons - Pioneer Missions Africa')
@section('description', 'Watch our collection of sermons proclaiming the Everlasting Gospel and present truth.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Sermons & Messages
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 italic mb-4">
                "So then faith cometh by hearing, and hearing by the word of God."
            </p>
            <p class="text-white/70 text-sm">— Romans 10:17</p>
        </div>
    </div>
</section>

<!-- Featured Sermon -->
@if($featuredVideo)
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <span class="inline-block px-6 py-2 rounded-full text-sm pma-heading mb-4"
                  style="background: var(--gradient-warm); color: white; letter-spacing: 0.05em;">
                FEATURED
            </span>
            <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Latest Message</h2>
        </div>

        <div class="max-w-5xl mx-auto pma-animate-on-scroll">
            <div class="pma-card-elevated">
                <div class="aspect-video w-full overflow-hidden rounded-t-2xl bg-gray-200">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $featuredVideo['id'] }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="p-8">
                    <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light mb-4"
                          style="background: var(--color-pma-green); color: white;">
                        Recent
                    </span>
                    <h3 class="pma-heading text-3xl mb-4" style="color: var(--color-indigo);">{!! $featuredVideo['title'] !!}</h3>
                    <p class="pma-body text-lg mb-3" style="color: var(--color-olive);">{!! $featuredVideo['channel_title'] !!}</p>
                    <p class="pma-body mb-4" style="color: var(--color-olive);">
                        {!! Str::limit($featuredVideo['description'], 200) !!}
                    </p>
                    <p class="pma-body text-sm mb-6" style="color: var(--color-ochre);">
                        Published: {{ \Carbon\Carbon::parse($featuredVideo['published_at'])->format('F j, Y') }}
                    </p>
                    <a href="https://www.youtube.com/watch?v={{ $featuredVideo['id'] }}" target="_blank"
                       class="pma-btn pma-btn-secondary inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Watch on YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Sermon Archive -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Sermon Archive</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Browse our collection of messages</p>
        </div>

        @if(count($archiveVideos) > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($archiveVideos as $index => $video)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="aspect-video w-full overflow-hidden rounded-t-lg bg-gray-200">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $video['id'] }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="p-6">
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">{!! $video['title'] !!}</h3>
                    <p class="pma-body text-sm mb-2" style="color: var(--color-olive);">{!! $video['channel_title'] !!}</p>
                    <p class="pma-body text-xs" style="color: var(--color-ochre);">
                        {{ \Carbon\Carbon::parse($video['published_at'])->format('F j, Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 pma-animate-on-scroll">
            <p class="pma-body text-lg" style="color: var(--color-olive);">No sermons available at the moment. Check back soon!</p>
        </div>
        @endif
    </div>
</section>

<!-- Sermon Series -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Sermon Series</h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">Follow along with our multi-part sermon series</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $series = [
                [
                    'title' => 'The Godhead Series',
                    'parts' => 6,
                    'description' => 'Understanding the Father, Son, and Holy Spirit',
                    'gradient' => 'linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green))'
                ],
                [
                    'title' => 'Prophecy Fulfilled',
                    'parts' => 8,
                    'description' => 'Examining Bible prophecy and current events',
                    'gradient' => 'linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark))'
                ],
                [
                    'title' => 'The Sanctuary Message',
                    'parts' => 5,
                    'description' => 'Christ\'s ministry in the heavenly sanctuary',
                    'gradient' => 'linear-gradient(135deg, var(--color-olive-light), var(--color-olive))'
                ],
            ];
            @endphp

            @foreach($series as $index => $item)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ $index + 1 }}">
                <div class="p-8">
                    <div class="w-full h-40 rounded-lg flex items-center justify-center mb-6"
                         style="background: {{ $item['gradient'] }};">
                        <div class="text-white text-center">
                            <div class="pma-display text-6xl mb-2">{{ $item['parts'] }}</div>
                            <div class="pma-heading text-lg opacity-90">Part Series</div>
                        </div>
                    </div>
                    <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">{{ $item['title'] }}</h3>
                    <p class="pma-body mb-6" style="color: var(--color-olive);">{{ $item['description'] }}</p>
                    <button class="pma-btn pma-btn-primary w-full">Watch Series</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Subscribe Section -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Subscribe to Our Channel</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Get notified when we upload new sermons and messages. Join our growing community on YouTube.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" target="_blank" class="pma-btn inline-flex items-center justify-center"
                   style="background: #FF0000; color: white;">
                    <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    Subscribe on YouTube
                </a>
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Support This Ministry
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Support CTA -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Help Us Reach More People</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Your support helps us continue producing and sharing these messages. Consider becoming a monthly partner.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary">
                    Support Our Ministry
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
