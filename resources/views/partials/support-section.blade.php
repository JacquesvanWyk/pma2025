<!-- Support Section -->
<section class="py-20 lg:py-32 relative overflow-hidden" style="background: var(--gradient-hero);">
    <!-- Geometric Light Rays Background -->
    <div class="pma-light-rays"></div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4" style="background: var(--gradient-warm);"></div>
            <h2 class="pma-section-title pma-heading text-white mb-4">Support Our Mission</h2>
            <p class="pma-body text-lg mb-12 max-w-3xl mx-auto text-white/90">
                Your support helps us spread the Everlasting Gospel across Africa.
                Join us in this vital work of proclaiming present truth.
            </p>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
                <div class="rounded-3xl shadow-xl border backdrop-blur-lg bg-white/10 border-white/20 p-8 pma-animate-on-scroll pma-stagger-1">
                    <h3 class="pma-heading text-2xl text-white mb-3">One-Time Gift</h3>
                    <p class="pma-body text-white/80 mb-6">
                        Make a single donation to support our ministry
                    </p>
                    <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary w-full text-center">
                        Give Now
                    </a>
                </div>

                <div class="rounded-3xl shadow-xl border backdrop-blur-lg bg-white/20 border-white/40 p-8 pma-animate-on-scroll pma-stagger-2 relative">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="px-4 py-1 rounded-full text-xs pma-heading"
                              style="background: var(--gradient-warm); color: white;">
                            RECOMMENDED
                        </span>
                    </div>
                    <h3 class="pma-heading text-2xl text-white mb-3">Monthly Pledge</h3>
                    <p class="pma-body text-white/80 mb-6">
                        Commit to regular support of our mission
                    </p>
                    <a href="{{ route('donate') }}" class="pma-btn pma-btn-primary w-full text-center">
                        Pledge Monthly
                    </a>
                </div>

                <div class="rounded-3xl shadow-xl border backdrop-blur-lg bg-white/10 border-white/20 p-8 pma-animate-on-scroll pma-stagger-3">
                    <h3 class="pma-heading text-2xl text-white mb-3">Partner With Us</h3>
                    <p class="pma-body text-white/80 mb-6">
                        Join us as a ministry partner
                    </p>
                    <a href="{{ route('partner') }}" class="pma-btn pma-btn-secondary w-full text-center bg-transparent border-2 border-white text-white hover:bg-white hover:text-[var(--color-pma-green)] transition-colors">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Testimonial -->
            <div class="max-w-4xl mx-auto pma-animate-on-scroll">
                <div class="pma-quote" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2);">
                    <p class="pma-quote-text text-white/95">
                        "This ministry has been a blessing to our home church. The resources and support
                        have helped us grow in our understanding of God's truth."
                    </p>
                    <cite class="pma-body block text-right" style="color: var(--color-gold-light);">
                        — Brother John, Johannesburg
                    </cite>
                </div>
            </div>
        </div>
    </div>
</section>
