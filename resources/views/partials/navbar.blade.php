<div class="fixed top-0 left-0 right-0 z-50 p-4 pointer-events-none">
    <nav class="pointer-events-auto mx-auto max-w-7xl rounded-2xl bg-white/90 backdrop-blur-xl shadow-lg border border-white/20 transition-all duration-300" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                <img class="h-10 w-auto transition-transform duration-300 group-hover:scale-105" src="{{ url('images/PMALogoDarkText.png') }}" alt="Pioneer Missions Africa">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-[var(--color-indigo)] font-medium hover:text-[var(--color-pma-green)] transition-colors text-sm uppercase tracking-wide">Home</a>
                
                <!-- About Dropdown -->
                <div class="relative group h-20 flex items-center">
                    <button class="flex items-center gap-1 text-[var(--color-indigo)] font-medium hover:text-[var(--color-pma-green)] transition-colors text-sm uppercase tracking-wide focus:outline-none">
                        About
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <div class="font-semibold">Fundamental Principles</div>
                            <div class="text-xs text-gray-500 mt-0.5">What we believe</div>
                        </a>
                        <a href="{{ route('about.support') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Support</div>
                            <div class="text-xs text-gray-500 mt-0.5">Partner with us</div>
                        </a>
                    </div>
                </div>

                <a href="{{ route('prayer-room.index') }}" class="relative px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--color-pma-green)] to-[var(--color-pma-green-light)] text-white font-bold text-sm flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    Prayer Room
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold animate-pulse">NEW</span>
                </a>

                <a href="{{ route('network.index') }}" class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--color-pma-green)] to-[var(--color-indigo)] text-white font-bold text-sm flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Network
                </a>

                <!-- Resources Dropdown -->
                <div class="relative group h-20 flex items-center">
                    <button class="flex items-center gap-1 text-[var(--color-indigo)] font-medium hover:text-[var(--color-pma-green)] transition-colors text-sm uppercase tracking-wide focus:outline-none">
                        Resources
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-56 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top translate-y-2 group-hover:translate-y-0">
                        <a href="{{ route('studies') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Studies</div>
                        </a>
                        <a href="{{ route('sermons') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Sermons</div>
                        </a>
                        <a href="{{ route('resources.tracts') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">Tracts</div>
                        </a>
                        <a href="{{ route('resources.ebooks') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors">
                            <div class="font-semibold">E-Books</div>
                        </a>
                    </div>
                </div>

                <a href="{{ route('kingdom-kids') }}" class="flex items-center gap-2 text-[var(--color-indigo)] font-medium hover:text-[var(--color-pma-green)] transition-colors text-sm uppercase tracking-wide">
                    <img src="{{ url('images/kingdomKids.png') }}" alt="Kingdom Kids" class="h-6 w-auto object-contain">
                </a>

                <a href="{{ route('contact') }}" class="text-[var(--color-indigo)] font-medium hover:text-[var(--color-pma-green)] transition-colors text-sm uppercase tracking-wide">Contact</a>
            </div>

            <!-- Right Actions -->
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('donate') }}" class="relative overflow-hidden group bg-[var(--color-pma-green)] text-white px-5 py-2 rounded-full font-semibold shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <span class="relative z-10">Support Us</span>
                    <div class="absolute inset-0 h-full w-full scale-0 rounded-full transition-all duration-300 group-hover:scale-100 group-hover:bg-[var(--color-pma-green-light)]"></div>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="p-2 text-[var(--color-indigo)] hover:text-[var(--color-pma-green)] transition-colors" title="Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-[var(--color-indigo)] text-[var(--color-indigo)] font-medium text-sm hover:bg-[var(--color-indigo)] hover:text-white transition-all">
                        Log In
                    </a>
                @endauth
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
                        <a href="{{ route('about.principles') }}" class="text-gray-600">Principles</a>
                        <a href="{{ route('about.support') }}" class="text-gray-600">Support</a>
                    </div>
                </div>

                    <div x-show="open" class="pl-4 mt-2 flex flex-col gap-3">
                        <a href="{{ route('studies') }}" class="text-gray-600">Studies</a>
                        <a href="{{ route('sermons') }}" class="text-gray-600">Sermons</a>
                        <a href="{{ route('resources.tracts') }}" class="text-gray-600">Tracts</a>
                        <a href="{{ route('resources.ebooks') }}" class="text-gray-600">E-Books</a>
                    </div>
                </div>

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
</div>
