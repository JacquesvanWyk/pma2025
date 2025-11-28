@extends('layouts.public')

@php $mainClass = 'pt-0'; @endphp

@section('content')
    <!-- Hero Section - Cinematic Mission-First -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-[var(--color-indigo-dark)]">
        <!-- Background Elements -->
        <div class="pma-light-rays"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[var(--color-pma-green)] opacity-20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[var(--color-ochre)] opacity-10 rounded-full blur-[120px]" style="animation-delay: 2s;"></div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60 z-0"></div>

        <!-- Content Container -->
        <div class="container mx-auto px-6 pt-32 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-20 items-center">
                <!-- Left Column: Mission Statement -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium mb-8 animate-fade-in-up">
                        <span class="w-2 h-2 rounded-full bg-[var(--color-pma-green)] animate-pulse"></span>
                        Proclaiming the Everlasting Gospel
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight">
                        Connecting Africa to the <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--color-pma-green-light)] to-[var(--color-ochre-light)]">Knowledge of God</span>
                    </h1>
                    
                    <p class="text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        We are a ministry dedicated to spreading the truth of the one true God and His Son, Jesus Christ, supporting spiritual growth through biblical resources and fellowship.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('studies') }}" class="px-8 py-4 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg shadow-[var(--color-pma-green)]/30 transition-all hover:-translate-y-1 hover:shadow-xl flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Start a Study
                        </a>
                        <a href="{{ route('sermons') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-semibold backdrop-blur-md transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Watch Latest Sermon
                        </a>
                    </div>
                </div>

                <!-- Right Column: Floating Pledge Card -->
                <div class="lg:col-span-5 mt-12 lg:mt-0 relative perspective-1000">
                    <!-- Glass Card -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl transform transition-transform hover:scale-[1.02] duration-500">
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-gradient-to-br from-[var(--color-ochre)] to-[var(--color-terracotta)] rounded-2xl rotate-12 opacity-80 blur-md -z-10"></div>
                        
                        <h3 class="text-white text-2xl font-bold mb-6 flex items-center gap-3">
                            <span class="p-2 rounded-lg bg-[var(--color-pma-green)]/20 text-[var(--color-pma-green-light)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </span>
                            Monthly Pledge
                        </h3>

                        <!-- Circular Progress or Bar -->
                        <div class="mb-8">
                            <div class="flex justify-between items-end mb-2 text-white">
                                <span class="text-sm opacity-80">Current Goal</span>
                                <span class="text-2xl font-bold text-[var(--color-pma-green-light)]">{{ number_format($pledgePercentage, 0) }}%</span>
                            </div>
                            <div class="h-4 bg-white/10 rounded-full overflow-hidden relative">
