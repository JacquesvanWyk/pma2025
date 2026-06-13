@extends('layouts.public')

@section('title', 'Camp Meeting 2026 — Pioneer Missions Africa')
@section('description', 'Join us at Wilderness Ebb & Flow Rest Camp, 9–11 October 2026. Register your accommodation and secure your spot.')
@section('og_image', asset('images/camp/mainposter.jpeg'))

@section('content')
<div>

    {{-- ─── HERO ─────────────────────────────────────────────────────── --}}
    <section class="relative min-h-[70vh] flex items-end overflow-hidden" style="background: var(--color-indigo);">
        <img src="{{ asset('images/camp/mainposter.jpeg') }}" alt="Camp Meeting 2026"
             class="absolute inset-0 w-full h-full object-cover opacity-50">
        {{-- gradient overlay --}}
        <div class="absolute inset-0" style="background: linear-gradient(to top, var(--color-indigo) 0%, rgba(30,39,73,0.4) 60%, transparent 100%);"></div>

        <div class="relative z-10 w-full max-w-5xl mx-auto px-6 py-16">
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest mb-5 px-3 py-1.5 rounded-full" style="background: var(--color-ochre); color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                9–11 October 2026
            </div>
            <h1 class="pma-display text-white text-5xl md:text-7xl leading-none mb-4">
                Camp Meeting<br><span style="color: var(--color-ochre-light, #D4A574);">2026</span>
            </h1>
            <p class="pma-body text-white/80 text-xl mb-2">
                Wilderness Ebb &amp; Flow Rest Camp
            </p>
            <p class="pma-body text-white/60 text-base">
                Wilderness, Garden Route — Western Cape
            </p>
        </div>
    </section>

    {{-- ─── INTRO / DETAILS ──────────────────────────────────────────── --}}
    <section class="py-16 px-6" style="background: var(--color-cream);">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

                {{-- Left: poster --}}
                <div class="relative">
                    <img src="{{ asset('images/camp/mainposter.jpeg') }}" alt="Camp Meeting 2026 Poster"
                         class="w-full rounded-2xl shadow-xl object-cover">
                </div>

                {{-- Right: details --}}
                <div class="space-y-8">
                    <div>
                        <h2 class="pma-heading text-3xl mb-3" style="color: var(--color-indigo);">Believe — A Better Covenant</h2>
                        <p class="pma-body text-gray-600 leading-relaxed">
                            Pioneer Missions Africa invites you to our 2026 Camp Meeting — three days of worship,
                            fellowship, and the Word in the heart of the Garden Route. Whether you camp by the riverside
                            or sleep in a cabin, we gather as one body.
                        </p>
                    </div>

                    {{-- Key details --}}
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--color-pma-green); color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="pma-heading-light text-xs text-gray-400 uppercase tracking-wide">Dates</p>
                                <p class="pma-body font-semibold" style="color: var(--color-indigo);">9–11 October 2026 (Friday to Sunday)</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--color-pma-green); color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="pma-heading-light text-xs text-gray-400 uppercase tracking-wide">Venue</p>
                                <p class="pma-body font-semibold" style="color: var(--color-indigo);">Wilderness Ebb &amp; Flow Rest Camp</p>
                                <p class="pma-body text-sm text-gray-500">Wilderness, Garden Route, Western Cape</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--color-pma-green); color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="pma-heading-light text-xs text-gray-400 uppercase tracking-wide">Speakers</p>
                                <p class="pma-body font-semibold" style="color: var(--color-indigo);">
                                    {{ implode(' & ', config('camp.venue.speakers')) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--color-pma-green); color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="pma-heading-light text-xs text-gray-400 uppercase tracking-wide">Deposit Deadline</p>
                                <p class="pma-body font-semibold" style="color: var(--color-indigo);">
                                    50% deposit — {{ \Carbon\Carbon::parse(config('camp.deposit_deadline'))->format('j F Y') }}
                                </p>
                                <p class="pma-body text-sm text-gray-500">EFT only · Book directly with the venue</p>
                            </div>
                        </div>
                    </div>

                    {{-- Distances --}}
                    <div class="rounded-xl p-5" style="background: white; border: 1px solid #e5e7eb;">
                        <p class="pma-heading-light text-xs text-gray-400 uppercase tracking-wide mb-3">Travel Distances to Wilderness</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1.5 text-sm pma-body">
                            @foreach(config('camp.venue.distances') as $city => $distance)
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ $city }}</span>
                                <span class="font-semibold" style="color: var(--color-indigo);">{{ $distance }}</span>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Day visitors: R{{ config('camp.day_visitor_fee') }} at the gate</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─── INFO STRIP ───────────────────────────────────────────────── --}}
    <section class="py-5 px-6" style="background: var(--color-pma-green);">
        <div class="max-w-5xl mx-auto flex flex-wrap gap-6 items-center justify-between text-white text-sm pma-body">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Register below — 50% deposit due 31 July 2026
            </span>
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                EFT payments only for deposit
            </span>
        </div>
    </section>

    {{-- ─── ACCOMMODATION ────────────────────────────────────────────── --}}
    <section class="py-16 px-6" style="background: white;">
        <div class="max-w-5xl mx-auto">
            <livewire:camp-booking-form />
        </div>
    </section>

    {{-- ─── SUPPORT THE CAMP ─────────────────────────────────────────── --}}
    <section class="py-16 px-6 relative overflow-hidden" style="background: var(--color-indigo);">
        <div class="absolute inset-0 opacity-5">
            <img src="{{ asset('images/camp/camp.jpeg') }}" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-3xl mx-auto text-center">
            <span class="inline-block text-xs font-semibold uppercase tracking-widest mb-4 px-3 py-1.5 rounded-full" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7);">Make it possible</span>
            <h2 class="pma-heading text-3xl md:text-4xl text-white mb-4">Support the Camp</h2>
            <p class="pma-body text-white/70 text-lg mb-4 max-w-xl mx-auto">
                Running camp involves real costs — conference room hire (R{{ number_format(config('camp.conference_room_rate'), 0) }}/day),
                speaker travel from across the country, sound equipment, and more.
                Your gift helps make it possible for everyone.
            </p>

            <form action="https://payment.payfast.io/eng/process" method="POST" class="flex flex-col items-center gap-5">
                <input type="hidden" name="cmd" value="_paynow">
                <input type="hidden" name="receiver" value="{{ config('camp.payfast_merchant_id') }}">
                <input type="hidden" name="return_url" value="{{ route('camp-meeting.thank-you', ['type' => 'support']) }}">
                <input type="hidden" name="cancel_url" value="{{ route('camp-meeting') }}">
                <input type="hidden" name="notify_url" value="{{ route('donate.notify') }}">
                <input type="hidden" name="item_name" value="{{ config('camp.support_item_name') }}">

                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach([50, 100, 200, 500] as $amount)
                    <button type="button" onclick="setCampAmount({{ $amount }})"
                            class="px-5 py-2 rounded-full text-sm font-semibold border-2 text-white transition-all"
                            style="border-color: rgba(255,255,255,0.3);"
                            onmouseover="this.style.borderColor='white'"
                            onmouseout="this.style.borderColor='rgba(255,255,255,0.3)'">
                        R{{ $amount }}
                    </button>
                    @endforeach
                </div>

                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-2">
                    <span class="text-white font-bold text-lg">R</span>
                    <input type="number" name="amount" id="camp-support-amount" min="10" step="10" value="100"
                           class="bg-transparent text-white font-bold text-2xl w-28 text-center outline-none placeholder-white/40"
                           required>
                </div>

                <button type="submit" class="pma-btn pma-btn-primary px-8 py-3 text-base">
                    Donate via PayFast
                </button>
                <p class="text-white/40 text-xs">Secure payment</p>
            </form>
        </div>
    </section>

    {{-- ─── MERCHANDISE ───────────────────────────────────────────────── --}}
    @if($merchandiseItems->isNotEmpty())
    <section class="py-16 px-6" style="background: var(--color-cream);">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <span class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2 block">Limited edition</span>
                <h2 class="pma-heading text-3xl md:text-4xl mb-3" style="color: var(--color-indigo);">Camp Merchandise</h2>
                <p class="pma-body text-gray-500 text-sm">Order your gear below. Add a donation on top to support the camp.</p>
            </div>

            {{-- Product cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                @foreach($merchandiseItems as $item)
                <div class="rounded-2xl overflow-hidden flex flex-col" style="background: white; border: 1px solid #e5e7eb;">
                    @if($item->image)
                    <img src="{{ asset('images/'.$item->image) }}" alt="{{ $item->name }}"
                         class="w-full object-contain bg-white p-4">
                    @endif
                    <div class="p-5">
                        <h3 class="pma-heading text-base mb-1" style="color: var(--color-indigo);">{{ $item->name }}</h3>
                        <p class="text-2xl font-bold mb-1" style="color: var(--color-pma-green);">R{{ number_format($item->price, 0) }}</p>
                        <p class="text-xs pma-body text-gray-400">Sizes: {{ implode(', ', $item->sizes ?? []) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Unified order form --}}
            <div class="rounded-2xl p-6 md:p-10" style="background: white; border: 1px solid #e5e7eb;">
                <h3 class="pma-heading text-2xl mb-1" style="color: var(--color-indigo);">Place Your Order</h3>
                <p class="pma-body text-gray-500 mb-8 text-sm">Select products, sizes and quantities. Pay securely via PayFast.</p>
                <livewire:camp-tshirt-form />
            </div>
        </div>
    </section>
    @endif

</div>

@push('scripts')
<script>
function setCampAmount(val) {
    document.getElementById('camp-support-amount').value = val;
}
</script>
@endpush
@endsection
