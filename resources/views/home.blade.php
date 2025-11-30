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
                    <h1 class="text-5xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight">
                        Proclaiming the <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--color-pma-green-light)] to-[var(--color-ochre-light)]">Everlasting Gospel</span> in Africa and Beyond
                    </h1>
                    
                    <p class="text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        We are a faith-based community committed to sharing the truth of the only true God and His Son, Jesus Christ.
                        Together, we strive to uplift communities, strengthen faith, and spread hope in every corner of Africa.
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

                <!-- Right Column: Support Card -->
                <div class="lg:col-span-5 mt-12 lg:mt-0 relative perspective-1000">
                    <!-- Glass Card -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl transform transition-transform hover:scale-[1.02] duration-500">
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-gradient-to-br from-[var(--color-ochre)] to-[var(--color-terracotta)] rounded-2xl rotate-12 opacity-80 blur-md -z-10"></div>

                        <h3 class="text-white text-2xl font-bold mb-4 flex items-center gap-3">
                            <span class="p-2 rounded-lg bg-[var(--color-pma-green)]/20 text-[var(--color-pma-green-light)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </span>
                            Monthly Pledge Goal
                        </h3>

                        <!-- Month Badge -->
                        <div class="text-center mb-4">
                            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[var(--color-pma-green)] text-white">
                                {{ $pledgeMonth }} Progress
                            </span>
                        </div>

                        <!-- Current Amount -->
                        <div class="text-center mb-4">
                            <span class="text-4xl font-bold text-[var(--color-ochre-light)]">R{{ number_format($currentPledges, 0) }}</span>
                            <span class="text-gray-400 text-sm ml-2">of R{{ number_format($pledgeGoal, 0) }}</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="relative h-3 bg-white/10 rounded-full overflow-hidden mb-2">
                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-[var(--color-pma-green)] to-[var(--color-pma-green-light)] rounded-full transition-all duration-1000 pledge-progress-bar"
                                 style="width: 0%"
                                 data-target="{{ min($pledgePercentage, 100) }}"></div>
                            <!-- Shimmer effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shimmer"></div>
                        </div>

                        <!-- Percentage -->
                        <p class="text-center text-[var(--color-pma-green-light)] font-semibold mb-6">
                            {{ number_format($pledgePercentage, 1) }}% Complete
                        </p>

                        <div class="border-t border-white/10 pt-6 mb-6">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-gray-300 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-pma-green-light)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Spread the Gospel across Africa</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-pma-green-light)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Create free biblical resources</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-pma-green-light)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Support kingdom fellowship</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('donate') }}" class="block w-full py-3 bg-white text-[var(--color-indigo)] font-bold rounded-xl text-center hover:bg-gray-100 transition-colors shadow-lg">
                            Give Today
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

                <!-- Featured Big Item (Random Study) -->
                @if($featuredStudy)
                    <div class="md:col-span-2 md:row-span-2 group relative overflow-hidden rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
                        @if($featuredStudy->featured_image)
                            <img src="{{ asset('storage/' . $featuredStudy->featured_image) }}" alt="{{ $featuredStudy->title }}" class="absolute inset-0 w-full h-full object-cover z-0 group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 bg-[var(--color-indigo)] z-0">
                                <div class="w-full h-full opacity-40 mix-blend-overlay bg-[url('/images/pattern-bg.png')] bg-cover"></div>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-10"></div>

                        <div class="relative z-20 h-full flex flex-col justify-end p-8">
                            <span class="inline-block px-3 py-1 rounded-full bg-[var(--color-pma-green)] text-white text-xs font-bold mb-3 w-fit">
                                Featured Study
                            </span>
                            <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-[var(--color-ochre-light)] transition-colors">
                                {{ $featuredStudy->title }}
                            </h3>
                            <p class="text-gray-300 mb-6 line-clamp-2">{{ $featuredStudy->excerpt }}</p>
                            <a href="{{ route('studies.show', $featuredStudy->slug) }}" class="inline-flex items-center text-white font-semibold hover:gap-2 transition-all">
                                Read Now <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Resource Stats Card -->
                <div class="md:col-span-1 md:row-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-[var(--color-pma-green)] transition-colors group flex flex-col">
                    <div class="w-12 h-12 rounded-xl bg-[var(--color-pma-green)]/10 text-[var(--color-pma-green)] flex items-center justify-center mb-6 group-hover:bg-[var(--color-pma-green)] group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-indigo)] mb-2">Free Resources</h3>
                    <p class="text-gray-500 text-sm mb-6 flex-grow">Download our collection of biblical resources.</p>
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">E-Books</span>
                            <span class="font-bold text-[var(--color-indigo)]">{{ $resourceCounts['ebooks'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tracts</span>
                            <span class="font-bold text-[var(--color-indigo)]">{{ $resourceCounts['tracts'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Study Notes</span>
                            <span class="font-bold text-[var(--color-indigo)]">{{ $resourceCounts['notes'] }}</span>
                        </div>
                    </div>
                    <a href="{{ route('resources') }}" class="mt-auto w-full py-2 rounded-lg border border-[var(--color-pma-green)] text-[var(--color-pma-green)] font-semibold text-sm flex items-center justify-center hover:bg-[var(--color-pma-green)] hover:text-white transition-colors">
                        Browse All
                    </a>
                </div>

                <!-- Latest Sermon with Thumbnail -->
                <div class="md:col-span-1 md:row-span-1 bg-[var(--color-indigo)] rounded-3xl shadow-lg relative overflow-hidden group">
                    @if($latestSermon && $latestSermon['thumbnail'])
                        <img src="{{ $latestSermon['thumbnail'] }}" alt="{{ $latestSermon['title'] }}" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-50 transition-opacity">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-indigo)] via-[var(--color-indigo)]/70 to-transparent"></div>
                    <div class="relative z-10 p-6 h-full flex flex-col justify-between">
                        <div>
                            <h3 class="text-white font-bold text-lg mb-1">Latest Sermon</h3>
                            @if($latestSermon)
                                <p class="text-white/70 text-xs line-clamp-2">{{ $latestSermon['title'] }}</p>
                            @endif
                        </div>
                        <a href="{{ route('sermons') }}" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white hover:text-[var(--color-indigo)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Prayer Room -->
                <div class="md:col-span-1 md:row-span-1 bg-[#f8f5f2] rounded-3xl p-6 shadow-sm border border-[#e8e1d5] hover:border-[var(--color-ochre)] transition-colors flex flex-col justify-between">
                    <div>
                        <h3 class="text-[var(--color-indigo)] font-bold text-lg mb-1">Prayer Room</h3>
                        @if($upcomingPrayerSession)
                            <p class="text-[var(--color-ochre)] text-xs font-semibold">{{ $upcomingPrayerSession->title }}</p>
                            <p class="text-gray-500 text-xs">{{ $upcomingPrayerSession->session_date->format('D, j M @ g:i A') }}</p>
                        @else
                            <p class="text-gray-500 text-xs">Join us in prayer</p>
                        @endif
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('prayer-room.index') }}" class="text-[var(--color-ochre)] hover:text-[var(--color-ochre-dark)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </a>
                    </div>
                </div>

                <!-- English E-Book -->
                @if($englishEbook)
                <div class="md:col-span-1 md:row-span-1 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold text-[var(--color-ochre)] bg-[var(--color-ochre)]/10 px-2 py-1 rounded">E-Book</span>
                        <span class="text-xs text-gray-400">English</span>
                    </div>
                    <h3 class="font-bold text-[var(--color-indigo)] mb-2 line-clamp-2 group-hover:text-[var(--color-pma-green)] transition-colors">
                        {{ $englishEbook->title }}
                    </h3>
                    <p class="text-xs text-gray-500 mb-3 flex-grow">{{ $englishEbook->author ?? 'Free Download' }}</p>
                    <a href="{{ route('resources.ebooks') }}#english" class="text-xs font-semibold text-[var(--color-pma-green)] hover:underline">
                        View All English E-Books →
                    </a>
                </div>
                @endif

                <!-- Afrikaans E-Book -->
                @if($afrikaansEbook)
                <div class="md:col-span-1 md:row-span-1 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold text-[var(--color-ochre)] bg-[var(--color-ochre)]/10 px-2 py-1 rounded">E-Book</span>
                        <span class="text-xs text-gray-400">Afrikaans</span>
                    </div>
                    <h3 class="font-bold text-[var(--color-indigo)] mb-2 line-clamp-2 group-hover:text-[var(--color-pma-green)] transition-colors">
                        {{ $afrikaansEbook->title }}
                    </h3>
                    <p class="text-xs text-gray-500 mb-3 flex-grow">{{ $afrikaansEbook->author ?? 'Gratis Aflaai' }}</p>
                    <a href="{{ route('resources.ebooks') }}#afrikaans" class="text-xs font-semibold text-[var(--color-pma-green)] hover:underline">
                        Sien Alle Afrikaanse E-Boeke →
                    </a>
                </div>
                @endif

                <!-- Random Gallery -->
                @if($randomGallery)
                <div class="md:col-span-1 md:row-span-1 rounded-3xl shadow-lg relative overflow-hidden group">
                    @if($randomGallery->cover_image)
                        <img src="{{ asset('storage/' . $randomGallery->cover_image) }}" alt="{{ $randomGallery->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-pma-green)] to-[var(--color-pma-green-dark)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="relative z-10 p-6 h-full flex flex-col justify-between min-h-[200px]">
                        <span class="inline-block px-2 py-1 rounded bg-white/20 text-white text-xs font-bold w-fit backdrop-blur-sm">
                            Gallery
                        </span>
                        <div>
                            <h3 class="text-white font-bold text-lg mb-1 line-clamp-2">{{ $randomGallery->title }}</h3>
                            <a href="{{ route('gallery.show', $randomGallery->slug) }}" class="text-xs font-semibold text-white/80 hover:text-white">
                                View Gallery →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Random Picture Study -->
                @if($randomPictureStudy)
                <div class="md:col-span-1 md:row-span-1 rounded-3xl shadow-lg relative overflow-hidden group">
                    @if($randomPictureStudy->thumbnail_path ?? $randomPictureStudy->image_path)
                        <img src="{{ asset('storage/' . ($randomPictureStudy->thumbnail_path ?? $randomPictureStudy->image_path)) }}" alt="{{ $randomPictureStudy->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-ochre)] to-[var(--color-terracotta)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="relative z-10 p-6 h-full flex flex-col justify-between min-h-[200px]">
                        <span class="inline-block px-2 py-1 rounded bg-white/20 text-white text-xs font-bold w-fit backdrop-blur-sm">
                            Picture Study
                        </span>
                        <div>
                            <h3 class="text-white font-bold text-lg mb-1 line-clamp-2">{{ $randomPictureStudy->title }}</h3>
                            <a href="{{ route('resources.picture-studies') }}" class="text-xs font-semibold text-white/80 hover:text-white">
                                View All Picture Studies →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

    <!-- Original Sections included below -->
    @include('partials.sermons-section')
    @include('partials.resources-section')

    <!-- Connect With Us Section -->
    <section class="py-20 lg:py-28 relative overflow-hidden" style="background: linear-gradient(135deg, var(--color-indigo-dark) 0%, var(--color-indigo) 100%);">
        <!-- Background decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[var(--color-pma-green)] opacity-10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[var(--color-ochre)] opacity-10 rounded-full blur-[120px]"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-pma-green)] animate-pulse"></span>
                    Stay Connected
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Join Our Community</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Connect with fellow believers, receive ministry updates, and stay informed about our latest resources and events.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- WhatsApp Card -->
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 text-center hover:bg-white/15 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-[#25D366] flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">WhatsApp Group</h3>
                    <p class="text-gray-300 text-sm mb-6">
                        Join our WhatsApp community for ministry news, prayer requests, and fellowship with believers across Africa.
                    </p>
                    <a href="https://chat.whatsapp.com/FtjaXDiw5xtJZKuxYdfnYS" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#25D366] hover:bg-[#20BD5A] text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-[#25D366]/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Join WhatsApp Group
                    </a>
                </div>

                <!-- Facebook Card -->
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 text-center hover:bg-white/15 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-[#1877F2] flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Facebook Page</h3>
                    <p class="text-gray-300 text-sm mb-6">
                        Follow us on Facebook for updates, testimonies, and inspirational content from our ministry.
                    </p>
                    <div class="flex justify-center mb-4">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fpioneermissionsafrica&tabs&width=280&height=130&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=434356118662900"
                                width="280" height="130"
                                style="border:none;overflow:hidden;border-radius:12px;"
                                scrolling="no" frameborder="0"
                                allowfullscreen="true"
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

        @include('partials.support-section')
        @include('partials.newsletter-section')
    
    @endsection
    
@push('styles')
<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
    .pledge-progress-bar {
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate pledge progress bar
    setTimeout(function() {
        const progressBar = document.querySelector('.pledge-progress-bar');
        if (progressBar) {
            const target = progressBar.dataset.target;
            progressBar.style.width = target + '%';
        }
    }, 300);

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

});
</script>
@endpush
    