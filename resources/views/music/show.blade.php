@extends('layouts.public')

@section('title', $album->title . ' - ' . $album->artist)

@push('styles')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    .audio-player-bar {
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
    }
    .audio-player-bar.active {
        transform: translateY(0);
    }
    .plyr--audio .plyr__controls {
        background: transparent;
        padding: 0;
    }
    .plyr--audio .plyr__control {
        color: white;
    }
    .plyr--audio .plyr__control:hover {
        background: rgba(255,255,255,0.1);
    }
    .plyr--audio .plyr__progress__buffer,
    .plyr--audio .plyr__volume--display {
        background: rgba(255,255,255,0.2);
    }
    .plyr--audio .plyr__progress__container input[type=range] {
        color: var(--color-pma-green);
    }
    .song-row:hover .play-overlay {
        opacity: 1;
    }
    .song-row .track-number {
        display: flex;
    }
    .song-row:hover .track-number {
        display: none;
    }
    .song-row .play-icon {
        display: none;
    }
    .song-row:hover .play-icon {
        display: flex;
    }
    /* Round corners for first/last rows */
    .song-row:first-child {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    .song-row:last-child {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    /* Hide track number and play icon when song is playing */
    .song-row.is-playing .track-number,
    .song-row.is-playing .play-icon {
        display: none !important;
    }
    .song-row.is-playing:hover .track-number {
        display: none !important;
    }
    /* Show equalizer when playing */
    .song-row .equalizer {
        display: none;
    }
    .song-row.is-playing .equalizer {
        display: flex !important;
    }
    /* Equalizer animation */
    .equalizer {
        align-items: flex-end;
        gap: 2px;
        height: 16px;
    }
    .equalizer .bar {
        width: 3px;
        background: var(--color-pma-green);
        border-radius: 2px;
        animation: equalize 0.8s ease-in-out infinite;
    }
    .equalizer .bar:nth-child(1) { animation-delay: 0s; height: 60%; }
    .equalizer .bar:nth-child(2) { animation-delay: 0.2s; height: 100%; }
    .equalizer .bar:nth-child(3) { animation-delay: 0.4s; height: 40%; }
    .equalizer .bar:nth-child(4) { animation-delay: 0.1s; height: 80%; }
    .song-row.is-paused .equalizer .bar {
        animation-play-state: paused;
    }
    @keyframes equalize {
        0%, 100% { height: 20%; }
        50% { height: 100%; }
    }
</style>
@endpush

@section('content')
    {{-- Album Header --}}
    <section class="relative py-32 bg-gradient-to-br from-[#0a1a0a] via-[#0d2818] to-[#0a1a0a] overflow-hidden">
        {{-- Background effects --}}
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[var(--color-pma-green)] opacity-10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-[var(--color-ochre)] opacity-5 rounded-full blur-[120px]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-3 gap-12 items-start">
                {{-- Album Cover --}}
                <div class="lg:col-span-1">
                    <div class="relative group max-w-md mx-auto lg:mx-0">
                        <div class="absolute inset-0 bg-[var(--color-pma-green)]/20 rounded-2xl blur-2xl transform scale-95 group-hover:scale-100 transition-transform duration-500"></div>
                        @if($album->cover_image)
                            <img src="{{ $album->cover_image_url }}"
                                 alt="{{ $album->title }}"
                                 class="relative w-full aspect-square object-cover rounded-2xl shadow-2xl border border-white/10">
                        @else
                            <div class="relative w-full aspect-square bg-gradient-to-br from-[var(--color-pma-green)] to-[var(--color-indigo)] rounded-2xl shadow-2xl flex items-center justify-center border border-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Album Info --}}
                <div class="lg:col-span-2 text-center lg:text-left">
                    <span class="text-[var(--color-pma-green)] font-semibold uppercase tracking-widest text-sm mb-2 block">Album</span>
                    <h1 class="text-5xl lg:text-6xl font-bold text-white mb-4">{{ $album->title }}</h1>
                    <p class="text-2xl text-[var(--color-pma-green-light)] font-semibold mb-4">{{ $album->artist }}</p>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 text-gray-300 mb-6">
                        @if($album->release_date)
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $album->release_date->format('F j, Y') }}
                            </span>
                        @endif
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                            {{ $album->songs->count() }} {{ Str::plural('track', $album->songs->count()) }}
                        </span>
                        <div class="relative group/downloads">
                            <span class="flex items-center gap-2 cursor-help">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                {{ number_format($album->download_count) }} downloads
                            </span>
                            {{-- Download Breakdown Popover --}}
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-48 bg-white rounded-md shadow-lg border border-gray-200 p-3 opacity-0 invisible group-hover/downloads:opacity-100 group-hover/downloads:visible transition-all z-20">
                                <div class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wide">Download Breakdown</div>
                                <div class="space-y-1.5 text-sm">
                                    <div class="flex justify-between text-gray-700">
                                        <span>Audio Only</span>
                                        <span class="font-semibold">{{ number_format($album->audio_download_count) }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-700">
                                        <span>Audio + Video</span>
                                        <span class="font-semibold">{{ number_format($album->video_download_count) }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-700">
                                        <span>Full Bundle</span>
                                        <span class="font-semibold">{{ number_format($album->full_download_count) }}</span>
                                    </div>
                                </div>
                                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-white border-r border-b border-gray-200 rotate-45"></div>
                            </div>
                        </div>
                    </div>

                    @if($album->description)
                        <div class="text-gray-300 text-lg leading-relaxed max-w-none mb-8">
                            {!! $album->description !!}
                        </div>
                    @endif

                    @if($album->isReleased())
                        {{-- Download Album Dropdown --}}
                        <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="px-8 py-4 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg shadow-[var(--color-pma-green)]/30 transition-all hover:-translate-y-1 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Album
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition
                                     class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 py-2 z-30">
                                    <button onclick="showDonationModal('album', 'mp3')"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[var(--color-pma-green)]/10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">MP3 Audio</div>
                                            <div class="text-xs text-gray-500">Compressed audio files</div>
                                        </div>
                                    </button>
                                    <button onclick="showDonationModal('album', 'wav')"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[var(--color-ochre)]/10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">WAV Audio</div>
                                            <div class="text-xs text-gray-500">Lossless high-quality files</div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <a href="{{ route('music.index') }}"
                               class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-semibold backdrop-blur-md transition-all hover:-translate-y-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                All Albums
                            </a>
                        </div>

                        {{-- Streaming Platform Links --}}
                        <div class="mt-8 pt-6 border-t border-white/10">
                            <p class="text-gray-400 text-sm mb-4 text-center lg:text-left">Stream on your favorite platform:</p>
                            <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                                {{-- Spotify --}}
                                <a href="https://open.spotify.com/album/2la3pYUEhzEg7LTmyJE17J" 
                                   target="_blank"
                                   rel="noopener"
                                   class="group flex items-center gap-2 px-4 py-3 bg-[#1DB954]/10 hover:bg-[#1DB954]/20 border border-[#1DB954]/30 rounded-lg transition-all hover:-translate-y-1">
                                    <svg class="w-5 h-5 text-[#1DB954]" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                                    </svg>
                                    <span class="text-white font-medium text-sm">Spotify</span>
                                </a>

                                {{-- Apple Music --}}
                                <a href="https://music.apple.com/za/artist/pma-worship/1865116757" 
                                   target="_blank"
                                   rel="noopener"
                                   class="group flex items-center gap-2 px-4 py-3 bg-[#FA243C]/10 hover:bg-[#FA243C]/20 border border-[#FA243C]/30 rounded-lg transition-all hover:-translate-y-1">
                                    <svg class="w-5 h-5" viewBox="0 0 512 512" fill="none">
                                        <rect width="512" height="512" rx="100" fill="url(#apple-gradient-show)"/>
                                        <path d="M368.5 278.5c-1.6-38.6 31.5-57.2 32.9-58.1-17.9-26.2-45.8-29.8-55.7-30.2-23.7-2.4-46.3 14-58.3 14-12.1 0-30.8-13.7-50.6-13.3-26 .4-50 15.1-63.4 38.4-27 48.7-6.9 120.9 19.4 160.4 12.9 19.4 28.2 41.1 48.4 40.4 19.6-.8 27-12.7 50.6-12.7s30.5 12.7 50.6 12.3c20.9-.3 34.5-19.9 47.4-39.4 14.9-22.5 21-44.3 21.3-45.4-.5-.2-40.8-15.6-42.6-62.4z" fill="white"/>
                                        <path d="M318.8 148.6c10.8-13.1 18.1-31.3 16.1-49.5-15.6.6-34.5 10.4-45.7 23.5-10 11.6-18.7 30.1-16.4 47.9 17.3 1.3 35-8.8 45.9-21.9z" fill="white"/>
                                        <defs>
                                            <linearGradient id="apple-gradient-show" x1="256" y1="0" x2="256" y2="512">
                                                <stop stop-color="#FA243C"/>
                                                <stop offset="1" stop-color="#FA243C"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <span class="text-white font-medium text-sm">Apple Music</span>
                                </a>

                                {{-- YouTube Music --}}
                                <a href="https://music.youtube.com/channel/UCb5kpOoNiwCCAFgFm76oNTQ" 
                                   target="_blank"
                                   rel="noopener"
                                   class="group flex items-center gap-2 px-4 py-3 bg-[#FF0000]/10 hover:bg-[#FF0000]/20 border border-[#FF0000]/30 rounded-lg transition-all hover:-translate-y-1">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" fill="#FF0000"/>
                                        <path d="M10 8.5v7l6-3.5-6-3.5z" fill="white"/>
                                    </svg>
                                    <span class="text-white font-medium text-sm">YouTube</span>
                                </a>
                            </div>
                        </div>

                        @if($album->suggested_donation > 0)
                            <p class="mt-6 text-gray-400 text-sm">
                                Suggested donation: R{{ number_format($album->suggested_donation, 2) }}
                            </p>
                            <button onclick="showDonationModal('donate', 'donate')"
                                    class="mt-3 px-6 py-2 bg-[var(--color-ochre)] hover:bg-[var(--color-ochre)]/90 text-white rounded-lg font-semibold transition-all hover:-translate-y-0.5 flex items-center gap-2 mx-auto lg:mx-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Donate
                            </button>
                        @endif
                    @else
                        {{-- Countdown Timer --}}
                        @php $countdown = $album->getTimeUntilRelease(); @endphp
                        <div class="mt-8">
                            <p class="text-[var(--color-ochre)] font-semibold text-lg mb-4">Album Launches In</p>
                            <div id="countdown" class="flex gap-4 justify-center lg:justify-start" data-release="{{ $countdown['iso'] }}">
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20">
                                        <span id="countdown-days" class="text-3xl font-bold text-white">{{ $countdown['days'] }}</span>
                                    </div>
                                    <span class="text-gray-400 text-sm mt-2 block">Days</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20">
                                        <span id="countdown-hours" class="text-3xl font-bold text-white">{{ str_pad($countdown['hours'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-400 text-sm mt-2 block">Hours</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20">
                                        <span id="countdown-minutes" class="text-3xl font-bold text-white">{{ str_pad($countdown['minutes'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-400 text-sm mt-2 block">Minutes</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20">
                                        <span id="countdown-seconds" class="text-3xl font-bold text-[var(--color-pma-green)]">{{ str_pad($countdown['seconds'], 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="text-gray-400 text-sm mt-2 block">Seconds</span>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mt-6">
                                Release Date: {{ $album->getReleaseDateTime()->format('F j, Y \a\t g:i A') }} (SA Time)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Track Listing --}}
    @if($album->isReleased())
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-[var(--color-indigo)] mb-8">Track Listing</h2>

            {{-- Support Message --}}
            <div class="bg-gradient-to-r from-[var(--color-pma-green)]/10 to-[var(--color-ochre)]/10 rounded-2xl p-6 mb-8 border border-[var(--color-pma-green)]/20">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-lg font-semibold text-[var(--color-indigo)] mb-2">Support Our Music Ministry</h3>
                        <p class="text-gray-600">
                            We had a wonderful experience creating this music about God and His Son. If you'd like to support us so we can continue making more worship music, please consider donating.
                        </p>
                    </div>
                    <button onclick="showDonationModal('donate', 'donate')"
                            class="shrink-0 px-6 py-3 bg-[var(--color-ochre)] hover:bg-[var(--color-ochre)]/90 text-white rounded-xl font-semibold transition-all hover:-translate-y-0.5 flex items-center gap-2 shadow-lg shadow-[var(--color-ochre)]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Donate Now
                    </button>
                </div>
            </div>

            @if($album->songs->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg">
                    <div class="divide-y divide-gray-100">
                        @foreach($album->songs as $song)
                            <div class="song-row flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group" id="song-row-{{ $song->id }}" data-song-id="{{ $song->id }}">
                                {{-- Play Button / Track Number / Equalizer --}}
                                <div class="w-10 h-10 flex items-center justify-center relative">
                                    @if($song->wav_file)
                                        {{-- Track Number (default) --}}
                                        <span class="track-number text-gray-400 font-semibold">
                                            {{ str_pad($song->track_number, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        {{-- Play Button (on hover) --}}
                                        <button onclick="playAudio({{ $song->id }}, '{{ $song->wav_file_url }}', '{{ addslashes($song->title) }}', '{{ addslashes($album->artist) }}', '{{ $album->cover_image_url }}')"
                                                class="play-icon w-10 h-10 rounded-full bg-[var(--color-pma-green)] text-white items-center justify-center hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                        {{-- Equalizer Animation (when playing) --}}
                                        <div class="equalizer">
                                            <div class="bar"></div>
                                            <div class="bar"></div>
                                            <div class="bar"></div>
                                            <div class="bar"></div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 font-semibold">
                                            {{ str_pad($song->track_number, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Song Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-[var(--color-indigo)] truncate">{{ $song->title }}</h3>
                                    @if($song->description)
                                        <p class="text-sm text-gray-500 truncate">{{ $song->description }}</p>
                                    @endif
                                </div>

                                {{-- Duration --}}
                                @if($song->duration)
                                    <span class="text-gray-400 text-sm hidden sm:block">{{ $song->duration }}</span>
                                @endif

                                {{-- Download Count with Hover Popover --}}
                                @if($song->download_count > 0)
                                    <div class="relative group/songdl hidden sm:block">
                                        <span class="text-gray-400 text-xs flex items-center gap-1 cursor-help">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            {{ number_format($song->download_count) }}
                                        </span>
                                        {{-- Popover --}}
                                        <div class="absolute bottom-full right-0 mb-3 w-40 bg-white rounded-md shadow-lg border border-gray-200 p-2.5 opacity-0 invisible group-hover/songdl:opacity-100 group-hover/songdl:visible transition-all z-30">
                                            <div class="text-[10px] text-gray-500 mb-1.5 font-semibold uppercase tracking-wide">Downloads</div>
                                            <div class="space-y-1 text-xs">
                                                <div class="flex justify-between text-gray-600">
                                                    <span>Audio</span>
                                                    <span class="font-medium">{{ number_format($song->audio_download_count) }}</span>
                                                </div>
                                                <div class="flex justify-between text-gray-600">
                                                    <span>Video</span>
                                                    <span class="font-medium">{{ number_format($song->video_download_count) }}</span>
                                                </div>
                                                <div class="flex justify-between text-gray-600">
                                                    <span>Lyrics</span>
                                                    <span class="font-medium">{{ number_format($song->lyrics_download_count) }}</span>
                                                </div>
                                                <div class="flex justify-between text-gray-600">
                                                    <span>Bundle</span>
                                                    <span class="font-medium">{{ number_format($song->bundle_download_count) }}</span>
                                                </div>
                                            </div>
                                            <div class="absolute -bottom-1 right-4 w-2 h-2 bg-white border-r border-b border-gray-200 rotate-45"></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2">
                                    {{-- Play Video Button --}}
                                    @if($song->mp4_video)
                                        <button onclick="playVideo('{{ $song->mp4_video_url }}', '{{ addslashes($song->title) }}')"
                                                class="p-2 rounded-lg bg-[var(--color-indigo)]/10 text-[var(--color-indigo)] hover:bg-[var(--color-indigo)] hover:text-white transition-colors"
                                                title="Play Video">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Lyrics button --}}
                                    @if($song->lyrics)
                                        <button onclick="toggleLyrics({{ $song->id }})"
                                                class="p-2 rounded-lg bg-gray-100 text-gray-500 hover:bg-[var(--color-pma-green)] hover:text-white transition-colors"
                                                title="View Lyrics">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Download Dropdown --}}
                                    @if($song->wav_file || $song->mp4_video || $song->lyrics)
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                    class="p-2 rounded-lg bg-[var(--color-pma-green)]/10 text-[var(--color-pma-green)] hover:bg-[var(--color-pma-green)] hover:text-white transition-colors"
                                                    title="Download">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </button>
                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 x-transition
                                                 class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 py-2 z-20">
                                                @if($song->wav_file)
                                                    <button onclick="downloadSongFile('audio', {{ $song->id }}, 'mp3')"
                                                            class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between">
                                                        <span class="flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                            </svg>
                                                            MP3
                                                        </span>
                                                        @if($song->getMp3FileSizeFormatted())
                                                            <span class="text-xs text-gray-400">{{ $song->getMp3FileSizeFormatted() }}</span>
                                                        @endif
                                                    </button>
                                                @endif
                                                @if($song->wav_file_path)
                                                    <button onclick="downloadSongFile('audio', {{ $song->id }}, 'wav')"
                                                            class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between">
                                                        <span class="flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                            </svg>
                                                            WAV
                                                        </span>
                                                        @if($song->getWavFileSizeFormatted())
                                                            <span class="text-xs text-gray-400">{{ $song->getWavFileSizeFormatted() }}</span>
                                                        @endif
                                                    </button>
                                                @endif
                                                @if($song->lyrics)
                                                    <button onclick="downloadSongFile('lyrics', {{ $song->id }})"
                                                            class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--color-ochre)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Lyrics (PDF)
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-400 text-xs rounded-lg">Coming Soon</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Lyrics Section (hidden by default) --}}
                            @if($song->lyrics)
                                <div id="lyrics-{{ $song->id }}" class="hidden bg-gray-50 p-6 border-t border-gray-100">
                                    <h4 class="font-semibold text-[var(--color-indigo)] mb-4">{{ $song->title }} - Lyrics</h4>
                                    <div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">{{ $song->lyrics }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    <p class="text-gray-500">No tracks available yet. Check back soon!</p>
                </div>
            @endif
        </div>
    </section>
    @else
    {{-- Preview Songs Section (before release) --}}
    @php
        $previewSongs = $album->songs->where('is_preview', true)->where('is_published', true);
    @endphp

    @if($previewSongs->count() > 0)
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="container mx-auto px-6">
            <div class="flex items-center gap-3 mb-8">
                <h2 class="text-3xl font-bold text-[var(--color-indigo)]">Preview Tracks</h2>
                <span class="px-3 py-1 bg-[var(--color-ochre)] text-white text-sm font-bold rounded-full">Sneak Peek</span>
            </div>

            <div class="bg-white rounded-2xl shadow-lg">
                <div class="divide-y divide-gray-100">
                    @foreach($previewSongs as $song)
                        <div class="song-row flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group" id="song-row-{{ $song->id }}" data-song-id="{{ $song->id }}">
                            {{-- Play Button / Track Number / Equalizer --}}
                            <div class="w-10 h-10 flex items-center justify-center relative">
                                @if($song->wav_file)
                                    <span class="track-number text-gray-400 font-semibold">
                                        {{ str_pad($song->track_number, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <button onclick="playAudio({{ $song->id }}, '{{ $song->wav_file_url }}', '{{ addslashes($song->title) }}', '{{ addslashes($album->artist) }}', '{{ $album->cover_image_url }}')"
                                            class="play-icon w-10 h-10 rounded-full bg-[var(--color-pma-green)] text-white items-center justify-center hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-0.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                    <div class="equalizer">
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                    </div>
                                @else
                                    <span class="text-gray-400 font-semibold">
                                        {{ str_pad($song->track_number, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Song Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-[var(--color-indigo)] truncate">{{ $song->title }}</h3>
                                    <span class="px-2 py-0.5 bg-[var(--color-ochre)]/10 text-[var(--color-ochre)] text-xs font-semibold rounded">Preview</span>
                                </div>
                                @if($song->description)
                                    <p class="text-sm text-gray-500 truncate">{{ $song->description }}</p>
                                @endif
                            </div>

                            {{-- Duration --}}
                            @if($song->duration)
                                <span class="text-gray-400 text-sm hidden sm:block">{{ $song->duration }}</span>
                            @endif

                            {{-- Play Video Button --}}
                            <div class="flex items-center gap-2">
                                @if($song->mp4_video)
                                    <button onclick="playVideo('{{ $song->mp4_video_url }}', '{{ addslashes($song->title) }}')"
                                            class="p-2 rounded-lg bg-[var(--color-indigo)]/10 text-[var(--color-indigo)] hover:bg-[var(--color-indigo)] hover:text-white transition-colors"
                                            title="Play Video">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                @endif

                                @if($song->lyrics)
                                    <button onclick="toggleLyrics({{ $song->id }})"
                                            class="p-2 rounded-lg bg-gray-100 text-gray-500 hover:bg-[var(--color-pma-green)] hover:text-white transition-colors"
                                            title="View Lyrics">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Lyrics Section --}}
                        @if($song->lyrics)
                            <div id="lyrics-{{ $song->id }}" class="hidden bg-gray-50 p-6 border-t border-gray-100">
                                <h4 class="font-semibold text-[var(--color-indigo)] mb-4">{{ $song->title }} - Lyrics</h4>
                                <div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">{{ $song->lyrics }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <p class="text-center text-gray-500 mt-6">
                More tracks available on release day!
            </p>
        </div>
    </section>
    @else
    {{-- Coming Soon Section --}}
    <section class="py-16 bg-[var(--color-cream)]">
        <div class="container mx-auto px-6">
            <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[var(--color-pma-green)] mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold text-[var(--color-indigo)] mb-4">Coming Soon</h2>
                <p class="text-gray-600 text-lg mb-2">This album hasn't been released yet.</p>
                <p class="text-gray-500">Check back on <span class="font-semibold text-[var(--color-pma-green)]">{{ $album->getReleaseDateTime()->format('F j, Y \a\t g:i A') }}</span> (SA Time)</p>
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Related Albums --}}
    @if($relatedAlbums->count() > 0)
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-[var(--color-indigo)] mb-8">More Albums</h2>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($relatedAlbums as $relatedAlbum)
                    <a href="{{ route('music.show', $relatedAlbum->slug) }}"
                       class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="relative aspect-square overflow-hidden">
                            @if($relatedAlbum->cover_image)
                                <img src="{{ $relatedAlbum->cover_image_url }}"
                                     alt="{{ $relatedAlbum->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[var(--color-pma-green)] to-[var(--color-indigo)] flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-[var(--color-indigo)] group-hover:text-[var(--color-pma-green)] transition-colors mb-1">
                                {{ $relatedAlbum->title }}
                            </h3>
                            <p class="text-[var(--color-pma-green)] font-medium text-sm">{{ $relatedAlbum->artist }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Sticky Audio Player Bar --}}
    <div id="audioPlayerBar" class="audio-player-bar fixed bottom-0 left-0 right-0 bg-gradient-to-r from-[var(--color-indigo-dark)] to-[var(--color-indigo)] shadow-2xl z-40 border-t border-white/10">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center gap-4">
                {{-- Album Art --}}
                <div id="playerAlbumArt" class="w-14 h-14 rounded-lg bg-white/10 overflow-hidden shrink-0">
                    <img src="{{ $album->cover_image_url }}" alt="" class="w-full h-full object-cover">
                </div>

                {{-- Song Info --}}
                <div class="min-w-0 flex-shrink-0 w-48">
                    <p id="playerSongTitle" class="text-white font-semibold truncate">Select a song</p>
                    <p id="playerArtist" class="text-gray-400 text-sm truncate">{{ $album->artist }}</p>
                </div>

                {{-- Player Controls --}}
                <div class="flex-1 flex items-center gap-4">
                    <button id="prevBtn" onclick="playPrevious()" class="text-white/70 hover:text-white transition-colors hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                        </svg>
                    </button>
                    <button id="playPauseBtn" onclick="togglePlayPause()" class="w-12 h-12 rounded-full bg-white text-[var(--color-indigo)] flex items-center justify-center hover:scale-105 transition-transform">
                        <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-0.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg id="pauseIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                        </svg>
                    </button>
                    <button id="nextBtn" onclick="playNext()" class="text-white/70 hover:text-white transition-colors hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
                        </svg>
                    </button>

                    {{-- Progress Bar --}}
                    <div class="flex-1 hidden sm:flex items-center gap-3">
                        <span id="currentTime" class="text-white/70 text-xs w-10 text-right">0:00</span>
                        <div class="flex-1 relative h-1.5 bg-white/20 rounded-full cursor-pointer group" onclick="seekAudio(event)">
                            <div id="progressBar" class="absolute inset-y-0 left-0 bg-[var(--color-pma-green)] rounded-full transition-all" style="width: 0%"></div>
                            <div id="progressHandle" class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow opacity-0 group-hover:opacity-100 transition-opacity" style="left: 0%"></div>
                        </div>
                        <span id="totalTime" class="text-white/70 text-xs w-10">0:00</span>
                    </div>
                </div>

                {{-- Volume --}}
                <div class="hidden lg:flex items-center gap-2">
                    <button onclick="toggleMute()" class="text-white/70 hover:text-white">
                        <svg id="volumeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        </svg>
                    </button>
                    <input type="range" id="volumeSlider" min="0" max="100" value="80" class="w-20 accent-[var(--color-pma-green)]" onchange="setVolume(this.value)">
                </div>

                {{-- Close Button --}}
                <button onclick="closePlayer()" class="text-white/50 hover:text-white ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Video Modal --}}
    <div id="videoModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm"
         onclick="closeVideoModal(event)">
        <div class="relative w-full max-w-5xl mx-4" onclick="event.stopPropagation()">
            <button onclick="closeVideoModal()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors flex items-center gap-2">
                <span class="text-sm">Close</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <p id="videoTitle" class="text-white text-xl font-semibold mb-4"></p>
            <video id="videoPlayer"
                   class="w-full rounded-xl shadow-2xl"
                   controls
                   playsinline>
            </video>
        </div>
    </div>

    {{-- Donation Modal --}}
    <div id="donationModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         onclick="closeDonationModal(event)">
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-r from-[var(--color-pma-green)] to-[var(--color-pma-green-light)] p-6 text-white">
                <button onclick="closeDonationModal()" class="absolute top-4 right-4 text-white/80 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-2xl font-bold mb-2">Support Our Ministry</h3>
                <p class="text-white/80" id="modalSubtitle">Your donation helps us create more worship music.</p>
            </div>

            <div class="p-6">
                <p class="text-gray-600 mb-6">
                    All our music is free to download. If you'd like to support our ministry, consider making a donation.
                </p>

                {{-- Custom Amount Selection --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Choose your donation amount</label>

                    {{-- Quick Amount Buttons --}}
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <button type="button" onclick="setDonationAmount(20)" class="donation-amount-btn px-3 py-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-[var(--color-pma-green)] hover:text-[var(--color-pma-green)] transition-colors">
                            R20
                        </button>
                        <button type="button" onclick="setDonationAmount(50)" class="donation-amount-btn px-3 py-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-[var(--color-pma-green)] hover:text-[var(--color-pma-green)] transition-colors">
                            R50
                        </button>
                        <button type="button" onclick="setDonationAmount(100)" class="donation-amount-btn px-3 py-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-[var(--color-pma-green)] hover:text-[var(--color-pma-green)] transition-colors">
                            R100
                        </button>
                        <button type="button" onclick="setDonationAmount(200)" class="donation-amount-btn px-3 py-2 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-[var(--color-pma-green)] hover:text-[var(--color-pma-green)] transition-colors">
                            R200
                        </button>
                    </div>

                    {{-- Custom Amount Input --}}
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">R</span>
                        <input type="number"
                               id="customDonationAmount"
                               min="10"
                               step="1"
                               value="{{ $album->suggested_donation > 0 ? $album->suggested_donation : 50 }}"
                               onchange="updateDonationAmount(this.value)"
                               oninput="updateDonationAmount(this.value)"
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl text-lg font-semibold text-[var(--color-indigo)] focus:border-[var(--color-pma-green)] focus:ring-2 focus:ring-[var(--color-pma-green)]/20 focus:outline-none transition-colors"
                               placeholder="Enter amount">
                    </div>
                    @if($album->suggested_donation > 0)
                        <p class="text-xs text-gray-500 mt-2">Suggested: R{{ number_format($album->suggested_donation, 2) }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <h4 class="font-semibold text-[var(--color-indigo)] mb-3">Pay Online</h4>
                    <form id="payfastForm" action="https://payment.payfast.io/eng/process" method="post">
                        <input type="hidden" name="cmd" value="_paynow">
                        <input type="hidden" name="receiver" value="13157150">
                        <input type="hidden" name="item_name" value="PMA Worship - {{ $album->title }} Donation">
                        <input type="hidden" name="amount" id="payfastAmount" value="{{ $album->suggested_donation > 0 ? $album->suggested_donation : 50 }}">
                        <input type="hidden" name="return_url" value="{{ url()->current() }}?donated=true">
                        <input type="hidden" name="cancel_url" value="{{ url()->current() }}">
                        <button type="submit"
                                id="donateBtn"
                                class="w-full px-6 py-3 bg-[#0B79BF] hover:bg-[#0967a3] text-white rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span id="donateBtnText">Donate R{{ $album->suggested_donation > 0 ? number_format($album->suggested_donation, 2) : '50.00' }} with PayFast</span>
                        </button>
                    </form>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-[var(--color-indigo)] mb-3">Or via Bank Transfer</h4>
                    <div class="bg-gray-50 rounded-xl p-4 text-sm">
                        <div class="grid grid-cols-2 gap-2">
                            <span class="text-gray-500">Account Name:</span>
                            <span class="font-medium">Pioneer Missions Africa</span>
                            <span class="text-gray-500">Bank:</span>
                            <span class="font-medium">Standard Bank</span>
                            <span class="text-gray-500">Account Number:</span>
                            <span class="font-medium">146865340</span>
                            <span class="text-gray-500">Branch Code:</span>
                            <span class="font-medium">051001</span>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <button id="skipDownloadBtn"
                            onclick="proceedWithDownload()"
                            class="w-full px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        Skip and Download Free / Already Donated
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
    // Audio Player State
    let audio = new Audio();
    let isPlaying = false;
    let currentSongIndex = -1;
    let currentPlayingSongId = null;

    // Update song row playing state
    function updateSongRowStates() {
        // Remove playing/paused classes from all rows
        document.querySelectorAll('.song-row').forEach(row => {
            row.classList.remove('is-playing', 'is-paused');
        });

        // Add appropriate class to current song
        if (currentPlayingSongId) {
            const currentRow = document.getElementById(`song-row-${currentPlayingSongId}`);
            if (currentRow) {
                currentRow.classList.add('is-playing');
                if (!isPlaying) {
                    currentRow.classList.add('is-paused');
                }
            }
        }
    }

    // Songs data - only include preview songs if album not released
    @php
        $availableSongs = $album->isReleased()
            ? $album->songs
            : $album->songs->where('is_preview', true)->where('is_published', true);

        $songsData = $availableSongs->map(function($s) use ($album) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'artist' => $album->artist,
                'audioUrl' => $s->wav_file ? $s->wav_file_url : null,
                'videoUrl' => $s->mp4_video ? $s->mp4_video_url : null,
                'cover' => $album->cover_image_url
            ];
        })->values();
    @endphp
    const songs = @json($songsData);

    // Audio Player Functions
    function playAudio(songId, url, title, artist, cover) {
        const playerBar = document.getElementById('audioPlayerBar');
        playerBar.classList.add('active');

        // Find song index
        currentSongIndex = songs.findIndex(s => s.id === songId);
        currentPlayingSongId = songId;

        // Update UI
        document.getElementById('playerSongTitle').textContent = title;
        document.getElementById('playerArtist').textContent = artist;
        document.getElementById('playerAlbumArt').querySelector('img').src = cover;

        // Play audio
        if (audio.src !== url) {
            audio.src = url;
            // Track play count when a new song is played
            trackSongPlay(songId);
        }
        audio.play();
        isPlaying = true;
        updatePlayPauseIcon();
        updateSongRowStates();
    }

    // Track song play count
    function trackSongPlay(songId) {
        fetch(`/api/songs/${songId}/play`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        }).catch(err => console.log('Play tracking failed:', err));
    }

    function togglePlayPause() {
        if (!audio.src) return;

        if (isPlaying) {
            audio.pause();
        } else {
            audio.play();
        }
        isPlaying = !isPlaying;
        updatePlayPauseIcon();
        updateSongRowStates();
    }

    function updatePlayPauseIcon() {
        document.getElementById('playIcon').classList.toggle('hidden', isPlaying);
        document.getElementById('pauseIcon').classList.toggle('hidden', !isPlaying);
    }

    function playPrevious() {
        if (currentSongIndex > 0) {
            const prevSong = songs[currentSongIndex - 1];
            if (prevSong.audioUrl) {
                playAudio(prevSong.id, prevSong.audioUrl, prevSong.title, prevSong.artist, prevSong.cover);
            }
        }
    }

    function playNext() {
        if (currentSongIndex < songs.length - 1) {
            const nextSong = songs[currentSongIndex + 1];
            if (nextSong.audioUrl) {
                playAudio(nextSong.id, nextSong.audioUrl, nextSong.title, nextSong.artist, nextSong.cover);
            }
        }
    }

    function closePlayer() {
        audio.pause();
        isPlaying = false;
        currentPlayingSongId = null;
        document.getElementById('audioPlayerBar').classList.remove('active');
        updatePlayPauseIcon();
        updateSongRowStates();
    }

    function seekAudio(event) {
        if (!audio.duration) return;
        const progressContainer = event.currentTarget;
        const rect = progressContainer.getBoundingClientRect();
        const percent = (event.clientX - rect.left) / rect.width;
        audio.currentTime = percent * audio.duration;
    }

    function setVolume(value) {
        audio.volume = value / 100;
    }

    function toggleMute() {
        audio.muted = !audio.muted;
    }

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    // Audio event listeners
    audio.addEventListener('timeupdate', () => {
        if (audio.duration) {
            const percent = (audio.currentTime / audio.duration) * 100;
            document.getElementById('progressBar').style.width = percent + '%';
            document.getElementById('progressHandle').style.left = percent + '%';
            document.getElementById('currentTime').textContent = formatTime(audio.currentTime);
        }
    });

    audio.addEventListener('loadedmetadata', () => {
        document.getElementById('totalTime').textContent = formatTime(audio.duration);
    });

    audio.addEventListener('ended', () => {
        playNext();
    });

    audio.addEventListener('pause', () => {
        isPlaying = false;
        updatePlayPauseIcon();
        updateSongRowStates();
    });

    audio.addEventListener('play', () => {
        isPlaying = true;
        updatePlayPauseIcon();
        updateSongRowStates();
    });

    // Video Player Functions
    function playVideo(url, title) {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('videoPlayer');
        const titleEl = document.getElementById('videoTitle');

        // Pause audio if playing
        if (isPlaying) {
            audio.pause();
        }

        titleEl.textContent = title;
        video.src = url;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        video.play();
    }

    function closeVideoModal(event) {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('videoPlayer');
        video.pause();
        video.src = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Download Progress Modal
    let downloadProgressTimeout = null;

    function showDownloadProgress(message = 'Preparing your download...', state = 'loading') {
        let modal = document.getElementById('downloadProgressModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'downloadProgressModal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm';
            document.body.appendChild(modal);
        }

        const isLoading = state === 'loading';
        const isSuccess = state === 'success';

        modal.innerHTML = `
            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center shadow-2xl">
                <div class="mb-4">
                    ${isLoading ? `
                        <svg class="animate-spin h-12 w-12 mx-auto text-[var(--color-pma-green)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    ` : `
                        <div class="h-12 w-12 mx-auto rounded-full bg-[var(--color-pma-green)] flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    `}
                </div>
                <p class="text-gray-700 font-semibold text-lg">${message}</p>
                ${isLoading ? `
                    <p class="text-gray-500 text-sm mt-2">This may take a moment for large files...</p>
                    <div class="mt-4 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[var(--color-pma-green)] rounded-full animate-pulse" style="width: 60%; animation: progress-indeterminate 1.5s infinite;"></div>
                    </div>
                ` : `
                    <p class="text-gray-500 text-sm mt-2">Check your browser's download bar or downloads folder.</p>
                    <div class="mt-4 flex items-center justify-center gap-2 text-[var(--color-pma-green)]">
                        <svg class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="text-sm font-medium">Download in progress...</span>
                    </div>
                `}
                ${isSuccess ? `
                    <button onclick="hideDownloadProgress()" class="mt-6 px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                        Got it
                    </button>
                ` : ''}
            </div>
        `;

        modal.classList.remove('hidden');

        // Add progress animation keyframes if not exists
        if (!document.getElementById('download-progress-styles')) {
            const style = document.createElement('style');
            style.id = 'download-progress-styles';
            style.textContent = `
                @keyframes progress-indeterminate {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(200%); }
                }
            `;
            document.head.appendChild(style);
        }
    }

    function showDownloadStarted(filename = 'your file') {
        // Clear any existing timeout
        if (downloadProgressTimeout) {
            clearTimeout(downloadProgressTimeout);
        }

        showDownloadProgress(`Download Started!`, 'success');

        // Auto-hide after 5 seconds
        downloadProgressTimeout = setTimeout(() => {
            hideDownloadProgress();
        }, 5000);
    }

    function hideDownloadProgress() {
        if (downloadProgressTimeout) {
            clearTimeout(downloadProgressTimeout);
            downloadProgressTimeout = null;
        }
        const modal = document.getElementById('downloadProgressModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Direct Song Download Function (no donation modal)
    function downloadSongFile(type, songId, format = 'mp3') {
        let url = `/music/{{ $album->id }}/songs/${songId}/download/${type}`;
        if (type === 'audio' && format) {
            url += `/${format}`;
        }
        if (type === 'lyrics') {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    }

    // Donation Modal Functions (Album downloads only)
    let currentAlbumType = 'full';

    // Donation Amount Functions
    function setDonationAmount(amount) {
        document.getElementById('customDonationAmount').value = amount;
        updateDonationAmount(amount);

        // Update button styles
        document.querySelectorAll('.donation-amount-btn').forEach(btn => {
            btn.classList.remove('border-[var(--color-pma-green)]', 'text-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            btn.classList.add('border-gray-200', 'text-gray-700');
        });

        // Highlight selected button
        event.target.classList.remove('border-gray-200', 'text-gray-700');
        event.target.classList.add('border-[var(--color-pma-green)]', 'text-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
    }

    function updateDonationAmount(amount) {
        amount = parseFloat(amount) || 0;
        if (amount < 10) amount = 10;

        document.getElementById('payfastAmount').value = amount.toFixed(2);
        document.getElementById('donateBtnText').textContent = `Donate R${amount.toFixed(2)} with PayFast`;

        // Clear button highlights when typing custom amount
        document.querySelectorAll('.donation-amount-btn').forEach(btn => {
            const btnAmount = parseInt(btn.textContent.replace('R', ''));
            if (btnAmount === amount) {
                btn.classList.remove('border-gray-200', 'text-gray-700');
                btn.classList.add('border-[var(--color-pma-green)]', 'text-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            } else {
                btn.classList.remove('border-[var(--color-pma-green)]', 'text-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
                btn.classList.add('border-gray-200', 'text-gray-700');
            }
        });
    }

    function showDonationModal(downloadType, albumType) {
        currentAlbumType = albumType;

        const modal = document.getElementById('donationModal');
        const subtitle = document.getElementById('modalSubtitle');
        const skipBtn = document.getElementById('skipDownloadBtn');

        // Handle "just donate" case
        if (albumType === 'donate') {
            subtitle.textContent = 'Your support helps us create more worship music.';
            skipBtn.classList.add('hidden');
        } else {
            // Store download intent in localStorage for post-payment
            localStorage.setItem('pma_download_intent', JSON.stringify({
                albumId: {{ $album->id }},
                type: albumType,
                timestamp: Date.now()
            }));

            const typeLabels = {
                'mp3': 'MP3 Audio (compressed files)',
                'wav': 'WAV Audio (lossless high-quality)',
                'video': 'Audio + Video (MP3 + WAV + MP4 files)',
                'full': 'Full Bundle (Audio + Video + Lyrics PDF)'
            };
            subtitle.textContent = `Download album: ${typeLabels[albumType]}`;
            skipBtn.classList.remove('hidden');
        }

        // Reset donation amount to suggested amount
        const suggestedAmount = {{ $album->suggested_donation > 0 ? $album->suggested_donation : 50 }};
        document.getElementById('customDonationAmount').value = suggestedAmount;
        updateDonationAmount(suggestedAmount);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDonationModal(event) {
        const modal = document.getElementById('donationModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function proceedWithDownload() {
        const skipBtn = document.getElementById('skipDownloadBtn');
        const originalText = skipBtn.textContent;
        skipBtn.textContent = 'Preparing download...';
        skipBtn.disabled = true;

        await triggerAlbumDownload(currentAlbumType);

        // Clear the stored intent
        localStorage.removeItem('pma_download_intent');

        skipBtn.textContent = originalText;
        skipBtn.disabled = false;
        closeDonationModal();
    }

    async function triggerAlbumDownload(albumType) {
        const typeLabels = {
            'mp3': 'MP3 Audio',
            'wav': 'WAV Audio',
            'video': 'Audio + Video',
            'full': 'Full Bundle'
        };
        showDownloadProgress(`Preparing ${typeLabels[albumType] || 'album'} download. This may take a few seconds...`, 'loading');

        const url = `/music/{{ $album->id }}/download/${albumType}`;

        // Use hidden iframe to trigger download without leaving page
        let iframe = document.getElementById('downloadFrame');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'downloadFrame';
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
        }
        iframe.src = url;

        // Show success after delay (download should have started)
        setTimeout(() => {
            showDownloadStarted();
        }, 2000);
    }

    function toggleLyrics(songId) {
        const lyricsDiv = document.getElementById(`lyrics-${songId}`);
        if (lyricsDiv) {
            lyricsDiv.classList.toggle('hidden');
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
            closeDonationModal();
        }
        if (e.key === ' ' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
            e.preventDefault();
            togglePlayPause();
        }
    });

    // Check if user just donated - trigger the download they requested
    if (window.location.search.includes('donated=true')) {
        const storedIntent = localStorage.getItem('pma_download_intent');
        if (storedIntent) {
            try {
                const intent = JSON.parse(storedIntent);
                // Only process if intent is less than 1 hour old and matches this album
                const oneHour = 60 * 60 * 1000;
                if (intent.albumId === {{ $album->id }} && (Date.now() - intent.timestamp) < oneHour) {
                    // Clear the intent first to prevent re-triggering on refresh
                    localStorage.removeItem('pma_download_intent');

                    // Show thank you message and trigger download
                    showDownloadProgress('Thank you for your donation! Preparing your download...', 'loading');
                    setTimeout(() => {
                        triggerAlbumDownload(intent.type);
                    }, 1500);
                }
            } catch (e) {
                console.error('Error processing download intent:', e);
            }
        }

        // Clean up URL (remove ?donated=true)
        const url = new URL(window.location);
        url.searchParams.delete('donated');
        window.history.replaceState({}, '', url);
    }

    // Set initial volume
    audio.volume = 0.8;

    // Countdown Timer
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const releaseDate = new Date(countdownEl.dataset.release);

        function updateCountdown() {
            const now = new Date();
            const diff = releaseDate - now;

            if (diff <= 0) {
                // Album is released, reload the page
                window.location.reload();
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('countdown-days').textContent = days;
            document.getElementById('countdown-hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('countdown-minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('countdown-seconds').textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
</script>
@endpush
@endsection
