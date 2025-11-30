<!-- Resources Section -->
<section class="py-20 lg:py-32 relative" style="background: white;">
    <!-- Cross Pattern Overlay -->
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="pma-section-header pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Ministry Resources</h2>
            <p class="pma-section-subtitle pma-body">
                Download free resources to support your spiritual journey
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 max-w-5xl mx-auto">
            <a href="{{ route('studies') }}"
               class="pma-card text-center p-6 lg:p-8 group pma-animate-on-scroll pma-stagger-1">
                <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-4 rounded-full flex items-center justify-center transition-all"
                     style="background: linear-gradient(135deg, var(--color-indigo-light), var(--color-indigo));">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 lg:h-10 lg:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="pma-heading text-lg lg:text-xl mb-2" style="color: var(--color-indigo);">Studies</h3>
                <p class="pma-body text-sm" style="color: var(--color-olive);">Bible studies</p>
            </a>

            <a href="{{ route('resources.tracts') }}"
               class="pma-card text-center p-6 lg:p-8 group pma-animate-on-scroll pma-stagger-2">
                <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-4 rounded-full flex items-center justify-center transition-all"
                     style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 lg:h-10 lg:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-lg lg:text-xl mb-2" style="color: var(--color-indigo);">Tracts</h3>
                <p class="pma-body text-sm" style="color: var(--color-olive);">Gospel literature</p>
            </a>

            <a href="{{ route('resources.ebooks') }}"
               class="pma-card text-center p-6 lg:p-8 group pma-animate-on-scroll pma-stagger-3">
                <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-4 rounded-full flex items-center justify-center transition-all"
                     style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 lg:h-10 lg:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="pma-heading text-lg lg:text-xl mb-2" style="color: var(--color-indigo);">E-Books</h3>
                <p class="pma-body text-sm" style="color: var(--color-olive);">Digital books</p>
            </a>

            <a href="{{ route('resources.picture-studies') }}"
               class="pma-card text-center p-6 lg:p-8 group pma-animate-on-scroll pma-stagger-4">
                <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-4 rounded-full flex items-center justify-center transition-all"
                     style="background: linear-gradient(135deg, var(--color-ochre-light), var(--color-ochre));">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 lg:h-10 lg:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-lg lg:text-xl mb-2" style="color: var(--color-indigo);">Picture Studies</h3>
                <p class="pma-body text-sm" style="color: var(--color-olive);">Visual infographics</p>
            </a>
        </div>
    </div>
</section>
