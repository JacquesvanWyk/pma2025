{{-- Album Launch Hero Section --}}
<section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-[#0a1a0a] via-[#0d2818] to-[#0a1a0a]">
    {{-- Background Elements --}}
    <div class="absolute inset-0">
        {{-- Animated light rays --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full">
            <div class="absolute top-0 left-1/4 w-1 h-full bg-gradient-to-b from-[var(--color-pma-green)]/20 via-transparent to-transparent blur-sm transform -rotate-12"></div>
            <div class="absolute top-0 left-1/2 w-2 h-full bg-gradient-to-b from-white/10 via-transparent to-transparent blur-md"></div>
            <div class="absolute top-0 right-1/4 w-1 h-full bg-gradient-to-b from-[var(--color-pma-green)]/20 via-transparent to-transparent blur-sm transform rotate-12"></div>
        </div>
        {{-- Gradient overlays --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40"></div>
    </div>

    {{-- Floating particles effect --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-[var(--color-pma-green)] rounded-full opacity-30 animate-pulse"></div>
        <div class="absolute top-1/3 right-1/3 w-3 h-3 bg-white rounded-full opacity-20 animate-ping" style="animation-duration: 3s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-2 h-2 bg-[var(--color-ochre)] rounded-full opacity-30 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    {{-- Content Container --}}
    <div class="container mx-auto px-6 pt-32 pb-16 relative z-10">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">

            {{-- Left Column: Album Poster --}}
            <div class="relative group order-2 lg:order-1">
                <div class="relative aspect-square max-w-md mx-auto lg:mx-0">
                    {{-- Glow effect behind poster --}}
                    <div class="absolute inset-0 bg-[var(--color-pma-green)]/30 rounded-2xl blur-3xl transform scale-90 group-hover:scale-100 transition-transform duration-500"></div>

                    {{-- Album poster --}}
                    <img src="{{ asset('images/poster.jpeg') }}"
                         alt="REAL - PMA Worship Album"
                         class="relative w-full h-full object-cover rounded-2xl shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 border border-white/10">

                    {{-- Play button overlay for teaser --}}
                    <button onclick="openVideoModal()"
                            class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                        <div class="w-20 h-20 bg-white/90 rounded-full flex items-center justify-center shadow-xl transform hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[var(--color-pma-green)] ml-1" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="absolute bottom-8 text-white text-sm font-medium">Listen to First Song</span>
                    </button>
                </div>
            </div>

            {{-- Right Column: Album Info --}}
            <div class="text-center lg:text-left order-1 lg:order-2">
                {{-- NEW badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-pma-green)]/20 border border-[var(--color-pma-green)]/40 rounded-full mb-6">
                    <span class="w-2 h-2 bg-[var(--color-pma-green)] rounded-full animate-pulse"></span>
                    <span class="text-[var(--color-pma-green)] text-sm font-semibold uppercase tracking-wider">New Album</span>
                </div>

                {{-- Album Title --}}
                <h1 class="text-6xl lg:text-8xl font-black text-white mb-4 tracking-tight">
                    REAL
                </h1>

                {{-- Artist --}}
                <p class="text-2xl lg:text-3xl text-[var(--color-pma-green-light)] font-semibold mb-6">
                    PMA Worship
                </p>

                {{-- Launch Date --}}
                <div class="flex items-center justify-center lg:justify-start gap-3 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xl text-white">
                        Album Launch <span class="text-[var(--color-ochre)] font-bold">20<sup>th</sup> December</span>
                    </span>
                </div>

                {{-- Tagline --}}
                <p class="text-xl text-gray-300 mb-4 italic">
                    "Listen to the Truth in Song"
                </p>

                {{-- Description about music ministry --}}
                <p class="text-gray-400 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    Music is a powerful way to share the everlasting gospel. Through worship, we declare God's truth in a language that touches hearts and transcends barriers. These songs carry the message of hope and salvation to every corner of Africa and beyond.
                </p>

                {{-- Countdown (if before release) --}}
                @php
                    $releaseDate = \Carbon\Carbon::create(2024, 12, 20);
                    $now = now();
                    $daysUntil = $now->diffInDays($releaseDate, false);
                @endphp

                @if($daysUntil > 0)
                <div class="mb-8" x-data="countdown()" x-init="startCountdown()">
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-3">Releasing In</p>
                    <div class="flex justify-center lg:justify-start gap-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <span class="text-2xl font-bold text-white" x-text="days">--</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 block">Days</span>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <span class="text-2xl font-bold text-white" x-text="hours">--</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 block">Hours</span>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <span class="text-2xl font-bold text-white" x-text="minutes">--</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 block">Minutes</span>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <span class="text-2xl font-bold text-white" x-text="seconds">--</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 block">Seconds</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('music.index') }}"
                       class="px-8 py-4 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg shadow-[var(--color-pma-green)]/30 transition-all hover:-translate-y-1 hover:shadow-xl flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        Listen Now
                    </a>
                    <button onclick="openVideoModal()"
                            class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-semibold backdrop-blur-md transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Listen to First Song
                    </button>
                </div>

                {{-- Streaming Platform Links --}}
                <div class="mt-8 pt-6 border-t border-white/10">
                    <p class="text-gray-400 text-sm mb-4 text-center lg:text-left">Also available on:</p>
                    <div class="flex gap-4 justify-center lg:justify-start">
                        {{-- Spotify --}}
                        <a href="https://open.spotify.com/album/2la3pYUEhzEg7LTmyJE17J" 
                           target="_blank"
                           rel="noopener"
                           class="group flex items-center gap-2 px-4 py-3 bg-[#1DB954]/10 hover:bg-[#1DB954]/20 border border-[#1DB954]/30 rounded-lg transition-all hover:-translate-y-1">
                            <svg class="w-6 h-6 text-[#1DB954]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                            </svg>
                            <span class="text-white font-medium hidden sm:inline">Spotify</span>
                        </a>

                        {{-- Apple Music --}}
                        <a href="https://music.apple.com/za/artist/pma-worship/1865116757" 
                           target="_blank"
                           rel="noopener"
                           class="group flex items-center gap-2 px-4 py-3 bg-[#FA243C]/10 hover:bg-[#FA243C]/20 border border-[#FA243C]/30 rounded-lg transition-all hover:-translate-y-1">
                            <svg class="w-6 h-6 text-[#FA243C]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.994 6.124a9.23 9.23 0 0 0-.24-2.19c-.317-1.31-1.062-2.31-2.18-3.043a5.022 5.022 0 0 0-1.877-.726 10.496 10.496 0 0 0-1.564-.15c-.04-.003-.083-.01-.124-.013H5.986c-.152.01-.303.017-.455.026-.747.043-1.49.123-2.193.4-1.336.53-2.3 1.452-2.865 2.78-.192.448-.292.925-.363 1.408a10.61 10.61 0 0 0-.1 1.18c0 .032-.007.062-.01.093v12.223c.01.14.017.283.033.424.063.676.168 1.344.465 1.965.595 1.245 1.593 2.1 2.926 2.543.524.172 1.07.267 1.623.346.167.024.337.036.507.05h12.03c.024 0 .05-.003.074-.003.204-.007.408-.01.611-.028a9.944 9.944 0 0 0 2.02-.312c1.14-.33 2.053-1.007 2.72-2.047.415-.647.615-1.37.722-2.113.065-.448.092-.9.113-1.352.01-.21.01-.42.01-.63V6.227c0-.024-.003-.05-.005-.074zm-3.07 11.201c0 .14 0 .28-.006.42-.01.476-.088.946-.335 1.368-.39.664-1.01 1.004-1.753 1.08-.27.03-.542.035-.814.035H6.028c-.27 0-.54-.005-.81-.034-.718-.076-1.34-.412-1.734-1.074-.263-.44-.34-.928-.35-1.43 0-.134-.005-.268-.005-.402V6.223c0-.133 0-.267.005-.4.01-.502.087-.99.35-1.43.394-.662 1.016-.998 1.735-1.074.27-.03.54-.034.81-.034h11.988c.27 0 .542.005.814.034.743.076 1.363.416 1.753 1.08.247.422.325.892.335 1.368.006.14.006.28.006.42v11.14z"/>
                                <path d="M15.81 14.214c.04.068.086.134.135.196.353.447.79.788 1.297 1.016.653.292 1.338.404 2.048.372 1.04-.046 1.944-.414 2.683-1.15.46-.46.82-1.002 1.05-1.622.196-.528.29-1.073.29-1.632V5.77c0-.16-.01-.318-.03-.476-.054-.41-.246-.75-.62-.947a1.204 1.204 0 0 0-.723-.175c-.44.017-.826.204-1.132.54-.3.33-.456.73-.456 1.194v6.65c0 .03-.003.06-.005.09a2.56 2.56 0 0 1-.134.63c-.175.493-.518.865-.988 1.074-.375.168-.77.225-1.178.185-.47-.046-.896-.22-1.273-.5-.3-.223-.545-.498-.74-.81a2.348 2.348 0 0 1-.343-1.085c-.01-.12-.013-.24-.013-.36V8.38c0-.16-.01-.318-.03-.476-.054-.41-.246-.75-.62-.947a1.204 1.204 0 0 0-.723-.175c-.44.017-.826.204-1.132.54-.3.33-.456.73-.456 1.194v5.78c0 .08.003.16.008.24.04.63.194 1.232.485 1.787z"/>
                            </svg>
                            <span class="text-white font-medium hidden sm:inline">Apple Music</span>
                        </a>

                        {{-- YouTube Music --}}
                        <a href="https://music.youtube.com/channel/UCb5kpOoNiwCCAFgFm76oNTQ" 
                           target="_blank"
                           rel="noopener"
                           class="group flex items-center gap-2 px-4 py-3 bg-[#FF0000]/10 hover:bg-[#FF0000]/20 border border-[#FF0000]/30 rounded-lg transition-all hover:-translate-y-1">
                            <svg class="w-6 h-6 text-[#FF0000]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.376 0 0 5.376 0 12s5.376 12 12 12 12-5.376 12-12S18.624 0 12 0zm4.99 16.716c-.888.888-2.164 1.284-3.99 1.284-1.826 0-3.102-.396-3.99-1.284C8.122 15.828 7.726 14.552 7.726 12.726V11.9c0-1.826.396-3.102 1.284-3.99C9.898 7.122 11.174 6.726 13 6.726c1.826 0 3.102.396 3.99 1.284.888.888 1.284 2.164 1.284 3.99v.826c0 1.826-.396 3.102-1.284 3.99zM13 8.418c-1.584 0-2.208.264-2.64.696-.432.432-.696 1.056-.696 2.64v.826c0 1.584.264 2.208.696 2.64.432.432 1.056.696 2.64.696s2.208-.264 2.64-.696c.432-.432.696-1.056.696-2.64V11.754c0-1.584-.264-2.208-.696-2.64-.432-.432-1.056-.696-2.64-.696zm-.132 3.438v2.772l2.376-1.386-2.376-1.386z"/>
                            </svg>
                            <span class="text-white font-medium hidden sm:inline">YouTube</span>
                        </a>
                    </div>
                </div>

                {{-- Download info --}}
                <p class="mt-6 text-gray-400 text-sm flex items-center justify-center lg:justify-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Free download available from our website
                </p>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</section>

{{-- Video Modal --}}
<div id="videoModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm"
     onclick="closeVideoModal(event)">
    <div class="relative w-full max-w-4xl mx-4" onclick="event.stopPropagation()">
        {{-- Close button --}}
        <button onclick="closeVideoModal()"
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Video Player --}}
        <video id="teaserVideo"
               class="w-full rounded-xl shadow-2xl"
               controls
               poster="{{ asset('images/poster.jpeg') }}">
            <source src="https://pub-35e3553e7d65416e982fef921a999df9.r2.dev/GodsTrueSonFinalV1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

@push('scripts')
<script>
    function openVideoModal() {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('teaserVideo');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        video.play();
    }

    function closeVideoModal(event) {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('teaserVideo');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        video.pause();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
        }
    });

    function countdown() {
        return {
            days: '--',
            hours: '--',
            minutes: '--',
            seconds: '--',
            startCountdown() {
                const targetDate = new Date('2024-12-20T00:00:00');
                const update = () => {
                    const now = new Date();
                    const diff = targetDate - now;

                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000).toString().padStart(2, '0');
                    } else {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                    }
                };
                update();
                setInterval(update, 1000);
            }
        };
    }
</script>
@endpush
