@extends('layouts.public')

@section('title', 'Support - Pioneer Missions Africa')
@section('description', 'Support Pioneer Missions Africa through one-time donations or monthly contributions. Your support helps spread the Everlasting Gospel across Africa.')

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Support Our Ministry
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                Your partnership helps us continue spreading the Everlasting Gospel across Africa and beyond. We thank you for your support. God bless.
            </p>
        </div>
    </div>
</section>

<!-- Donation Options -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-8 mb-12">
            <!-- One-Time Donation -->
            <div class="pma-card pma-animate-on-scroll pma-stagger-1">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="pma-heading text-3xl text-center mb-4" style="color: var(--color-indigo);">One-Time Donation</h2>
                    <p class="pma-body text-center mb-8" style="color: var(--color-olive);">
                        Make a single contribution to support our ministry work
                    </p>

                    <!-- PayFast One-Time Form -->
                    <form name="PayFastPayNowForm" action="https://payment.payfast.io/eng/process" method="post" class="space-y-6">
                        <input required type="hidden" name="cmd" value="_paynow">
                        <input required type="hidden" name="receiver" pattern="[0-9]" value="13157150">
                        <input type="hidden" name="return_url" value="https://pioneermissionsafrica.co.za/donate/thank-you">
                        <input type="hidden" name="cancel_url" value="https://pioneermissionsafrica.co.za/donate">
                        <input type="hidden" name="notify_url" value="https://pioneermissionsafrica.co.za/donate/notify">

                        <div class="mb-4">
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Email (for receipt)
                            </label>
                            <input type="email" id="donorEmail" name="email_address" placeholder="your@email.com"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Amount (ZAR)
                            </label>

                            {{-- Quick Amount Buttons --}}
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <button type="button" onclick="setDonationAmount(50)" class="donation-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold" style="color: var(--color-indigo);">R50</span>
                                </button>
                                <button type="button" onclick="setDonationAmount(100)" class="donation-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold" style="color: var(--color-indigo);">R100</span>
                                </button>
                                <button type="button" onclick="setDonationAmount(200)" class="donation-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold" style="color: var(--color-indigo);">R200</span>
                                </button>
                                <button type="button" onclick="setDonationAmount(500)" class="donation-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold" style="color: var(--color-indigo);">R500</span>
                                </button>
                            </div>

                            <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10"
                                   onchange="updateDonationDisplay()"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center text-xl"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <input required type="hidden" name="item_name" maxlength="255" value="One-Time Donation">
                        <input type="hidden" name="item_description" maxlength="255" value="Support Pioneer Missions Africa ministry work">

                        {{-- Side-by-side Payment Buttons --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- PayFast Button --}}
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white transition-all hover:opacity-90"
                                    style="background: #0B79BF;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <span>PayFast</span>
                            </button>

                            {{-- Paystack Button --}}
                            <button type="button"
                                    onclick="payWithPaystack('onetime')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white transition-all hover:opacity-90"
                                    style="background: #0AA5DB;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <span>Paystack</span>
                            </button>
                        </div>

                        <p class="text-xs text-center mt-3" style="color: var(--color-olive);">
                            Both gateways accept SA cards, Mastercard, Visa & more
                        </p>
                    </form>

                    <!-- PayPal One-Time -->
                    <div class="mt-6 pt-6 border-t-2" style="border-color: var(--color-cream-dark);">
                        <div class="mb-4 p-3 rounded-lg" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                            <p class="text-sm font-semibold mb-1" style="color: #92400e;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Alternative Payment Option
                            </p>
                            <p class="text-xs" style="color: #78350f;">
                                PayFast and Paystack accept SA and international cards. PayPal (USD) is available as an alternative for international donors.
                            </p>
                        </div>
                        <p class="text-center text-sm mb-2" style="color: var(--color-olive);">Or pay with PayPal</p>
                        <div class="text-center mb-3">
                            <span class="inline-block px-4 py-2 rounded-lg text-lg font-bold" style="background: var(--color-cream); color: var(--color-indigo);" id="paypal-onetime-amount">
                                Loading...
                            </span>
                        </div>
                        <div id="paypal-onetime-button-container"></div>
                        <p class="text-xs text-center mt-2 text-gray-500" id="paypal-onetime-usd"></p>
                    </div>

                    <div class="mt-6 p-4 rounded-lg" style="background: var(--color-cream);">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="pma-body text-sm" style="color: var(--color-olive);">
                                Secure payments via PayFast or Paystack
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Recurring Donation -->
            <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-2" style="border: 3px solid var(--color-pma-green);">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div class="text-center mb-4">
                        <span class="inline-block px-6 py-2 rounded-full text-sm pma-heading mb-4"
                              style="background: var(--color-pma-green); color: white; letter-spacing: 0.05em;">
                            MOST POPULAR
                        </span>
                    </div>
                    <h2 class="pma-heading text-3xl text-center mb-4" style="color: var(--color-indigo);">Monthly Recurring</h2>
                    <p class="pma-body text-center mb-8" style="color: var(--color-olive);">
                        Become a monthly partner in spreading the Gospel
                    </p>

                    <!-- PayFast Monthly Form -->
                    <form name="PayFastPayNowForm" action="https://payment.payfast.io/eng/process" method="post" class="space-y-6">
                        <input required type="hidden" name="cmd" value="_paynow">
                        <input required type="hidden" name="receiver" pattern="[0-9]" value="13157150">
                        <input type="hidden" name="return_url" value="https://pioneermissionsafrica.co.za/donate/thank-you">
                        <input type="hidden" name="cancel_url" value="https://pioneermissionsafrica.co.za/donate">
                        <input type="hidden" name="notify_url" value="https://pioneermissionsafrica.co.za/donate/notify">

                        <div class="mb-4">
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Email (for receipt)
                            </label>
                            <input type="email" id="donorEmailMonthly" name="email_address" placeholder="your@email.com"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <div>
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Monthly Amount (ZAR)
                            </label>

                            {{-- Quick Amount Buttons --}}
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <button type="button" onclick="setMonthlyAmount(50)" class="monthly-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold text-sm" style="color: var(--color-indigo);">R50</span>
                                </button>
                                <button type="button" onclick="setMonthlyAmount(100)" class="monthly-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold text-sm" style="color: var(--color-indigo);">R100</span>
                                </button>
                                <button type="button" onclick="setMonthlyAmount(250)" class="monthly-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold text-sm" style="color: var(--color-indigo);">R250</span>
                                </button>
                                <button type="button" onclick="setMonthlyAmount(500)" class="monthly-amount-btn px-3 py-2 border-2 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors" style="border-color: var(--color-cream-dark);">
                                    <span class="block font-semibold text-sm" style="color: var(--color-indigo);">R500</span>
                                </button>
                            </div>

                            <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10"
                                   onchange="updateDonationDisplay()"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center text-xl"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <input required type="hidden" name="item_name" maxlength="255" value="Monthly Recurring Donation">
                        <input type="hidden" name="item_description" maxlength="255" value="Monthly support for Pioneer Missions Africa">
                        <input required type="hidden" name="subscription_type" pattern="1" value="1">
                        <input type="hidden" name="recurring_amount" value="10">
                        <input required type="hidden" name="cycles" pattern="[0-9]" value="0">
                        <input required type="hidden" name="frequency" pattern="[0-9]" value="3">

                        {{-- Side-by-side Payment Buttons --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- PayFast Button --}}
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white transition-all hover:opacity-90"
                                    style="background: #0B79BF;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <span>PayFast</span>
                            </button>

                            {{-- Paystack Button --}}
                            <button type="button"
                                    onclick="payWithPaystack('monthly')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white transition-all hover:opacity-90"
                                    style="background: #0AA5DB;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <span>Paystack</span>
                            </button>
                        </div>

                        <p class="text-xs text-center mt-3" style="color: var(--color-olive);">
                            Both gateways accept SA cards, Mastercard, Visa & more
                        </p>
                    </form>

                    <!-- PayPal Monthly -->
                    <div class="mt-6 pt-6 border-t-2" style="border-color: var(--color-cream-dark);">
                        <div class="mb-4 p-3 rounded-lg" style="background: #fef3c9; border-left: 4px solid #10b981;">
                            <p class="text-sm font-semibold mb-1" style="color: #065f46;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Alternative Payment Option
                            </p>
                            <p class="text-xs" style="color: #064e3b;">
                                PayFast and Paystack accept SA and international cards. PayPal (USD) is available as an alternative for international donors.
                            </p>
                        </div>
                        <p class="text-center text-sm mb-2" style="color: var(--color-olive);">Or subscribe with PayPal</p>
                        <div class="text-center mb-3">
                            <span class="inline-block px-4 py-2 rounded-lg text-lg font-bold" style="background: var(--color-cream); color: var(--color-indigo);" id="paypal-monthly-amount">
                                Loading...
                            </span>
                        </div>
                        <div id="paypal-monthly-button-container"></div>
                        <p class="text-xs text-center mt-2 text-gray-500" id="paypal-monthly-usd"></p>
                    </div>

                    <div class="mt-6 p-4 rounded-lg" style="background: var(--color-cream);">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="pma-body text-sm" style="color: var(--color-olive);">
                                Set up automatic monthly donations via PayFast or Paystack
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bank Transfer Details -->
<section class="py-20" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <div class="text-center mb-12">
                <div class="pma-accent-line mx-auto mb-4"></div>
                <h2 class="pma-section-title pma-heading mb-4" style="color: var(--color-indigo);">Bank Transfer Details</h2>
                <p class="pma-body text-lg" style="color: var(--color-olive);">
                    Prefer to make a direct bank transfer? Use the details below
                </p>
            </div>

            <div class="pma-card-elevated">
                <div class="p-8 lg:p-12">
                    <div class="w-16 h-16 mx-auto mb-8 rounded-full flex items-center justify-center"
                         style="background: var(--color-cream-dark);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>

                    <div class="space-y-4 max-w-md mx-auto">
                        <div class="flex justify-between py-3 border-b-2" style="border-color: var(--color-cream-dark);">
                            <span class="pma-heading-light" style="color: var(--color-indigo);">Account Name:</span>
                            <span class="pma-body font-mono" style="color: var(--color-olive);">Pioneer Missions Africa</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2" style="border-color: var(--color-cream-dark);">
                            <span class="pma-heading-light" style="color: var(--color-indigo);">Bank:</span>
                            <span class="pma-body font-mono" style="color: var(--color-olive);">Standard Bank</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2" style="border-color: var(--color-cream-dark);">
                            <span class="pma-heading-light" style="color: var(--color-indigo);">Account Number:</span>
                            <span class="pma-body font-mono" style="color: var(--color-olive);">146865340</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2" style="border-color: var(--color-cream-dark);">
                            <span class="pma-heading-light" style="color: var(--color-indigo);">Branch Code:</span>
                            <span class="pma-body font-mono" style="color: var(--color-olive);">051001</span>
                        </div>
                        <div class="text-center pt-4">
                            <span class="pma-body text-sm" style="color: var(--color-olive);">(Electronic Payments)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ministry Statement -->
