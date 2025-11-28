@extends('layouts.public')

@section('title', 'Contact Pioneer Missions Africa')
@section('description', 'Get in touch with Pioneer Missions Africa. Contact us for ministry inquiries, prayer requests, or partnership opportunities.')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Contact Us
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                We'd love to hear from you. Reach out for ministry inquiries, prayer requests, or partnership opportunities.
            </p>
        </div>
    </div>
</section>

<!-- Contact Form & Information -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-1">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h2 class="pma-heading text-3xl mb-8" style="color: var(--color-indigo);">Send Us a Message</h2>

                    @if(session('success'))
                    <div class="p-4 mb-4 rounded-lg" style="background: var(--color-pma-green-light); color: var(--color-pma-green);">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="p-4 mb-4 rounded-lg bg-red-100 text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                                Full Name
                            </label>
                            <input type="text" name="name" placeholder="John Doe"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                   style="focus:ring-color: var(--color-pma-green);"
                                   required>
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                                Email Address
                            </label>
                            <input type="email" name="email" placeholder="john@example.com"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                   style="focus:ring-color: var(--color-pma-green);"
                                   required>
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                                Phone Number
                            </label>
                            <input type="tel" name="phone" placeholder="0794703941"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                                Message Type
                            </label>
                            <select name="message_type"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                    style="focus:ring-color: var(--color-pma-green);">
                                <option value="general">General Inquiry</option>
                                <option value="prayer">Prayer Request</option>
                                <option value="partnership">Partnership Opportunity</option>
                                <option value="support">Technical Support</option>
                                <option value="resources">Resource Request</option>
                            </select>
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                                Your Message
                            </label>
                            <textarea name="message" rows="6" placeholder="Tell us how we can help you..."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body resize-none"
                                      style="focus:ring-color: var(--color-pma-green);"
                                      required></textarea>
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="prayer_request" id="prayer_request"
                                   class="mt-1 w-5 h-5 rounded border-gray-300 focus:ring-2"
                                   style="color: var(--color-pma-green); focus:ring-color: var(--color-pma-green);">
                            <label for="prayer_request" class="pma-body text-sm" style="color: var(--color-olive);">
                                I would like this to be included in prayer requests
                            </label>
                        </div>

                        <button type="submit" class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6 pma-animate-on-scroll pma-stagger-2">
                <div class="pma-card">
                    <div class="p-8">
                        <div class="w-12 h-12 mb-4 rounded-full flex items-center justify-center"
                             style="background: var(--color-cream-dark);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Phone Numbers</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:0794703941" class="pma-body hover:underline" style="color: var(--color-pma-green);">079 470 3941</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:0634698313" class="pma-body hover:underline" style="color: var(--color-pma-green);">063 469 8313</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pma-card">
                    <div class="p-8">
                        <div class="w-12 h-12 mb-4 rounded-full flex items-center justify-center"
                             style="background: var(--color-cream-dark);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Office Hours</h3>
                        <div class="space-y-3 pma-body" style="color: var(--color-olive);">
                            <div class="flex justify-between">
                                <span class="pma-heading-light">Monday - Friday:</span>
                                <span>9:00 AM - 5:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="pma-heading-light">Saturday:</span>
                                <span>Closed for Sabbath</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="pma-heading-light">Sunday:</span>
                                <span>10:00 AM - 2:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pma-card">
                    <div class="p-8">
                        <div class="w-12 h-12 mb-4 rounded-full flex items-center justify-center"
                             style="background: var(--color-cream-dark);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Location</h3>
                        <p class="pma-heading-light text-lg mb-2" style="color: var(--color-indigo);">South Africa</p>
                        <p class="pma-body" style="color: var(--color-olive);">Serving communities across Africa</p>
                    </div>
                </div>

                <div class="pma-card">
                    <div class="p-8">
                        <div class="w-12 h-12 mb-4 rounded-full flex items-center justify-center"
                             style="background: var(--color-cream-dark);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Connect With Us</h3>
                        <div class="flex gap-4">
                            <a href="https://www.facebook.com/pioneermissionsafrica" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all hover:scale-110"
                               style="border-color: var(--color-pma-green); color: var(--color-pma-green);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                            <a href="https://www.youtube.com/@pioneermissionsafrica3344" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all hover:scale-110"
                               style="border-color: var(--color-pma-green); color: var(--color-pma-green);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Join Our Mission</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Your support helps us continue spreading the Everlasting Gospel across Africa and beyond.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary">
                    Support Our Ministry
                </a>
                <a href="{{ route('about') }}" class="pma-btn pma-btn-secondary"
                   style="background: transparent; border: 2px solid white; color: white;">
                    Learn More About Us
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
