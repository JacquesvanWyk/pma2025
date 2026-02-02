@extends('layouts.public')

@section('title', 'The Prayer Room - Pioneer Missions Africa')
@section('description', 'Join us for our monthly Prayer Room sessions where we come together to pray for our friends, families, and communities.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                The Prayer Room
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 mb-4">
                Every Last Wednesday of the Month
            </p>
            <p class="text-xl text-white/80">
                Join us as we come together in prayer for our friends, families, and communities
            </p>
        </div>
    </div>
</section>

<!-- Success Message -->
@if(session('success'))
<section class="py-8" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                <svg class="h-12 w-12 mx-auto mb-4" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold mb-2" style="color: var(--color-pma-green);">{{ session('success') }}</p>
                <p class="text-sm" style="color: var(--color-olive);">We will be lifting your request in prayer.</p>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Next Session Details -->
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="pma-card-elevated p-8 lg:p-12 mb-12">
                @if($upcomingSession)
                <div class="text-center mb-8">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-bold mb-4" style="background: var(--color-pma-green); color: white;">
                        NEXT SESSION
                    </span>
                    @if($upcomingSession->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $upcomingSession->image) }}" alt="{{ $upcomingSession->title }}" class="w-full max-w-md mx-auto rounded-lg shadow-lg">
                    </div>
                    @endif
                    <h2 class="text-3xl lg:text-4xl font-bold mb-2" style="color: var(--color-indigo);">
                        {{ $upcomingSession->title }}
                    </h2>
                    <p class="text-xl" style="color: var(--color-olive);">
                        {{ $upcomingSession->session_date->format('l, j F Y @ g:i A') }} (SAST)
                    </p>
                    @if($upcomingSession->description)
                    <p class="mt-4 text-base" style="color: var(--color-olive);">
                        {{ $upcomingSession->description }}
                    </p>
                    @endif
                </div>
                @else
                <div class="text-center mb-8">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-bold mb-4" style="background: var(--color-ochre); color: white;">
                        COMING SOON
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-2" style="color: var(--color-indigo);">
                        Next Session To Be Announced
                    </h2>
                    <p class="text-xl" style="color: var(--color-olive);">
                        Check back soon for details on our next Prayer Room session
                    </p>
                </div>
                @endif

                <!-- Join Links -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <!-- Zoom Link -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <svg class="h-6 w-6 mr-2" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <h3 class="font-bold" style="color: var(--color-indigo);">Join via Zoom</h3>
                        </div>
                        <a href="https://us02web.zoom.us/j/3013144003" target="_blank" class="text-sm break-all hover:underline" style="color: var(--color-pma-green);">
                            https://us02web.zoom.us/j/3013144003
                        </a>
                        <div class="mt-3 text-sm" style="color: var(--color-olive);">
                            <p><strong>Meeting ID:</strong> 301 314 4003</p>
                            <p><strong>Passcode:</strong> jhn17:3pma</p>
                        </div>
                    </div>

                    <!-- WhatsApp Group -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <svg class="h-6 w-6 mr-2" style="color: var(--color-pma-green);" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <h3 class="font-bold" style="color: var(--color-indigo);">WhatsApp Group</h3>
                        </div>
                        <p class="text-sm mb-3" style="color: var(--color-olive);">Join our WhatsApp group to submit prayer requests and stay updated</p>
                        <a href="https://chat.whatsapp.com/EqqJGyKQm02KSaYoi6RhiN?mode=hqrt1" target="_blank" class="pma-btn pma-btn-primary w-full text-center block">
                            Join WhatsApp Group
                        </a>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 text-center">
                    <h3 class="font-bold mb-3" style="color: var(--color-indigo);">Or Connect via Facebook</h3>
                    <p class="text-sm mb-4" style="color: var(--color-olive);">Send us a direct message with your prayer request</p>
                    <a href="https://www.facebook.com/pioneermissionsafrica/" target="_blank" class="inline-flex items-center px-6 py-3 rounded-lg font-medium transition" style="background: #1877F2; color: white;">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Visit Our Facebook Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Previous Session -->
