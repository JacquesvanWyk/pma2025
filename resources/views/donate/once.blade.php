@extends('layouts.public')

@section('title', 'One-Time Donation - Pioneer Missions Africa')
@section('description', 'Make a one-time donation to support Pioneer Missions Africa ministry work across South Africa and Africa.')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-4">One-Time Donation</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Thank you for choosing to support Pioneer Missions Africa with a one-time gift. Your generous contribution helps spread the Everlasting Gospel across Africa.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Select Your Gift Amount</h2>

                        <form action="#" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="amount" value="100" class="peer sr-only" checked>
                                    <div class="card bg-base-100 border-2 border-gray-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white hover:border-primary transition-all">
                                        <div class="card-body text-center py-6">
                                            <p class="text-2xl font-bold">R100</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="amount" value="250" class="peer sr-only">
                                    <div class="card bg-base-100 border-2 border-gray-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white hover:border-primary transition-all">
                                        <div class="card-body text-center py-6">
                                            <p class="text-2xl font-bold">R250</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="amount" value="500" class="peer sr-only">
                                    <div class="card bg-base-100 border-2 border-gray-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white hover:border-primary transition-all">
                                        <div class="card-body text-center py-6">
                                            <p class="text-2xl font-bold">R500</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="amount" value="1000" class="peer sr-only">
                                    <div class="card bg-base-100 border-2 border-gray-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white hover:border-primary transition-all">
                                        <div class="card-body text-center py-6">
                                            <p class="text-2xl font-bold">R1,000</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div>
                                <label class="cursor-pointer">
                                    <input type="radio" name="amount" value="custom" class="peer sr-only">
                                    <div class="card bg-base-100 border-2 border-gray-200 peer-checked:border-primary transition-all">
                                        <div class="card-body">
                                            <div class="flex items-center gap-4">
                                                <span class="font-semibold">Custom Amount:</span>
                                                <div class="form-control flex-1">
                                                    <label class="input-group">
                                                        <span>R</span>
                                                        <input type="number" name="custom_amount" placeholder="Enter amount" class="input input-bordered w-full" min="1" step="1">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="divider"></div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">Your Information</h3>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">Full Name</span>
                                    </label>
                                    <input type="text" name="donor_name" placeholder="John Doe" class="input input-bordered" required>
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">Email Address</span>
                                    </label>
                                    <input type="email" name="donor_email" placeholder="john@example.com" class="input input-bordered" required>
                                    <label class="label">
                                        <span class="label-text-alt">For donation receipt and updates</span>
                                    </label>
                                </div>

                                <div class="form-control mb-4">
                                    <label class="label">
                                        <span class="label-text font-semibold">Phone Number (Optional)</span>
                                    </label>
                                    <input type="tel" name="donor_phone" placeholder="0794703941" class="input input-bordered">
                                </div>

                                <div class="form-control">
                                    <label class="label cursor-pointer justify-start gap-2">
                                        <input type="checkbox" name="anonymous" class="checkbox checkbox-primary">
                                        <span class="label-text">Make this donation anonymous</span>
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-sm">Please note: You will be redirected to complete payment using our secure banking details.</span>
                            </div>

                            <button type="submit" class="btn btn-primary w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Continue to Payment Details
                            </button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Payment Information</h2>

                        <div class="card bg-base-200 mb-6">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Bank Transfer Details
                                </h3>
                                <div class="space-y-3 text-sm">
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
                                        <span class="font-mono">051001 (Electronic Payments)</span>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-xs">Please use your name as reference for your donation</span>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-200 mb-6">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Proof of Payment
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    After making your donation, please upload your proof of payment to help us process your donation receipt.
                                </p>
                                <div class="form-control">
                                    <input type="file" class="file-input file-input-bordered w-full" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="label">
                                        <span class="label-text-alt">Accepted: PDF, JPG, PNG (Max 5MB)</span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-outline btn-primary btn-sm mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Upload Proof of Payment
                                </button>
                            </div>
                        </div>

                        <div class="card bg-gradient-to-br from-primary to-secondary text-white">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Your Impact</h3>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>R100 provides 50 gospel tracts</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>R250 supports a small study group for a month</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>R500 produces 100 DVDs for distribution</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>R1,000 funds an outreach event</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-6 justify-center">
                            <div class="badge badge-lg gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Secure Payment
                            </div>
                            <div class="badge badge-lg gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Tax Deductible
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-4">Thank You for Your Generosity</h2>
                    <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                        Your one-time donation makes a lasting impact. You will receive a donation receipt via email once your payment is processed.
                    </p>
                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('donate') }}" class="btn btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Donation Options
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline btn-primary">Questions? Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
