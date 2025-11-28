@extends('layouts.public')

@section('title', 'Monthly Pledge - Pioneer Missions Africa')
@section('description', 'Support our monthly ministry pledge. Help us reach our goal of R35,000 per month to spread the Everlasting Gospel across Africa.')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-8 text-gray-900 text-center">
                <h1 class="text-4xl font-bold mb-4">Monthly Ministry Pledge</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Join us in supporting the work of Pioneer Missions Africa. Your monthly pledge helps us proclaim the Everlasting Gospel and spread present truth across the continent.
                </p>
            </div>
        </div>

        <!-- Progress Meter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ $month }} Progress</h2>
                    <p class="text-gray-600">
                        Help us reach our monthly goal
                    </p>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700">R{{ number_format($currentAmount, 2) }}</span>
                        <span class="text-sm font-semibold text-gray-700">R{{ number_format($goalAmount, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                        <div
                            id="pledge-progress-bar"
                            class="bg-gradient-to-r from-blue-500 to-primary h-6 rounded-full transition-all duration-1000 ease-out flex items-center justify-center"
                            style="width: 0%"
                            data-percentage="{{ $percentage }}"
                        >
                            <span id="pledge-percentage" class="text-white text-xs font-bold opacity-0">{{ number_format($percentage, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid md:grid-cols-3 gap-4 mt-8">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-3xl font-bold text-primary">R{{ number_format($currentAmount, 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Current Amount</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-3xl font-bold text-gray-900">R{{ number_format($goalAmount, 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Monthly Goal</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($percentage, 1) }}%</div>
                        <div class="text-sm text-gray-600 mt-1">Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-blue-600 to-primary overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-8 text-white text-center">
                <h2 class="text-2xl font-bold mb-4">Become a Monthly Partner</h2>
                <p class="text-lg mb-6 max-w-2xl mx-auto">
                    Your consistent monthly support enables us to plan ahead and expand our ministry reach. Every contribution, no matter the size, makes a significant difference in spreading God's truth.
                </p>
                <a href="#payment-options" class="btn btn-lg bg-white text-primary hover:bg-gray-100 border-0">
                    Support the Ministry
                </a>
            </div>
        </div>

        <!-- Payment Options -->
        <div id="payment-options" class="grid md:grid-cols-2 gap-6 mb-8">
            <!-- One-Time Donation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">One-Time Donation</h2>
                        <p class="text-gray-600 mb-6">
                            Make a single contribution to support our ministry
                        </p>
                    </div>

                    <!-- PayFast One-Time Button -->
                    <div class="text-center mb-6">
                        <form name="PayFastPayNowForm" action="https://payment.payfast.io/eng/process" method="post">
                            <input required type="hidden" name="cmd" value="_paynow">
                            <input required type="hidden" name="receiver" pattern="[0-9]" value="13157150">
                            <input type="hidden" name="return_url" value="https://pioneermissionsafrica.co.za/donate/thank-you">
                            <input type="hidden" name="cancel_url" value="https://pioneermissionsafrica.co.za/pledge">
                            <input type="hidden" name="notify_url" value="https://pioneermissionsafrica.co.za/donate/notify">

                            <div class="mb-4">
                                <label class="label justify-center">
                                    <span class="label-text font-semibold">Amount (ZAR):</span>
                                </label>
                                <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10" class="input input-bordered w-full max-w-xs">
                            </div>

                            <input required type="hidden" name="item_name" maxlength="255" value="One-Time Donation">
                            <input type="hidden" name="item_description" maxlength="255" value="Support Pioneer Missions Africa ministry work">

                            <input type="image" src="https://my.payfast.io/images/buttons/DonateNow/Dark-Large-DonateNow.png" alt="Donate Now" title="Donate Now with Payfast" class="mx-auto">
                        </form>
                    </div>

                    <div class="alert alert-info text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>You will be redirected to PayFast secure payment page</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Recurring Donation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-primary">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Monthly Recurring Donation</h2>
                        <p class="text-gray-600 mb-6">
                            Become a monthly partner in the ministry
                        </p>
                        <div class="badge badge-primary badge-lg mb-4">Recommended</div>
                    </div>

                    <!-- PayFast Monthly Button -->
                    <div class="text-center mb-6">
                        <form name="PayFastPayNowForm" action="https://payment.payfast.io/eng/process" method="post">
                            <input required type="hidden" name="cmd" value="_paynow">
                            <input required type="hidden" name="receiver" pattern="[0-9]" value="13157150">
                            <input type="hidden" name="return_url" value="https://pioneermissionsafrica.co.za/donate/thank-you">
                            <input type="hidden" name="cancel_url" value="https://pioneermissionsafrica.co.za/pledge">
                            <input type="hidden" name="notify_url" value="https://pioneermissionsafrica.co.za/donate/notify">

                            <div class="mb-4">
                                <label class="label justify-center">
                                    <span class="label-text font-semibold">Amount (ZAR):</span>
                                </label>
                                <input required id="PayFastAmount" type="number" step=".01" name="amount" min="5.00" placeholder="5.00" value="10" class="input input-bordered w-full max-w-xs">
                            </div>

                            <input required type="hidden" name="item_name" maxlength="255" value="Monthly Recurring Donation">
                            <input type="hidden" name="item_description" maxlength="255" value="Monthly support for Pioneer Missions Africa">
                            <input required type="hidden" name="subscription_type" pattern="1" value="1">
                            <input type="hidden" name="recurring_amount" value="10">
                            <input required type="hidden" name="cycles" pattern="[0-9]" value="0">
                            <input required type="hidden" name="frequency" pattern="[0-9]" value="3">

                            <input type="image" src="https://my.payfast.io/images/buttons/Subscribe/Primary-Large-Subscribe.png" alt="Subscribe" title="Subscribe with Payfast" class="mx-auto">
                        </form>
                    </div>

                    <div class="alert alert-info text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Set up automatic monthly donations via PayFast</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold mb-2">Bank Transfer Details</h2>
                    <p class="text-gray-600">
                        Prefer to make a direct bank transfer? Use the details below
                    </p>
                </div>

                <div class="max-w-md mx-auto">
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-300">
                                    <span class="font-semibold">Account Name:</span>
                                    <span class="font-mono">Pioneer Missions Africa</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-300">
                                    <span class="font-semibold">Bank:</span>
                                    <span class="font-mono">Standard Bank</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-300">
                                    <span class="font-semibold">Account Number:</span>
                                    <span class="font-mono">146865340</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="font-semibold">Branch Code:</span>
                                    <span class="font-mono">051001</span>
                                </div>
                                <div class="text-center pt-2">
                                    <span class="text-xs text-gray-600">(Electronic Payments)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Support -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">
                <h3 class="text-xl font-semibold mb-6 text-center">Why Your Support Matters</h3>
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Gospel Literature</h4>
                        <p class="text-gray-600 text-sm">
                            Printing and distributing tracts, books, and Bible study materials across Africa
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Media Ministry</h4>
                        <p class="text-gray-600 text-sm">
                            Producing video sermons, podcasts, and digital content to reach millions online
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Evangelistic Outreach</h4>
                        <p class="text-gray-600 text-sm">
                            Supporting evangelistic campaigns and Bible workers spreading present truth
                        </p>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6 max-w-2xl mx-auto">
                    <p class="text-gray-700 italic text-center">
                        "We do not require tithe payments. We accept donations when God impresses you to support the ministry. Every contribution is a voluntary act of worship and partnership in spreading the Everlasting Gospel."
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('pledge-progress-bar');
    const percentageText = document.getElementById('pledge-percentage');
    const targetPercentage = parseFloat(progressBar.dataset.percentage);

    // Intersection Observer for scroll-triggered animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Animate progress bar
                setTimeout(() => {
                    progressBar.style.width = targetPercentage + '%';
                    percentageText.style.opacity = '1';
                }, 100);

                // Unobserve after animation
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    observer.observe(progressBar.parentElement);
});
</script>
@endpush
@endsection