@if($previousSession)
<section class="py-16" style="background: var(--color-cream);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Previous Session</h2>
            </div>

            <div class="pma-card-elevated p-6 lg:p-8">
                @if($previousSession->image)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $previousSession->image) }}" alt="{{ $previousSession->title }}" class="w-full max-w-2xl mx-auto rounded-lg shadow-lg">
                </div>
                @endif
                <div class="text-center">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold mb-3" style="background: var(--color-olive); color: white;">
                        {{ $previousSession->session_date->format('l, j F Y') }}
                    </span>
                    <h3 class="text-2xl lg:text-3xl font-bold" style="color: var(--color-indigo);">
                        {{ $previousSession->title }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Prayer Request Form -->
<section class="py-20" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-12">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Submit Your Prayer Request</h2>
                <p class="pma-body text-lg" style="color: var(--color-olive);">
                    We would be honoured to pray for you. Share your request below and our prayer team will lift it up.
                </p>
            </div>

            <div class="pma-card-elevated p-8">
                <form action="{{ route('prayer-room.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2" style="color: var(--color-indigo);">
                            Your Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               required
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition"
                               style="border-color: var(--color-cream-dark); focus:ring-color: var(--color-pma-green);">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2" style="color: var(--color-indigo);">
                            Email Address (Optional)
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition"
                               style="border-color: var(--color-cream-dark);">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2" style="color: var(--color-indigo);">
                            Phone Number (Optional)
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition"
                               style="border-color: var(--color-cream-dark);">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prayer Request -->
                    <div>
                        <label for="request" class="block text-sm font-semibold mb-2" style="color: var(--color-indigo);">
                            Your Prayer Request <span class="text-red-500">*</span>
                        </label>
                        <textarea id="request"
                                  name="request"
                                  required
                                  rows="6"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition"
                                  style="border-color: var(--color-cream-dark);"
                                  placeholder="Please share your prayer request...">{{ old('request') }}</textarea>
                        @error('request')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Privacy Option -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   id="is_private"
                                   name="is_private"
                                   value="1"
                                   {{ old('is_private') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300"
                                   style="color: var(--color-pma-green);">
                        </div>
                        <div class="ml-3">
                            <label for="is_private" class="text-sm" style="color: var(--color-olive);">
                                Keep this prayer request private (only visible to prayer team)
                            </label>
                        </div>
                    </div>

                    <!-- Honeypot field for spam prevention (hidden from users) -->
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text"
                               id="website"
                               name="website"
                               value="{{ old('website') }}"
                               tabindex="-1"
                               autocomplete="off">
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="pma-btn pma-btn-primary w-full flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                            Submit Prayer Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">How to Submit Prayer Requests</h2>
                <p class="pma-body text-lg" style="color: var(--color-olive);">
                    The WhatsApp group opens the day before each Prayer Room session
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-2xl font-bold" style="background: var(--color-pma-green); color: white;">
                        1
                    </div>
                    <h3 class="font-bold mb-2 text-lg" style="color: var(--color-indigo);">Join the Group</h3>
                    <p class="text-sm" style="color: var(--color-olive);">Use the WhatsApp link above to join our prayer group</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-2xl font-bold" style="background: var(--color-pma-green); color: white;">
                        2
                    </div>
                    <h3 class="font-bold mb-2 text-lg" style="color: var(--color-indigo);">Submit Requests</h3>
                    <p class="text-sm" style="color: var(--color-olive);">Send your requests via WhatsApp, Facebook, or this form</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-2xl font-bold" style="background: var(--color-pma-green); color: white;">
                        3
                    </div>
                    <h3 class="font-bold mb-2 text-lg" style="color: var(--color-indigo);">Join the Session</h3>
                    <p class="text-sm" style="color: var(--color-olive);">Meet us on Zoom every last Wednesday at 7 PM SAST</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection