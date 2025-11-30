@extends('layouts.public')

@section('title', 'Terms of Service')
@section('description', 'Terms of Service for Pioneer Missions Africa - Rules and guidelines for using our website and services.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="pma-accent-line mx-auto mb-6" style="background: var(--gradient-warm);"></div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Terms of Service
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90">
                Last updated: {{ now()->format('F j, Y') }}
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-20 lg:py-32" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Introduction -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Introduction</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        Welcome to Pioneer Missions Africa. By accessing and using our website (pioneermissionsafrica.co.za), you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our website or services.
                    </p>
                </div>
            </div>

            <!-- Acceptance of Terms -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Acceptance of Terms</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        By using our website, creating an account, joining our network, or accessing our resources, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy.
                    </p>
                </div>
            </div>

            <!-- Use of Services -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Use of Services</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-pma-green);">Permitted Use</h3>
                            <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                                You may use our website and services for:
                            </p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pma-body" style="color: var(--color-olive);">
                                <li>Personal spiritual growth and Bible study</li>
                                <li>Downloading and sharing our free resources for non-commercial purposes</li>
                                <li>Connecting with other believers through our network</li>
                                <li>Participating in prayer room sessions and ministry activities</li>
                                <li>Making donations to support our ministry</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-pma-green);">Prohibited Use</h3>
                            <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                                You may not:
                            </p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pma-body" style="color: var(--color-olive);">
                                <li>Use our services for any unlawful purpose</li>
                                <li>Sell or commercially distribute our free resources without permission</li>
                                <li>Attempt to gain unauthorized access to our systems</li>
                                <li>Upload harmful content, viruses, or malicious code</li>
                                <li>Harass, abuse, or harm other users</li>
                                <li>Misrepresent your identity or affiliation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Accounts -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">User Accounts</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        When you create an account with us, you agree to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li>Provide accurate and complete information</li>
                        <li>Maintain the security of your account credentials</li>
                        <li>Notify us immediately of any unauthorized access</li>
                        <li>Accept responsibility for all activities under your account</li>
                    </ul>
                    <p class="pma-body leading-relaxed mt-4" style="color: var(--color-olive);">
                        We reserve the right to suspend or terminate accounts that violate these terms or engage in inappropriate conduct.
                    </p>
                </div>
            </div>

            <!-- Intellectual Property -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Intellectual Property</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        All content on our website, including text, graphics, logos, images, audio, video, and software, is the property of Pioneer Missions Africa or our content providers and is protected by copyright laws.
                    </p>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        Our resources (tracts, e-books, studies, picture studies) are provided free for personal use and ministry purposes. You may share and distribute these materials freely, provided you do not sell them or claim them as your own work. Commercial reproduction requires our written permission.
                    </p>
                </div>
            </div>

            <!-- Donations -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Donations</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        All donations made to Pioneer Missions Africa are voluntary and will be used to support our ministry activities, including:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li>Creating and distributing gospel resources</li>
                        <li>Supporting evangelistic outreach programs</li>
                        <li>Maintaining and improving our website and services</li>
                        <li>Operating costs of the ministry</li>
                    </ul>
                    <p class="pma-body leading-relaxed mt-4" style="color: var(--color-olive);">
                        Donations are generally non-refundable. If you have concerns about a donation, please contact us.
                    </p>
                </div>
            </div>

            <!-- Network Membership -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Network Membership</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        By joining our Pioneer Network, you agree to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li>Provide truthful information about yourself and your fellowship</li>
                        <li>Conduct yourself respectfully when interacting with other members</li>
                        <li>Allow approved profile information to be visible to other network members</li>
                        <li>Use the network for fellowship and ministry purposes only</li>
                    </ul>
                    <p class="pma-body leading-relaxed mt-4" style="color: var(--color-olive);">
                        Network membership is subject to approval and may be revoked if these guidelines are violated.
                    </p>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Disclaimer</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        Our website and services are provided "as is" without warranties of any kind, either express or implied. We do not guarantee that our services will be uninterrupted, error-free, or secure.
                    </p>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        The spiritual content and teachings on our website represent our understanding of Scripture. We encourage all users to study the Bible for themselves and seek the guidance of the Holy Spirit.
                    </p>
                </div>
            </div>

            <!-- Limitation of Liability -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Limitation of Liability</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        To the fullest extent permitted by law, Pioneer Missions Africa shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our website or services. Our total liability shall not exceed the amount of any donations you have made to us in the past twelve months.
                    </p>
                </div>
            </div>

            <!-- Changes to Terms -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Changes to Terms</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        We reserve the right to modify these Terms of Service at any time. Changes will be effective immediately upon posting to our website. Your continued use of our services after any changes constitutes acceptance of the new terms. We encourage you to review these terms periodically.
                    </p>
                </div>
            </div>

            <!-- Governing Law -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Governing Law</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        These Terms of Service shall be governed by and construed in accordance with the laws of South Africa. Any disputes arising from these terms or your use of our services shall be subject to the exclusive jurisdiction of the courts of South Africa.
                    </p>
                </div>
            </div>

            <!-- Contact Us -->
            <div class="pma-card-elevated">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Contact Us</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        If you have any questions about these Terms of Service, please contact us:
                    </p>
                    <div class="space-y-2 pma-body" style="color: var(--color-olive);">
                        <p><strong>Email:</strong> <a href="mailto:info@pioneermissionsafrica.co.za" class="hover:underline" style="color: var(--color-pma-green);">info@pioneermissionsafrica.co.za</a></p>
                        <p><strong>Phone:</strong> 079 470 3941 / 063 469 8313</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('contact') }}" class="pma-btn pma-btn-primary inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
