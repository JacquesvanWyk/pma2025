<!-- Newsletter Section -->
<section id="newsletter" class="py-20 lg:py-32" style="background: var(--color-cream);">
    <div class="container mx-auto px-6">
        <div class="pma-card-elevated max-w-4xl mx-auto p-8 lg:p-16 text-center pma-animate-on-scroll"
             style="background: var(--color-indigo);">
            <div class="pma-accent-line mx-auto mb-6" style="background: var(--gradient-warm);"></div>
            <h2 class="pma-heading text-4xl lg:text-5xl mb-6 text-white">Stay Connected</h2>
            <p class="pma-body text-lg mb-10 text-white/80 max-w-2xl mx-auto">
                Subscribe to our newsletter for updates, new studies, and ministry news
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-xl mx-auto w-full">
                @csrf
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email address"
                        required
                        class="flex-1 px-6 py-4 rounded-lg text-lg pma-body"
                        style="background: white; color: var(--color-indigo); border: 2px solid transparent;"
                    >
                    <button type="submit" class="pma-btn pma-btn-primary px-8 py-4 text-lg whitespace-nowrap">
                        Subscribe
                    </button>
                </div>
                <div class="flex justify-center gap-8 text-sm">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="language" value="en" checked
                               class="w-5 h-5 accent-current"
                               style="accent-color: var(--color-ochre);" />
                        <span class="pma-body text-white/90 group-hover:text-white transition-colors">English</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="language" value="af"
                               class="w-5 h-5"
                               style="accent-color: var(--color-ochre);" />
                        <span class="pma-body text-white/90 group-hover:text-white transition-colors">Afrikaans</span>
                    </label>
                </div>
            </form>
        </div>
    </div>
</section>
