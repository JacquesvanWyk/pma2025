@extends('layouts.public')

@section('title', '404 - Page Not Found | Pioneer Missions Africa')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center bg-[var(--color-cream)]">
    <div class="text-center px-6 py-20">
        <h1 class="text-[8rem] font-extrabold leading-none text-[var(--color-indigo)] opacity-20">404</h1>
        <h2 class="text-3xl font-bold text-[var(--color-indigo)] -mt-4 mb-4">Page Not Found</h2>
        <p class="text-lg text-gray-600 max-w-md mx-auto mb-8">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[var(--color-pma-green)] text-white font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Back to Home
        </a>
    </div>
</section>
@endsection