<section class="py-20" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto pma-animate-on-scroll">
            <div class="pma-quote" style="background: rgba(255, 255, 255, 0.5); border-color: var(--color-pma-green);">
                <p class="pma-quote-text" style="color: var(--color-indigo);">
                    We do not require tithe payments. We accept donations when God impresses you to support the ministry. Every contribution is a voluntary act of worship and partnership in spreading the Everlasting Gospel.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Need Help or Have Questions?</h2>
            <div class="pma-body text-xl text-white/90 mb-8 space-y-2">
                <p>
                    <strong>Phone:</strong> 0794703941 / 0634698313
                </p>
                <p>
                    <strong>Email:</strong> <a href="mailto:info@pioneermissionsafrica.co.za" class="hover:underline">info@pioneermissionsafrica.co.za</a>
                </p>
            </div>
            <a href="{{ route('contact') }}" class="pma-btn pma-btn-primary inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Contact Us
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
let exchangeRate = 18;

// Fetch exchange rate
fetch('/api/exchange-rate')
    .then(response => response.json())
    .then(data => {
        exchangeRate = data.rate;
        updateUSDDisplay();
        renderPayPalButtons();
    })
    .catch(err => {
        console.error('Failed to fetch exchange rate:', err);
        updateUSDDisplay();
        renderPayPalButtons();
    });