<div id="pledge-progress-bar" class="absolute inset-0 bg-[var(--color-pma-green)] h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $pledgePercentage }}%">
                                    <div class="absolute inset-0 bg-white/30 animate-[shimmer_2s_infinite]"></div>
                                </div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-400 font-medium">
                                <span>R{{ number_format($currentPledges, 0) }} Raised</span>
                                <span>Goal: R{{ number_format($pledgeGoal, 0) }}</span>
                            </div>
                        </div>

                        <p class="text-gray-300 text-sm mb-8 leading-relaxed">
                            Your monthly support helps us reach more souls with the message of the Kingdom. Join our family of supporters today.
                        </p>

                        <a href="{{ route('pledge') }}" class="block w-full py-3 bg-white text-[var(--color-indigo)] font-bold rounded-xl text-center hover:bg-gray-100 transition-colors shadow-lg">
                            Support the Ministry
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>

    <!-- Bento Grid: Discover Content -->
    <section class="py-24 bg-[var(--color-cream)]" id="discover">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[var(--color-ochre)] font-semibold uppercase tracking-widest text-sm mb-2 block">Discover Truth</span>
                <h2 class="text-4xl font-bold text-[var(--color-indigo)] mb-4">Latest from the Ministry</h2>
                <div class="w-20 h-1 bg-[var(--color-pma-green)] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 auto-rows-[minmax(200px,auto)]">
                
                <!-- Featured Big Item (Latest Study) -->
                @if($featuredStudies->count() > 0)
                    @php $firstStudy = $featuredStudies->first(); @endphp
                    <div class="md:col-span-2 md:row-span-2 group relative overflow-hidden rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-10"></div>
                        <!-- Fallback background if no image -->
                        <div class="absolute inset-0 bg-[var(--color-indigo)] z-0">
                             <div class="w-full h-full opacity-40 mix-blend-overlay bg-[url('/images/pattern-bg.png')] bg-cover"></div>
                        </div>
                        
                        <div class="relative z-20 h-full flex flex-col justify-end p-8">
                            <span class="inline-block px-3 py-1 rounded-full bg-[var(--color-pma-green)] text-white text-xs font-bold mb-3 w-fit">
                                Featured Study
                            </span>
                            <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-[var(--color-ochre-light)] transition-colors">
                                {{ $firstStudy->title }}
                            </h3>
                            <p class="text-gray-300 mb-6 line-clamp-2">{{ $firstStudy->excerpt }}</p>
                            <a href="{{ route('studies.show', $firstStudy->slug) }}" class="inline-flex items-center text-white font-semibold hover:gap-2 transition-all">
                                Read Now <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Secondary Item (Prayer Room) -->
                <div class="md:col-span-1 md:row-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-[var(--color-pma-green)] transition-colors group flex flex-col">
                    <div class="w-12 h-12 rounded-xl bg-[var(--color-pma-green)]/10 text-[var(--color-pma-green)] flex items-center justify-center mb-6 group-hover:bg-[var(--color-pma-green)] group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-indigo)] mb-2">Prayer Room</h3>
                    <p class="text-gray-500 text-sm mb-6 flex-grow">Join us monthly to pray for our communities.</p>
                    <div class="mt-auto">
                        <div class="text-xs font-bold text-[var(--color-ochre)] uppercase tracking-wide mb-1">Next Session</div>
                        <div class="font-bold text-[var(--color-indigo)]">Wed, 27 Aug 2025</div>
                        <a href="{{ route('prayer-room.index') }}" class="mt-4 w-full py-2 rounded-lg border border-[var(--color-pma-green)] text-[var(--color-pma-green)] font-semibold text-sm flex items-center justify-center hover:bg-[var(--color-pma-green)] hover:text-white transition-colors">
                            Join Us
                        </a>
                    </div>
                </div>

                <!-- Third Item (Sermons) -->
                <div class="md:col-span-1 md:row-span-1 bg-[var(--color-indigo)] rounded-3xl p-6 shadow-lg relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <h3 class="text-white font-bold text-lg mb-2 relative z-10">Latest Sermons</h3>
                    <p class="text-white/70 text-sm mb-4 relative z-10">Watch recent messages.</p>
                    <a href="{{ route('sermons') }}" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white hover:text-[var(--color-indigo)] transition-colors relative z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </a>
                </div>

                <!-- Fourth Item (Resources/Ebooks) -->
                <div class="md:col-span-1 md:row-span-1 bg-[#f8f5f2] rounded-3xl p-6 shadow-sm border border-[#e8e1d5] hover:border-[var(--color-ochre)] transition-colors flex flex-col justify-between">
                    <div>
                        <h3 class="text-[var(--color-indigo)] font-bold text-lg mb-1">Free Resources</h3>
                        <p class="text-gray-500 text-xs">E-books & Tracts</p>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('resources') }}" class="text-[var(--color-ochre)] hover:text-[var(--color-ochre-dark)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Fifth Item (More Studies) -->
                @if($featuredStudies->count() > 1)
                    @foreach($featuredStudies->skip(1)->take(2) as $study)
                    <div class="md:col-span-1 md:row-span-1 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-bold text-[var(--color-pma-green)] bg-[var(--color-pma-green)]/10 px-2 py-1 rounded">{{ $study->category->name ?? 'Study' }}</span>
                            <span class="text-xs text-gray-400">{{ $study->read_time ?? '5' }} min</span>
                        </div>
                        <h3 class="font-bold text-[var(--color-indigo)] mb-2 line-clamp-2 group-hover:text-[var(--color-pma-green)] transition-colors">
                            <a href="{{ route('studies.show', $study->slug) }}">{{ $study->title }}</a>
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-0">{{ $study->excerpt }}</p>
                    </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>

    <!-- Original Sections included below -->
    @include('partials.sermons-section')
    @include('partials.resources-section')
        @include('partials.support-section')
        @include('partials.newsletter-section')
    
    @endsection
    
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll Animation Observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    try {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const animatedElements = document.querySelectorAll('.pma-animate-on-scroll');
        if (animatedElements.length > 0) {
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        }
    } catch (e) {
        console.error('Animation Observer Error:', e);
        // Fallback: Make everything visible if observer fails
        document.querySelectorAll('.pma-animate-on-scroll').forEach(el => {
            el.classList.add('is-visible');
            el.style.opacity = 1;
            el.style.transform = 'none';
        });
    }

    // Progress Bar Animation
    try {
        const bar = document.getElementById('pledge-progress-bar');
        if (bar) {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = targetWidth;
            }, 100);
        }
    } catch (e) {
        console.warn('Progress bar animation error:', e);
    }
});
</script>
@endpush
    