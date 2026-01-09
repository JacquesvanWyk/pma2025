@extends('layouts.public')

@section('title', 'Monthly Pledge - Pioneer Missions Africa')
@section('description', 'Become a monthly partner with Pioneer Missions Africa. Provide sustained support for spreading the Everlasting Gospel across Africa.')

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD&vault=true"></script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <span class="inline-block px-6 py-2 rounded-full text-sm pma-heading mb-6"
                  style="background: rgba(255, 255, 255, 0.2); color: white; letter-spacing: 0.05em;">
                MONTHLY PARTNERSHIP
            </span>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Become a Monthly Partner
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                Join our community of faithful partners who provide sustained monthly support to spread the Everlasting Gospel. Your recurring commitment enables us to plan and expand our ministry work with confidence.
            </p>
        </div>
    </div>
</section>

<!-- Progress Goal Section -->
<section class="py-12" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto pma-animate-on-scroll">
            <div class="pma-card-elevated" style="border: 3px solid var(--color-pma-green);">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background: var(--color-pma-green);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="pma-heading text-2xl mb-1" style="color: var(--color-indigo);">Monthly Pledge Goal: R35,000</h3>
                            <p class="pma-body" style="color: var(--color-olive);">Current pledges: R22,400 (64% of goal)</p>
                        </div>
                    </div>
                    <div class="pma-progress">
                        <div class="pma-progress-bar" style="width: 64%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="py-20" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-5 gap-12 max-w-7xl mx-auto">
            <!-- Left Column - Form -->
            <div class="lg:col-span-3">
                <div class="pma-card-elevated pma-animate-on-scroll">
                    <div class="p-8 lg:p-12">
                        <h2 class="pma-heading text-3xl mb-8" style="color: var(--color-indigo);">Choose Your Monthly Pledge</h2>

                        <form action="#" method="POST" class="space-y-8">
                            @csrf

                            <!-- Amount Selection -->
                            <div class="space-y-4">
                                <label class="block cursor-pointer">
                                    <input type="radio" name="monthly_amount" value="50" class="peer sr-only">
                                    <div class="pma-card border-2 border-gray-200 peer-checked:border-4 transition-all" style="peer-checked:border-color: var(--color-pma-green);">
                                        <div class="p-4 flex items-center justify-between">
                                            <div>
                                                <p class="pma-heading text-2xl" style="color: var(--color-indigo);">R50/month</p>
                                                <p class="pma-body text-sm" style="color: var(--color-olive);">Gospel Supporter</p>
                                            </div>
                                            <p class="pma-body text-sm" style="color: var(--color-olive);">R600/year</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="monthly_amount" value="100" class="peer sr-only" checked>
                                    <div class="pma-card border-2 border-gray-200 peer-checked:border-4 transition-all" style="peer-checked:border-color: var(--color-pma-green);">
                                        <div class="p-4 flex items-center justify-between">
                                            <div>
                                                <p class="pma-heading text-2xl" style="color: var(--color-indigo);">R100/month</p>
                                                <p class="pma-body text-sm" style="color: var(--color-olive);">Ministry Partner</p>
                                            </div>
                                            <p class="pma-body text-sm" style="color: var(--color-olive);">R1,200/year</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="monthly_amount" value="250" class="peer sr-only">
                                    <div class="pma-card border-2 border-gray-200 peer-checked:border-4 transition-all" style="peer-checked:border-color: var(--color-pma-green);">
                                        <div class="p-4 flex items-center justify-between">
                                            <div>
                                                <p class="pma-heading text-2xl" style="color: var(--color-indigo);">R250/month</p>
                                                <p class="pma-body text-sm" style="color: var(--color-olive);">Mission Advocate</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="pma-body text-sm mb-1" style="color: var(--color-olive);">R3,000/year</p>
                                                <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                                                      style="background: var(--color-pma-green); color: white;">Popular</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="monthly_amount" value="500" class="peer sr-only">
                                    <div class="pma-card border-2 border-gray-200 peer-checked:border-4 transition-all" style="peer-checked:border-color: var(--color-pma-green);">
                                        <div class="p-4 flex items-center justify-between">
                                            <div>
                                                <p class="pma-heading text-2xl" style="color: var(--color-indigo);">R500/month</p>
                                                <p class="pma-body text-sm" style="color: var(--color-olive);">Champion Partner</p>
                                            </div>
                                            <p class="pma-body text-sm" style="color: var(--color-olive);">R6,000/year</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="monthly_amount" value="custom" class="peer sr-only">
                                    <div class="pma-card border-2 border-gray-200 peer-checked:border-4 transition-all" style="peer-checked:border-color: var(--color-pma-green);">
                                        <div class="p-4">
                                            <div class="flex items-center gap-4 flex-wrap">
                                                <span class="pma-heading-light" style="color: var(--color-indigo);">Custom Amount:</span>
                                                <div class="flex-1 min-w-[200px]">
                                                    <div class="flex items-center rounded-lg border border-gray-300">
                                                        <span class="px-3 py-2 pma-heading-light text-sm" style="color: var(--color-indigo);">R</span>
                                                        <input type="number" name="custom_monthly_amount" placeholder="Enter amount"
                                                               class="flex-1 px-2 py-2 border-0 focus:outline-none pma-body" min="10" step="1">
                                                        <span class="px-3 py-2 pma-body text-sm" style="color: var(--color-olive);">/month</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="pma-divider"></div>

                            <!-- Personal Information -->
                            <div>
                                <h3 class="pma-heading text-xl mb-6" style="color: var(--color-indigo);">Your Information</h3>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Full Name</label>
                                        <input type="text" name="partner_name" placeholder="John Doe"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                               style="focus:ring-color: var(--color-pma-green);" required>
                                    </div>

                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Email Address</label>
                                        <input type="email" name="partner_email" placeholder="john@example.com"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                               style="focus:ring-color: var(--color-pma-green);" required>
                                        <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">For monthly statements and ministry updates</p>
                                    </div>

                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Phone Number</label>
                                        <input type="tel" name="partner_phone" placeholder="0794703941"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                               style="focus:ring-color: var(--color-pma-green);" required>
                                        <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">Required for debit order authorization</p>
                                    </div>

                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Preferred Debit Order Date</label>
                                        <select name="debit_date"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                                style="focus:ring-color: var(--color-pma-green);" required>
                                            <option value="">Select date</option>
                                            <option value="1">1st of each month</option>
                                            <option value="7">7th of each month</option>
                                            <option value="15">15th of each month</option>
                                            <option value="25">25th of each month</option>
                                            <option value="last">Last day of month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pma-divider"></div>

                            <!-- Banking Information -->
                            <div>
                                <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Banking Information</h3>
                                <p class="pma-body text-sm mb-6" style="color: var(--color-olive);">
                                    Please provide your banking details for the monthly debit order. All information is securely processed and encrypted.
                                </p>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Bank Name</label>
                                        <select name="bank_name"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                                style="focus:ring-color: var(--color-pma-green);" required>
                                            <option value="">Select your bank</option>
                                            <option value="absa">ABSA</option>
                                            <option value="capitec">Capitec</option>
                                            <option value="fnb">FNB</option>
                                            <option value="nedbank">Nedbank</option>
                                            <option value="standard">Standard Bank</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Account Holder Name</label>
                                        <input type="text" name="account_holder" placeholder="John Doe"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                               style="focus:ring-color: var(--color-pma-green);" required>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Account Number</label>
                                            <input type="text" name="account_number" placeholder="62XXXXXXXXX"
                                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                                   style="focus:ring-color: var(--color-pma-green);" required>
                                        </div>

                                        <div>
                                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Account Type</label>
                                            <select name="account_type"
                                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                                                    style="focus:ring-color: var(--color-pma-green);" required>
                                                <option value="">Select type</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="savings">Savings</option>
                                                <option value="transmission">Transmission</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="terms_accepted" id="terms_accepted"
                                       class="mt-1 w-5 h-5 rounded border-gray-300 focus:ring-2"
                                       style="color: var(--color-pma-green); focus:ring-color: var(--color-pma-green);" required>
                                <label for="terms_accepted" class="pma-body text-sm" style="color: var(--color-olive);">
                                    I authorize Pioneer Missions Africa to debit my account monthly for the selected amount. I understand I can cancel this authorization at any time by contacting the ministry.
                                </label>
                            </div>

                            <div class="p-4 rounded-lg" style="background: var(--color-cream);">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="pma-body text-sm" style="color: var(--color-olive);">
                                        A debit order mandate form will be sent to you via email for signature. Your first debit will only occur after we receive your signed mandate.
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Submit Monthly Pledge
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Benefits -->
            <div class="lg:col-span-2 space-y-8">
                <!-- PayPal Monthly Option -->
                <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-1">
                    <div class="p-8">
                        <h3 class="pma-heading text-xl mb-4" style="color: var(--color-indigo);">Quick Monthly Setup with PayPal</h3>
                        <p class="pma-body text-sm mb-6" style="color: var(--color-olive);">
                            Set up your monthly pledge quickly and securely with PayPal. Easy cancellation anytime.
                        </p>

                        {{-- Quick Amount Suggestions --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <button type="button" onclick="setMonthlyAmount(50)" class="monthly-amount-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors">
                                <span class="pma-heading text-lg" style="color: var(--color-indigo);">R50</span>
                                <p class="pma-body text-xs" style="color: var(--color-olive);">per month</p>
                            </button>
                            <button type="button" onclick="setMonthlyAmount(100)" class="monthly-amount-btn p-3 border-2 border-[var(--color-pma-green)] bg-[var(--color-pma-green)]/10 rounded-lg text-center">
                                <span class="pma-heading text-lg" style="color: var(--color-indigo);">R100</span>
                                <p class="pma-body text-xs" style="color: var(--color-olive);">per month</p>
                            </button>
                            <button type="button" onclick="setMonthlyAmount(250)" class="monthly-amount-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors">
                                <span class="pma-heading text-lg" style="color: var(--color-indigo);">R250</span>
                                <p class="pma-body text-xs" style="color: var(--color-olive);">per month</p>
                            </button>
                            <button type="button" onclick="setMonthlyAmount(500)" class="monthly-amount-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-[var(--color-pma-green)] transition-colors">
                                <span class="pma-heading text-lg" style="color: var(--color-indigo);">R500</span>
                                <p class="pma-body text-xs" style="color: var(--color-olive);">per month</p>
                            </button>
                        </div>

                        {{-- Custom Amount Input --}}
                        <div class="mb-4">
                            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Or enter custom monthly amount:</label>
                            <div class="flex items-center rounded-lg border border-gray-300">
                                <span class="px-3 py-2 pma-heading-light text-sm" style="color: var(--color-indigo);">R</span>
                                <input type="number" id="customMonthlyAmount" placeholder="100" class="flex-1 px-2 py-2 border-0 focus:outline-none pma-body" min="10" step="1" value="100" onchange="updateMonthlyAmount(this.value)">
                                <span class="px-3 py-2 pma-body text-sm" style="color: var(--color-olive);">/month</span>
                            </div>
                        </div>

                        <div id="paypal-subscription-button-container" class="mb-4"></div>
                        <p class="pma-body text-xs" style="color: var(--color-olive);">
                            You can manage or cancel your subscription anytime from your PayPal account.
                        </p>
                    </div>
                </div>

                <!-- Why Monthly Giving -->
                <div class="pma-card-elevated pma-animate-on-scroll pma-stagger-1" style="background: linear-gradient(135deg, var(--color-pma-green), var(--color-pma-green-dark));">
                    <div class="p-8 text-white">
                        <h3 class="pma-heading text-2xl mb-6">Why Monthly Giving Matters</h3>
                        <ul class="space-y-4 pma-body">
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span><strong>Sustainable Impact:</strong> Monthly support allows us to plan long-term ministry projects with confidence</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span><strong>Greater Reach:</strong> Consistent funding enables us to support more groups and communities</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span><strong>Efficiency:</strong> Reduces administrative costs and allows more funds to go directly to ministry</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span><strong>Convenience:</strong> Automatic giving means you never miss supporting the ministry</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Partner Benefits -->
                <div class="pma-card pma-animate-on-scroll pma-stagger-2">
                    <div class="p-8">
                        <h3 class="pma-heading text-xl mb-6" style="color: var(--color-indigo);">As a Monthly Partner, You'll Receive:</h3>
                        <ul class="space-y-3 pma-body text-sm" style="color: var(--color-olive);">
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Monthly email updates on ministry activities and impact</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Quarterly ministry reports with detailed testimonies</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Annual tax-deductible receipts for your donations</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Priority access to new resources and publications</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Inclusion in our monthly prayer circle</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Invitation to annual partner appreciation events</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Monthly Impact -->
                <div class="pma-card pma-animate-on-scroll pma-stagger-3">
                    <div class="p-8">
                        <h3 class="pma-heading text-xl mb-6" style="color: var(--color-indigo);">Monthly Impact Examples</h3>
                        <div class="space-y-4">
                            <div class="flex gap-4 pb-4 border-b" style="border-color: var(--color-cream-dark);">
                                <div class="pma-display text-4xl" style="color: var(--color-pma-green);">R50</div>
                                <div>
                                    <p class="pma-heading-light text-sm" style="color: var(--color-indigo);">Gospel Literature Package</p>
                                    <p class="pma-body text-xs" style="color: var(--color-olive);">25 tracts + 5 booklets monthly</p>
                                </div>
                            </div>
                            <div class="flex gap-4 pb-4 border-b" style="border-color: var(--color-cream-dark);">
                                <div class="pma-display text-4xl" style="color: var(--color-pma-green);">R100</div>
                                <div>
                                    <p class="pma-heading-light text-sm" style="color: var(--color-indigo);">Study Group Support</p>
                                    <p class="pma-body text-xs" style="color: var(--color-olive);">Materials for one small group</p>
                                </div>
                            </div>
                            <div class="flex gap-4 pb-4 border-b" style="border-color: var(--color-cream-dark);">
                                <div class="pma-display text-4xl" style="color: var(--color-pma-green);">R250</div>
                                <div>
                                    <p class="pma-heading-light text-sm" style="color: var(--color-indigo);">Media Ministry Package</p>
                                    <p class="pma-body text-xs" style="color: var(--color-olive);">20 DVDs for distribution</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="pma-display text-4xl" style="color: var(--color-pma-green);">R500</div>
                                <div>
                                    <p class="pma-heading-light text-sm" style="color: var(--color-indigo);">Community Outreach</p>
                                    <p class="pma-body text-xs" style="color: var(--color-olive);">Monthly event in rural area</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="pma-card pma-animate-on-scroll pma-stagger-4">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-cream-dark);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h3 class="pma-heading text-lg" style="color: var(--color-indigo);">Secure & Flexible</h3>
                        </div>
                        <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">
                            Your banking information is processed securely. You can modify or cancel your monthly pledge at any time by contacting us.
                        </p>
                        <div class="flex gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full text-xs pma-body" style="border: 1px solid var(--color-pma-green); color: var(--color-pma-green);">Bank-level Security</span>
                            <span class="px-3 py-1 rounded-full text-xs pma-body" style="border: 1px solid var(--color-pma-green); color: var(--color-pma-green);">Cancel Anytime</span>
                            <span class="px-3 py-1 rounded-full text-xs pma-body" style="border: 1px solid var(--color-pma-green); color: var(--color-pma-green);">Adjust Amount</span>
                        </div>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="text-center pma-animate-on-scroll pma-stagger-5">
                    <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">Questions about monthly giving?</p>
                    <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary">Contact Our Team</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thank You Section -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-hero-title pma-display text-white mb-6">Thank You for Partnering With Us</h2>
            <p class="pma-body text-xl text-white/90 mb-10">
                Your monthly partnership is invaluable to our ministry. Together, we are spreading the Everlasting Gospel and making a lasting impact across Africa.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="pma-btn pma-btn-secondary inline-flex items-center justify-center"
                   style="background: transparent; border: 2px solid white; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Other Giving Options
                </a>
                <a href="{{ route('donate.once') }}" class="pma-btn pma-btn-primary">Make a One-Time Gift</a>
            </div>
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

    // Amount selection functions
    window.setMonthlyAmount = function(amount) {
        document.getElementById('customMonthlyAmount').value = amount;
        updateMonthlyAmount(amount);
    };

    window.updateMonthlyAmount = function(amount) {
        amount = parseFloat(amount) || 100;
        if (amount < 10) amount = 10;

        // Update button styles
        document.querySelectorAll('.monthly-amount-btn').forEach(btn => {
            btn.classList.remove('border-[var(--color-pma-green)]', 'bg-[var(--color-pma-green)]/10');
            btn.classList.add('border-gray-200');
        });

        // Re-render PayPal button with new amount
        renderPayPalButton(amount);
    };

    // Render PayPal subscription button
    async function renderPayPalButton(amount) {
        const container = document.getElementById('paypal-subscription-button-container');

        // Clear existing button
        container.innerHTML = '<p class="text-sm text-gray-500 text-center">Loading PayPal...</p>';

        // Create plan via backend
        try {
            const response = await fetch('/paypal/create-plan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ amount: amount })
            });

            const data = await response.json();

            if (!data.plan_id) {
                container.innerHTML = '<p class="text-sm text-red-500 text-center">Failed to load PayPal. Please refresh.</p>';
                return;
            }

            // Clear loading message
            container.innerHTML = '';

            // Render PayPal button with the plan
            paypal.Buttons({
                createSubscription: function(data, actions) {
                    return actions.subscription.create({
                        'plan_id': data.plan_id
                    });
                },
                onApprove: function(data, actions) {
                    alert('Thank you for your monthly pledge! Subscription ID: ' + data.subscriptionID);
                    window.location.href = '{{ route('pledge') }}';
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                    alert('An error occurred. Please try again.');
                }
            }).render('#paypal-subscription-button-container');

        } catch (error) {
            console.error('Failed to create PayPal plan:', error);
            container.innerHTML = '<p class="text-sm text-red-500 text-center">Failed to load PayPal. Please try again.</p>';
        }
    }

    // Initial render with default amount
    renderPayPalButton(100);
});
</script>
@endpush
@endsection
