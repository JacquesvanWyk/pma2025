<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="pma">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pioneer Missions Africa - Proclaiming the Everlasting Gospel')</title>
    <meta name="description" content="@yield('description', 'Pioneer Missions Africa is a ministry determined to proclaim the Everlasting Gospel and spread knowledge of God across Africa.')">

    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/favicon/site.webmanifest">

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

        /* NEW badge styling with subtle up-down animation */
        .new-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            animation: float-subtle 2s ease-in-out infinite;
        }

        @keyframes float-subtle {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-3px);
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[var(--color-cream)]">
    @include('partials.navbar')

    <main class="flex-grow {{ $mainClass ?? 'pt-28' }}">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Copyright Bar - Integrated into footer, removing separate one -->

    @stack('scripts')

    <script defer src="https://api.pirsch.io/pa.js"
        id="pianjs"
        data-code="GVL5UWNm9oqItEobieniq1sXJAQ44QMH"></script>
</body>
</html>
