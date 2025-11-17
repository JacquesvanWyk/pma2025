<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Pioneer Missions Africa - Proclaiming the Everlasting Gospel')</title>
    <meta name="description" content="@yield('description', 'Pioneer Missions Africa is a ministry determined to proclaim the Everlasting Gospel and spread knowledge of God across Africa.')">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    {{-- Navigation --}}
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="/images/logo.png" alt="PMA Logo" class="h-10 w-auto">
                        <span class="ml-3 text-xl font-bold text-gray-900 hidden sm:block">Pioneer Missions Africa</span>
                    </a>
                </div>
                
                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors {{ request()->routeIs('home') ? 'text-amber-600' : '' }}">Home</a>
                    
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium transition-colors flex items-center">
                            About
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">About Us</a>
                            <a href="{{ route('about.principles') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Fundamental Principles</a>
                            <a href="{{ route('about.support') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Support</a>
                            <a href="{{ route('privacy') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Privacy Policy</a>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium transition-colors flex items-center">
                            Studies
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('studies.pioneers') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Pioneers</a>
                            <a href="{{ route('studies.scriptures') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Bible Scriptures</a>
                            <a href="{{ route('studies.ellen-white') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Ellen White</a>
                            <a href="{{ route('studies.evangelism') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Evangelism</a>
                            <a href="{{ route('studies.issues') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">The Issues</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('studies.afrikaans') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors">Afrikaans Studies</a>
                    
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium transition-colors flex items-center">
                            Resources
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('resources.tracts') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Tracts</a>
                            <a href="{{ route('resources.ebooks') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">E-Books</a>
                            <a href="{{ route('resources.notes') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">Notes</a>
                            <a href="{{ route('resources.dvd') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600">DVD Ministry</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('sermons') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors">Sermons</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-amber-600 font-medium transition-colors">Contact</a>
                    
                    <a href="{{ route('donate') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors">Donate</a>
                </div>
                
                {{-- Mobile menu button --}}
                <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        {{-- Mobile Navigation --}}
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Home</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">About</a>
                <a href="{{ route('studies') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Studies</a>
                <a href="{{ route('studies.afrikaans') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Afrikaans Studies</a>
                <a href="{{ route('resources') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Resources</a>
                <a href="{{ route('sermons') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Sermons</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg">Contact</a>
                <a href="{{ route('donate') }}" class="block px-3 py-2 bg-amber-600 text-white text-center font-semibold rounded-lg">Donate</a>
            </div>
        </div>
    </nav>
    
    {{-- Main Content --}}
    <main class="pt-16">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- About Column --}}
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-white font-bold text-lg mb-4">Pioneer Missions Africa</h3>
                    <p class="mb-4">
                        A ministry determined in its efforts to proclaim the Everlasting Gospel 
                        and spread the knowledge of the one true God and His Son across Africa.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                {{-- Quick Links --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('studies') }}" class="hover:text-white transition-colors">Studies</a></li>
                        <li><a href="{{ route('sermons') }}" class="hover:text-white transition-colors">Sermons</a></li>
                        <li><a href="{{ route('resources') }}" class="hover:text-white transition-colors">Resources</a></li>
                        <li><a href="{{ route('donate') }}" class="hover:text-white transition-colors">Donate</a></li>
                    </ul>
                </div>
                
                {{-- Contact Info --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p>0794703941</p>
                                <p>0634698313</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>South Africa</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-800 text-center">
                <p>&copy; {{ date('Y') }} Pioneer Missions Africa. All rights reserved.</p>
                <p class="mt-2">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>