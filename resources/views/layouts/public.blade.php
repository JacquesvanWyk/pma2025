<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="pma">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pioneer Missions Africa - Proclaiming the Everlasting Gospel')</title>
    <meta name="description" content="@yield('description', 'Pioneer Missions Africa is a ministry determined to proclaim the Everlasting Gospel and spread knowledge of God across Africa.')">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        /* Improve dropdown hover behavior */
        .dropdown-hover:hover > .dropdown-content,
        .dropdown-content:hover {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .dropdown-content {
            margin-top: 0;
            transition: opacity 0.2s ease-in-out;
        }

        /* Add invisible bridge between trigger and dropdown for seamless hover */
        .dropdown-hover::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.75rem;
            z-index: 50;
        }

        /* Ensure dropdown stays open when hovering */
        .dropdown-hover:hover .dropdown-content {
            pointer-events: auto;
        }

        /* Better spacing for dropdown items */
        .dropdown-content li a {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease;
        }

        .dropdown-content li a:hover {
            background-color: rgba(59, 130, 246, 0.1);
            transform: translateX(4px);
        }
    </style>
</head>
<body>
    <div class="page-content">
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg fixed top-0 z-50">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li>
                        <details>
                            <summary>About</summary>
                            <ul class="p-2">
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('about.principles') }}">Fundamental Principles</a></li>
                                <li><a href="{{ route('about.support') }}">Support</a></li>
                                <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                            </ul>
                        </details>
                    </li>
                    <li><a href="{{ route('studies') }}">Studies</a></li>
                    <li>
                        <details>
                            <summary>Resources</summary>
                            <ul class="p-2">
                                <li><a href="{{ route('resources.tracts') }}">Tracts</a></li>
                                <li><a href="{{ route('resources.ebooks') }}">E-Books</a></li>
                            </ul>
                        </details>
                    </li>
                    <li><a href="{{ route('sermons') }}">Sermons</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li>
                        <a href="{{ route('network.index') }}" class="font-bold" style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-indigo)); color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Network
                        </a>
                    </li>
                    <li class="menu-title">Account</li>
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
            <a href="{{ route('home') }}" class="btn btn-ghost text-xl">
                <img class="h-12" src="{{ url('images/PMALogoDarkText.png') }}" alt="Pioneer Missions Africa">
            </a>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="dropdown dropdown-hover relative">
                    <a tabindex="0" role="button" class="flex items-center gap-1">
                        About
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-3 shadow-xl">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('about.principles') }}">Fundamental Principles</a></li>
                        <li><a href="{{ route('about.support') }}">Support</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('studies') }}">Studies</a></li>
                <li class="dropdown dropdown-hover relative">
                    <a tabindex="0" role="button" class="flex items-center gap-1">
                        Resources
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-3 shadow-xl">
                        <li><a href="{{ route('resources.tracts') }}">Tracts</a></li>
                        <li><a href="{{ route('resources.ebooks') }}">E-Books</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('sermons') }}">Sermons</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li>
                    <a href="{{ route('network.index') }}" class="btn btn-sm font-bold gap-1" style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-indigo)); color: white; border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Network
                    </a>
                </li>
            </ul>
        </div>

        <div class="navbar-end gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-ghost">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn btn-ghost">Register</a>
            @endauth
            <a href="{{ route('donate') }}" class="btn btn-primary">Support</a>
        </div>
    </div>

    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer p-10 bg-neutral text-neutral-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <nav>
            <h6 class="footer-title">About</h6>
            <a href="{{ route('about') }}" class="link link-hover">Our Mission</a>
            <a href="{{ route('about.principles') }}" class="link link-hover">Fundamental Principles</a>
            <a href="{{ route('about.support') }}" class="link link-hover">Support Us</a>
            <a href="{{ route('privacy') }}" class="link link-hover">Privacy Policy</a>
        </nav>
        <nav>
            <h6 class="footer-title">Ministry</h6>
            <a href="{{ route('studies') }}" class="link link-hover">Studies</a>
            <a href="{{ route('sermons') }}" class="link link-hover">Sermons</a>
            <a href="{{ route('resources') }}" class="link link-hover">Resources</a>
            <a href="{{ route('contact') }}" class="link link-hover">Contact</a>
            <a href="{{ route('donate') }}" class="link link-hover">Support</a>
        </nav>
        <nav>
            <h6 class="footer-title">Contact</h6>
            <p class="text-sm">Phone: 0794703941</p>
            <p class="text-sm">Alternative: 0634698313</p>
            <p class="text-sm">Location: South Africa</p>
            <a href="mailto:info@pioneermissionsafrica.co.za" class="link link-hover text-sm">Email Us</a>
        </nav>
        <nav>
            <h6 class="footer-title">Connect With Us</h6>
            <p class="text-sm mb-2">Follow us on social media</p>
            <div class="flex gap-4">
                <a href="#" class="link hover:text-primary transition-colors" title="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                    </svg>
                </a>
                <a href="#" class="link hover:text-primary transition-colors" title="YouTube">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
                    </svg>
                </a>
                <a href="#" class="link hover:text-primary transition-colors" title="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
                    </svg>
                </a>
            </div>
            <div class="mt-4">
                <a href="{{ route('home') }}#newsletter" class="btn btn-sm btn-outline">Subscribe to Newsletter</a>
            </div>
        </nav>
    </footer>

    <!-- Copyright Bar -->
    <div class="bg-base-300 py-4">
        <div class="container mx-auto px-6 text-center text-sm text-base-content/70">
            <p>&copy; {{ date('Y') }} Pioneer Missions Africa. All rights reserved. | Proclaiming the Everlasting Gospel</p>
        </div>
    </div>

    </div> <!-- End page-content -->

    @stack('scripts')
</body>
</html>
