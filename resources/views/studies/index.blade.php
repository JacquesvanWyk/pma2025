@extends('layouts.public')

@section('title', 'Bible Studies - Pioneer Missions Africa')
@section('description', 'Explore our comprehensive Bible studies covering Pioneer teachings, Ellen White, Evangelism, and more. Filter by topic, language, and search to find the perfect study.')

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
                Bible Studies
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 mb-8">
                Deepen your understanding of God's truth through our comprehensive study materials covering various aspects of biblical knowledge and pioneer teachings.
            </p>
            <button onclick="document.getElementById('filters').scrollIntoView({behavior: 'smooth'})"
                    class="pma-btn pma-btn-primary">
                Browse Studies
            </button>
        </div>
    </div>
</section>

<!-- Studies List Component -->
<livewire:studies-list />

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-section-title pma-heading mb-6" style="color: var(--color-indigo);">
                Can't Find What You're Looking For?
            </h2>
            <p class="pma-body text-lg mb-10" style="color: var(--color-olive);">
                We're constantly adding new study materials. Contact us to request a specific study topic
                or support our ministry to help us create more content.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-primary">
                    Request a Study
                </a>
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-secondary">
                    Support Our Ministry
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