function updateUSDDisplay() {
    const onetimeInput = document.getElementById('PayFastAmount');
    if (onetimeInput && onetimeInput.value) {
        const zar = parseFloat(onetimeInput.value);
        const usd = (zar / exchangeRate).toFixed(2);
        document.getElementById('paypal-onetime-amount').textContent = `$${usd} USD`;
        document.getElementById('paypal-onetime-usd').textContent = `(R${zar} at $${exchangeRate.toFixed(2)}/R)`;
    }

    const monthlyInput = document.querySelectorAll('#PayFastAmount')[1];
    if (monthlyInput && monthlyInput.value) {
        const zar = parseFloat(monthlyInput.value);
        const usd = (zar / exchangeRate).toFixed(2);
        document.getElementById('paypal-monthly-amount').textContent = `$${usd} USD /month`;
        document.getElementById('paypal-monthly-usd').textContent = `(R${zar}/month at $${exchangeRate.toFixed(2)}/R)`;
    }
}

// Set one-time donation amount from quick buttons
function setDonationAmount(amount) {
    const input = document.getElementById('PayFastAmount');
    if (input) {
        input.value = amount;
        updateUSDDisplay();

        // Highlight selected button
        document.querySelectorAll('.donation-amount-btn').forEach(btn => {
            const btnAmount = parseInt(btn.textContent.replace('R', ''));
            if (btnAmount === amount) {
                btn.classList.remove('border-gray-300');
                btn.classList.add('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            } else {
                btn.classList.add('border-gray-300');
                btn.classList.remove('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            }
        });
    }
}

// Set monthly donation amount from quick buttons
function setMonthlyAmount(amount) {
    const inputs = document.querySelectorAll('#PayFastAmount');
    if (inputs[1]) {
        inputs[1].value = amount;
        updateUSDDisplay();

        // Highlight selected button
        document.querySelectorAll('.monthly-amount-btn').forEach(btn => {
            const btnAmount = parseInt(btn.textContent.replace('R', ''));
            if (btnAmount === amount) {
                btn.classList.remove('border-gray-300');
                btn.classList.add('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            } else {
                btn.classList.add('border-gray-300');
                btn.classList.remove('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            }
        });
    }
}

// Update donation display when input changes
function updateDonationDisplay() {
    updateUSDDisplay();

    // Clear button highlights when using custom input
    document.querySelectorAll('.donation-amount-btn, .monthly-amount-btn').forEach(btn => {
        btn.classList.add('border-gray-300');
        btn.classList.remove('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
    });
}

// Paystack Payment Handler
function payWithPaystack(type) {
    const inputs = document.querySelectorAll('#PayFastAmount');
    const amount = type === 'onetime' ? parseFloat(inputs[0]?.value) || 10 : parseFloat(inputs[1]?.value) || 10;
    const amountInKobo = Math.round(amount * 100);

    const emailInput = type === 'onetime' ? document.getElementById('donorEmail') : document.getElementById('donorEmailMonthly');
    const email = emailInput?.value?.trim();

    if (!email || !email.includes('@')) {
        alert('Please enter a valid email address for your receipt.');
        emailInput?.focus();
        return;
    }

    const handler = PaystackPop.setup({
        key: '{{ env('PAYSTACK_PUBLIC_KEY') }}',
        email: email,
        amount: amountInKobo,
        currency: 'ZAR',
        channels: ['card', 'bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer', 'eft'],
        metadata: {
            custom_fields: [
                {
                    display_name: "Donation Type",
                    variable_name: "donation_type",
                    value: type === 'onetime' ? 'One-Time Donation' : 'Monthly Recurring'
                }
            ]
        },
        callback: function(response) {
            window.location.href = 'https://pioneermissionsafrica.co.za/donate/thank-you?ref=' + response.reference;
        },
        onClose: function() {
            console.log('Payment window closed');
        }
    });
    handler.openIframe();
}

function renderPayPalButtons() {
    // One-Time PayPal Button
    paypal.Buttons({
        createOrder: function(data, actions) {
            const input = document.getElementById('PayFastAmount');
            const amountZAR = parseFloat(input?.value) || 10;
            const amountUSD = (amountZAR / exchangeRate).toFixed(2);

            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: amountUSD,
                        currency_code: 'USD'
                    },
                    description: 'One-Time Donation to Pioneer Missions Africa (R' + amountZAR + ')'
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Thank you for your donation, ' + details.payer.name.given_name + '!');
                window.location.href = 'https://pioneermissionsafrica.co.za/donate/thank-you';
            });
        },
        onError: function(err) {
            console.error('PayPal error:', err);
        }
    }).render('#paypal-onetime-button-container');

    // Monthly PayPal Button (subscription)
    const monthlyInput = document.querySelectorAll('#PayFastAmount')[1];
    const monthlyAmount = parseFloat(monthlyInput?.value) || 10;

    // Create plan via backend for subscription
    fetch('/paypal/create-plan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ amount: monthlyAmount })
    })
    .then(response => response.json())
    .then(planData => {
        if (planData.plan_id) {
            paypal.Buttons({
                createSubscription: function(data, actions) {
                    return actions.subscription.create({
                        'plan_id': planData.plan_id
                    });
                },
                onApprove: function(data, actions) {
                    alert('Thank you for your monthly pledge!');
                    window.location.href = 'https://pioneermissionsafrica.co.za/pledge';
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                }
            }).render('#paypal-monthly-button-container');
        }
    })
    .catch(err => {
        console.error('Failed to create PayPal plan:', err);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.pma-animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Update USD display when amounts change
    const inputs = document.querySelectorAll('#PayFastAmount');
    inputs.forEach(input => {
        input.addEventListener('change', updateDonationDisplay);
        input.addEventListener('input', updateDonationDisplay);
    });
});
</script>
@endpush
@endsection
