@extends('layouts.public')

@section('title', 'Thank You — Camp Meeting 2026')

@section('content')
<section class="min-h-[60vh] flex items-center justify-center px-6 py-20" style="background: var(--color-cream);">
    <div class="max-w-lg w-full text-center">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background: var(--color-pma-green);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        @if($type === 'support')
            <h1 class="pma-display text-4xl mb-4" style="color: var(--color-indigo);">Thank you for your support!</h1>
            <p class="pma-body text-gray-600 text-lg mb-8">Your contribution toward the camp is received. May God bless you abundantly.</p>
        @elseif($type === 'tshirt')
            <h1 class="pma-display text-4xl mb-4" style="color: var(--color-indigo);">Order received!</h1>
            <p class="pma-body text-gray-600 text-lg mb-8">Your T-shirt payment is confirmed. We will be in touch about collection/delivery.</p>
        @else
            <h1 class="pma-display text-4xl mb-4" style="color: var(--color-indigo);">You're registered!</h1>
            <p class="pma-body text-gray-600 text-lg mb-8">Thank you for registering for Camp Meeting 2026. Check the details above for your EFT payment information.</p>
        @endif

        <a href="{{ route('camp-meeting') }}" class="pma-btn pma-btn-primary inline-block">
            Back to Camp Meeting
        </a>
    </div>
</section>
@endsection
