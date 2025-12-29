@extends('layouts.public')

@section('title', 'PMA Worship - Music')

@section('content')
    {{-- Hero Section --}}
    <section class="relative py-32 bg-gradient-to-br from-[#0a1a0a] via-[#0d2818] to-[#0a1a0a] overflow-hidden">
        {{-- Background effects --}}
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[var(--color-pma-green)] opacity-10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[var(--color-ochre)] opacity-5 rounded-full blur-[120px]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-pma-green)]/20 border border-[var(--color-pma-green)]/40 rounded-full mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    <span class="text-[var(--color-pma-green)] text-sm font-semibold uppercase tracking-wider">PMA Worship</span>
                </span>

                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6">
                    Listen to the Truth in Song
                </h1>

                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Worship music from Pioneer Missions Africa. Download and share freely.
                </p>
            </div>
        </div>
    </section>

    {{-- Featured Album --}}
    @if($featuredAlbum)
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Album Cover --}}
                <div class="relative group">
                    <div class="absolute inset-0 bg-[var(--color-pma-green)]/20 rounded-2xl blur-2xl transform scale-95 group-hover:scale-100 transition-transform duration-500"></div>
                    @if($featuredAlbum->cover_image)
                        <img src="{{ $featuredAlbum->cover_image_url }}"
                             alt="{{ $featuredAlbum->title }}"
                             class="relative w-full aspect-square object-cover rounded-2xl shadow-2xl">
                    @else
                        <div class="relative w-full aspect-square bg-gradient-to-br from-[var(--color-pma-green)] to-[var(--color-indigo)] rounded-2xl shadow-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-[var(--color-ochre)] text-white text-sm font-bold rounded-full">Featured</span>
                    </div>
                </div>

                {{-- Album Info --}}
                <div>
                    <span class="text-[var(--color-ochre)] font-semibold uppercase tracking-widest text-sm mb-2 block">Featured Album</span>
                    <h2 class="text-4xl lg:text-5xl font-bold text-[var(--color-indigo)] mb-4">{{ $featuredAlbum->title }}</h2>
                    <p class="text-xl text-[var(--color-pma-green)] font-semibold mb-4">{{ $featuredAlbum->artist }}</p>

                    @if($featuredAlbum->isReleased())
                        @if($featuredAlbum->release_date)
                            <p class="text-gray-600 mb-4">
                                Released {{ $featuredAlbum->release_date->format('F j, Y') }}
                            </p>
                        @endif
                    @else
                        {{-- Countdown for unreleased album --}}
                        @php $countdown = $featuredAlbum->getTimeUntilRelease(); @endphp
                        <div class="mb-6">
                            <p class="text-[var(--color-ochre)] font-semibold mb-3">Launching In</p>
                            <div class="countdown-timer flex gap-3" data-release="{{ $countdown['iso'] }}">
                                <div class="text-center">
                                    <div class="w-14 h-14 bg-[var(--color-pma-green)]/10 rounded-lg flex items-center justify-center">
                                        <span class="countdown-days text-xl font-bold text-[var(--color-pma-green)]">{{ $countdown['days'] }}</span>
                                    </div>
                                    <span class="text-gray-500 text-xs mt-1 block">Days</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-14 h-14 bg-[var(--color-pma-green)]/10 rounded-lg flex items-center justify-center">
                                        <span class="countdown-hours text-xl font-bold text-[var(--color-pma-green)]">{{ str_pad($countdown['hours'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-500 text-xs mt-1 block">Hours</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-14 h-14 bg-[var(--color-pma-green)]/10 rounded-lg flex items-center justify-center">
                                        <span class="countdown-minutes text-xl font-bold text-[var(--color-pma-green)]">{{ str_pad($countdown['minutes'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-500 text-xs mt-1 block">Min</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-14 h-14 bg-[var(--color-pma-green)]/10 rounded-lg flex items-center justify-center">
                                        <span class="countdown-seconds text-xl font-bold text-[var(--color-ochre)]">{{ str_pad($countdown['seconds'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-500 text-xs mt-1 block">Sec</span>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mt-3">
                                {{ $featuredAlbum->getReleaseDateTime()->format('F j, Y \a\t g:i A') }} (SA Time)
                            </p>
                        </div>
                    @endif

                    @if($featuredAlbum->description)
                        <div class="prose prose-lg text-gray-600 mb-6">
                            {!! $featuredAlbum->description !!}
                        </div>
                    @endif

                    {{-- Track Count --}}
                    <p class="text-gray-500 mb-6">
                        {{ $featuredAlbum->songs->count() }} {{ Str::plural('track', $featuredAlbum->songs->count()) }}
                    </p>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('music.show', $featuredAlbum->slug) }}"
                           class="px-6 py-3 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg transition-all hover:-translate-y-1 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @if($featuredAlbum->isReleased())
                                View Album
                            @else
                                Preview Album
                            @endif
                        </a>
                        @if($featuredAlbum->isReleased())
                            <a href="{{ route('music.show', $featuredAlbum->slug) }}"
                               class="px-6 py-3 bg-white/90 hover:bg-white text-[var(--color-pma-green)] border-2 border-[var(--color-pma-green)] rounded-xl font-semibold transition-all hover:-translate-y-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Album
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- All Albums --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="text-[var(--color-ochre)] font-semibold uppercase tracking-widest text-sm mb-2 block">Our Music</span>
                <h2 class="text-4xl font-bold text-[var(--color-indigo)] mb-4">All Albums</h2>
                <div class="w-20 h-1 bg-[var(--color-pma-green)] mx-auto rounded-full"></div>
            </div>

            @if($albums->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($albums as $album)
                        <a href="{{ route('music.show', $album->slug) }}"
                           class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                            {{-- Cover Image --}}
                            <div class="relative aspect-square overflow-hidden">
                                @if($album->cover_image)
                                    <img src="{{ $album->cover_image_url }}"
                                         alt="{{ $album->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[var(--color-pma-green)] to-[var(--color-indigo)] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Play overlay --}}
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[var(--color-pma-green)] ml-1" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>

                                @if($album->is_featured)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2 py-1 bg-[var(--color-ochre)] text-white text-xs font-bold rounded-full">Featured</span>
                                    </div>
                                @endif

                                @if(!$album->isReleased())
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2 py-1 bg-[var(--color-pma-green)] text-white text-xs font-bold rounded-full animate-pulse">Coming Soon</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-[var(--color-indigo)] group-hover:text-[var(--color-pma-green)] transition-colors mb-1">
                                    {{ $album->title }}
                                </h3>
                                <p class="text-[var(--color-pma-green)] font-medium text-sm mb-2">{{ $album->artist }}</p>

                                @if(!$album->isReleased())
                                    {{-- Mini countdown --}}
                                    @php $countdown = $album->getTimeUntilRelease(); @endphp
                                    <div class="countdown-timer flex gap-2 text-xs mb-2" data-release="{{ $countdown['iso'] }}">
                                        <span class="bg-gray-100 px-2 py-1 rounded">
                                            <span class="countdown-days font-bold">{{ $countdown['days'] }}</span>d
                                        </span>
                                        <span class="bg-gray-100 px-2 py-1 rounded">
                                            <span class="countdown-hours font-bold">{{ str_pad($countdown['hours'], 2, '0', STR_PAD_LEFT) }}</span>h
                                        </span>
                                        <span class="bg-gray-100 px-2 py-1 rounded">
                                            <span class="countdown-minutes font-bold">{{ str_pad($countdown['minutes'], 2, '0', STR_PAD_LEFT) }}</span>m
                                        </span>
                                        <span class="bg-[var(--color-pma-green)]/10 px-2 py-1 rounded text-[var(--color-pma-green)]">
                                            <span class="countdown-seconds font-bold">{{ str_pad($countdown['seconds'], 2, '0', STR_PAD_LEFT) }}</span>s
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <span>{{ $album->songs_count }} {{ Str::plural('track', $album->songs_count) }}</span>
                                        @if($album->release_date)
                                            <span>{{ $album->release_date->format('M Y') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $albums->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Albums Yet</h3>
                    <p class="text-gray-500">Check back soon for new worship music from PMA.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Support Section --}}
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-[var(--color-indigo)] mb-4">Support Our Music Ministry</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                All our music is available for free download. If you'd like to support our ministry and help us create more worship music, consider making a donation.
            </p>
            <a href="{{ route('donate') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-[var(--color-ochre)] hover:bg-[var(--color-ochre-light)] text-white rounded-xl font-semibold shadow-lg transition-all hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Support Us
            </a>
        </div>
    </section>

@push('scripts')
<script>
    // Countdown Timers
    function initCountdowns() {
        const timers = document.querySelectorAll('.countdown-timer');

        timers.forEach(timer => {
            const releaseDate = new Date(timer.dataset.release);

            function update() {
                const now = new Date();
                const diff = releaseDate - now;

                if (diff <= 0) {
                    window.location.reload();
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                const daysEl = timer.querySelector('.countdown-days');
                const hoursEl = timer.querySelector('.countdown-hours');
                const minutesEl = timer.querySelector('.countdown-minutes');
                const secondsEl = timer.querySelector('.countdown-seconds');

                if (daysEl) daysEl.textContent = days;
                if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
            }

            update();
            setInterval(update, 1000);
        });
    }

    initCountdowns();
</script>
@endpush
@endsection
