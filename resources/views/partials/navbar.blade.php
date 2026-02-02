<div class="fixed top-0 left-0 right-0 z-50 px-4 pt-8 pb-4 pointer-events-none" x-data="{ mobileMenuOpen: false }">
    <div class="pointer-events-auto mx-auto max-w-7xl relative">
    <nav class="w-full rounded-2xl bg-white/90 backdrop-blur-xl shadow-lg border border-white/20 transition-all duration-300">
        <div class="px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                <img class="h-10 w-auto transition-transform duration-300 group-hover:scale-105" src="{{ url('images/PMALogoDarkText.png') }}" alt="Pioneer Missions Africa">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                
                <!-- About Dropdown -->
                <div class="relative group h-20 flex items-center">
                    <button class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all focus:outline-none whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        About
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Invisible bridge -->
                    <div class="absolute top-16 left-0 w-full h-4"></div>
                    <!-- Dropdown Content -->
                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top translate-y-2 group-hover:translate-y-0">
                        <a href="{{ route('about') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Our Mission</div>
                            <div class="text-xs text-gray-500 mt-0.5">Who we are & what we do</div>
                        </a>
                        <a href="{{ route('about.principles') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Declaration of Core Beliefs</div>
                            <div class="text-xs text-gray-500 mt-0.5">What we believe</div>
                        </a>
                        <a href="{{ route('about.support') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Support</div>
                            <div class="text-xs text-gray-500 mt-0.5">Partner with us</div>
                        </a>
                    </div>
                </div>

                <a href="{{ route('prayer-room.index') }}" class="px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    Prayer Room
                </a>

                <a href="{{ route('network.index') }}" class="relative px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Network
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold animate-pulse">NEW</span>
                </a>

                <!-- Resources Dropdown -->
                <div class="relative group h-20 flex items-center">
                    <button class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all focus:outline-none whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Resources
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-56 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top translate-y-2 group-hover:translate-y-0">
                        <a href="{{ route('studies') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Studies</div>
                        </a>
                        <a href="{{ route('resources.picture-studies') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Picture Studies</div>
                        </a>
                        <a href="{{ route('sermons') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Sermons</div>
                        </a>
                        <a href="{{ route('shorts') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Shorts</div>
                        </a>
                        <a href="{{ route('resources.tracts') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Tracts</div>
                        </a>
                        <a href="{{ route('resources.ebooks') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">E-Books</div>
                        </a>
                        <a href="{{ route('resources.notes') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Study Notes</div>
                        </a>
                        <a href="{{ route('kingdom-kids') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Kingdom Kids</div>
                        </a>
                    </div>
                </div>

                <a href="{{ route('music.index') }}" class="relative px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    PMA Worship
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold animate-pulse">NEW</span>
                </a>

                <a href="{{ route('gallery') }}" class="px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Gallery
                </a>

                <a href="{{ route('contact') }}" class="px-3 py-1.5 rounded-lg bg-[var(--color-pma-green)] text-white font-medium text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-[var(--color-indigo)] focus:outline-none p-2">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" style="display: none;" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden px-6 pb-6 border-t border-gray-100 bg-white/95 backdrop-blur-xl rounded-b-2xl max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col gap-4 pt-4">
                <a href="{{ route('home') }}" class="text-lg font-medium text-[var(--color-indigo)]">Home</a>
                
                <div x-data="{ open: false }" class="border-b border-dashed border-gray-200 pb-2">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-lg font-medium text-[var(--color-indigo)]">
                        About
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-2 flex flex-col gap-3">
                        <a href="{{ route('about') }}" class="text-gray-600">Our Mission</a>
                        <a href="{{ route('about.principles') }}" class="text-gray-600">Declaration of Core Beliefs</a>
                        <a href="{{ route('about.support') }}" class="text-gray-600">Support</a>
                    </div>
                </div>

                <div x-data="{ open: false }" class="border-b border-dashed border-gray-200 pb-2">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-lg font-medium text-[var(--color-indigo)]">
                        Resources
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-2 flex flex-col gap-3">
                        <a href="{{ route('studies') }}" class="text-gray-600">Studies</a>
                        <a href="{{ route('resources.picture-studies') }}" class="text-gray-600">Picture Studies</a>
                        <a href="{{ route('sermons') }}" class="text-gray-600">Sermons</a>
                        <a href="{{ route('shorts') }}" class="text-gray-600">Shorts</a>
                        <a href="{{ route('resources.tracts') }}" class="text-gray-600">Tracts</a>
                        <a href="{{ route('resources.ebooks') }}" class="text-gray-600">E-Books</a>
                        <a href="{{ route('resources.notes') }}" class="text-gray-600">Study Notes</a>
                        <a href="{{ route('kingdom-kids') }}" class="text-gray-600">Kingdom Kids</a>
                    </div>
                </div>

                <a href="{{ route('prayer-room.index') }}" class="text-lg font-medium text-[var(--color-pma-green)]">Prayer Room</a>

                <a href="{{ route('network.index') }}" class="text-lg font-medium text-[var(--color-pma-green)]">Network</a>

                <a href="{{ route('music.index') }}" class="relative text-lg font-medium text-[var(--color-pma-green)] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    PMA Worship
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">NEW</span>
                </a>

                <a href="{{ route('gallery') }}" class="text-lg font-medium text-[var(--color-indigo)]">Gallery</a>

                <a href="{{ route('contact') }}" class="text-lg font-medium text-[var(--color-indigo)]">Contact</a>

                <hr class="my-2 border-gray-200">

                @auth
                    <a href="{{ route('dashboard') }}" class="text-lg font-medium text-[var(--color-indigo)]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-lg font-medium text-[var(--color-indigo)]">Log In</a>
                    <a href="{{ route('register') }}" class="text-lg font-medium text-[var(--color-indigo)]">Register</a>
                @endauth
                
                <a href="{{ route('donate') }}" class="btn btn-primary w-full justify-center mt-2">Support Us</a>
            </div>
        </div>
    </nav>

        <!-- Secondary Action Box (Desktop Only) -->
        <div class="hidden lg:flex items-center gap-2 px-2 py-1 rounded-t bg-white/90 backdrop-blur-xl shadow-md border border-b-0 border-white/20 absolute bottom-full right-6">
            <a href="{{ route('donate') }}" class="px-2 py-1 rounded bg-amber-500 text-white font-medium text-xs shadow-sm hover:bg-amber-600 transition-all flex items-center gap-1.5 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Support Us
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="px-2 py-1 rounded bg-[var(--color-indigo)] text-white font-medium text-xs shadow-sm hover:opacity-90 transition-all flex items-center gap-1.5 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-2 py-1 rounded bg-[var(--color-indigo)] text-white font-medium text-xs shadow-sm hover:opacity-90 transition-all flex items-center gap-1.5 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login
                </a>
            @endauth
        </div>
    </div>
</div>
