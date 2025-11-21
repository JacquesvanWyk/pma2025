@extends('layouts.public')

@section('title', 'About Pioneer Missions Africa')
@section('description', 'Learn about Pioneer Missions Africa - a ministry focused on proclaiming the Everlasting Gospel and supporting groups across South Africa and Africa.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <!-- Geometric Light Rays Background -->
    <div class="pma-light-rays"></div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-6" style="background: var(--gradient-warm);"></div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                About Pioneer Missions Africa
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                We thank you for visiting Pioneer Missions Africa website and hope you will be blessed.
            </p>
        </div>
    </div>
</section>

<!-- Introduction -->
<section class="py-20 relative" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <p class="pma-body text-xl mb-8 leading-relaxed" style="color: var(--color-indigo);">
                We thank those who are supporting this ministry in South Africa. We are proclaiming the last message of warning to the people of our beloved country, calling them back to the worship of the one true God.
            </p>
        </div>
    </div>
</section>

<!-- Mission Cards -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-8 mb-16">
            <!-- Our Mission -->
            <div class="pma-card pma-animate-on-scroll pma-stagger-1">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h2 class="pma-heading text-3xl mb-4" style="color: var(--color-indigo);">Our Mission</h2>
                    <p class="pma-body text-lg leading-relaxed" style="color: var(--color-olive);">
                        Pioneer Missions Africa is dedicated to proclaiming the Everlasting Gospel and spreading knowledge of God that leads to eternal life. We support groups and individuals across South Africa and Africa through our media and publishing departments.
                    </p>
                </div>
            </div>

            <!-- What We Are -->
            <div class="pma-card pma-animate-on-scroll pma-stagger-2">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="pma-heading text-3xl mb-4" style="color: var(--color-indigo);">What We Are</h2>
                    <p class="pma-body text-lg leading-relaxed mb-4 font-semibold" style="color: var(--color-pma-green);">
                        PMA is not an organisation, it is not a conference, it is not a church—IT IS A MINISTRY.
                    </p>
                    <p class="pma-body text-lg leading-relaxed" style="color: var(--color-olive);">
                        Individuals and groups answer to Christ, not the ministry itself. We are here to support and encourage believers in their walk with God.
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Health & Wellbeing -->
            <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-3">
                <div class="p-8">
                    <h3 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Health & Wellbeing</h3>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        We value both spiritual and physical health through a biblical Christian health model, recognizing that our bodies are temples of the Holy Spirit.
                    </p>
                </div>
            </div>

            <!-- Finances -->
            <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-4">
                <div class="p-8">
                    <h3 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Finances</h3>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        Donations are appreciated but not required. We understand all groups need resources for the Great Commission, and we trust God to provide through those He impresses to support this work.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inspirational Quote -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto pma-animate-on-scroll">
            <div class="pma-quote" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2);">
                <p class="pma-quote-text text-white/95 text-2xl">
                    We are excited about what God is doing world wide, and especially in Africa. Let us worship Him in Spirit and in Truth.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <div class="text-center mb-12">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Get in Touch</h2>
            </div>

            <div class="pma-card-elevated">
                <div class="p-8 lg:p-12">
                    <div class="grid md:grid-cols-3 gap-8 mb-8">
                        <!-- Phone -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center"
                                 style="background: var(--color-cream-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <p class="pma-body font-semibold mb-2" style="color: var(--color-indigo);">Phone</p>
                            <p class="pma-body text-sm" style="color: var(--color-olive);">0794703941<br>0634698313</p>
                        </div>

                        <!-- Email -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center"
                                 style="background: var(--color-cream-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="pma-body font-semibold mb-2" style="color: var(--color-indigo);">Email</p>
                            <a href="mailto:info@pioneermissionsafrica.co.za" class="pma-body text-sm hover:underline" style="color: var(--color-pma-green);">
                                info@pioneermissionsafrica.co.za
                            </a>
                        </div>

                        <!-- Location -->
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center"
                                 style="background: var(--color-cream-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <p class="pma-body font-semibold mb-2" style="color: var(--color-indigo);">Location</p>
                            <p class="pma-body text-sm" style="color: var(--color-olive);">South Africa</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('contact') }}" class="pma-btn pma-btn-primary inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Send Us a Message
                        </a>
                    </div>
                </div>
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
