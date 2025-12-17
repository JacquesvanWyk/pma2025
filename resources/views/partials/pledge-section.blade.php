{{-- Monthly Pledge Section --}}
<section class="py-16 bg-gradient-to-br from-[var(--color-indigo)] to-[var(--color-indigo-dark)] relative overflow-hidden">
    {{-- Background decorations --}}
    <div class="absolute top-0 right-0 w-72 h-72 bg-[var(--color-pma-green)] opacity-10 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-[var(--color-ochre)] opacity-10 rounded-full blur-[120px]"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                {{-- Left: Text Content --}}
                <div class="text-center md:text-left">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--color-pma-green)] text-white mb-4">
                        {{ $pledgeMonth }} Goal
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                        Support the Mission
                    </h2>
                    <p class="text-gray-300 mb-6">
                        Your faithful giving helps us spread the Gospel across Africa, create free biblical resources, and support kingdom fellowship.
                    </p>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-gray-300 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green-light)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Spread the Gospel across Africa</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-300 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green-light)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Create free biblical resources</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-300 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green-light)] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Support kingdom fellowship</span>
                        </div>
                    </div>

                    <a href="{{ route('donate') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--color-ochre)] hover:bg-[var(--color-ochre-light)] text-white font-semibold rounded-xl transition-all hover:-translate-y-1 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Give Today
                    </a>
                </div>

                {{-- Right: Progress Card --}}
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 shadow-xl">
                    <h3 class="text-white text-xl font-bold mb-4 flex items-center gap-3">
                        <span class="p-2 rounded-lg bg-[var(--color-pma-green)]/20 text-[var(--color-pma-green-light)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </span>
                        Monthly Pledge Progress
                    </h3>

                    {{-- Current Amount --}}
                    <div class="text-center mb-4">
                        <span class="text-4xl font-bold text-[var(--color-ochre-light)]">R{{ number_format($currentPledges, 0) }}</span>
                        <span class="text-gray-400 text-sm ml-2">of R{{ number_format($pledgeGoal, 0) }}</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="relative h-3 bg-white/10 rounded-full overflow-hidden mb-2">
                        <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-[var(--color-pma-green)] to-[var(--color-pma-green-light)] rounded-full transition-all duration-1000 pledge-progress-bar"
                             style="width: 0%"
                             data-target="{{ min($pledgePercentage, 100) }}"></div>
                        {{-- Shimmer effect --}}
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shimmer"></div>
                    </div>

                    {{-- Percentage --}}
                    <p class="text-center text-[var(--color-pma-green-light)] font-semibold">
                        {{ number_format($pledgePercentage, 1) }}% Complete
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
