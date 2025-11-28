@extends('layouts.public')

@section('title', 'Support - Pioneer Missions Africa')
@section('description', 'Support Pioneer Missions Africa through one-time donations or monthly contributions. Your support helps spread the Everlasting Gospel across Africa.')

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

                        <div>
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Amount (ZAR)
                            </label>
                            <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center text-xl"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <input required type="hidden" name="item_name" maxlength="255" value="One-Time Donation">
                        <input type="hidden" name="item_description" maxlength="255" value="Support Pioneer Missions Africa ministry work">

                        <div class="text-center">
                            <input type="image" src="https://my.payfast.io/images/buttons/DonateNow/Dark-Large-DonateNow.png" alt="Donate Now" title="Donate Now with Payfast" class="mx-auto">
                        </div>
                    </form>

                    <div class="mt-6 p-4 rounded-lg" style="background: var(--color-cream);">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="pma-body text-sm" style="color: var(--color-olive);">
                                You will be redirected to PayFast secure payment page
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

                        <div>
                            <label class="block pma-heading-light text-sm mb-2 text-center" style="color: var(--color-indigo);">
                                Monthly Amount (ZAR)
                            </label>
                            <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body text-center text-xl"
                                   style="focus:ring-color: var(--color-pma-green);">
                        </div>

                        <input required type="hidden" name="item_name" maxlength="255" value="Monthly Recurring Donation">
                        <input type="hidden" name="item_description" maxlength="255" value="Monthly support for Pioneer Missions Africa">
                        <input required type="hidden" name="subscription_type" pattern="1" value="1">
                        <input type="hidden" name="recurring_amount" value="10">
                        <input required type="hidden" name="cycles" pattern="[0-9]" value="0">
                        <input required type="hidden" name="frequency" pattern="[0-9]" value="3">

                        <div class="text-center">
                            <input type="image" src="https://my.payfast.io/images/buttons/Subscribe/Primary-Large-Subscribe.png" alt="Subscribe" title="Subscribe with Payfast" class="mx-auto">
                        </div>
                    </form>

                    <div class="mt-6 p-4 rounded-lg" style="background: var(--color-cream);">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="pma-body text-sm" style="color: var(--color-olive);">
                                Set up automatic monthly donations via PayFast
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
});
</script>
@endpush
@endsection
