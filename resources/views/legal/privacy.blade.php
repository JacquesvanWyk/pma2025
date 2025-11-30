@extends('layouts.public')

@section('title', 'Privacy Policy')
@section('description', 'Privacy Policy for Pioneer Missions Africa - How we collect, use, and protect your personal information.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="pma-accent-line mx-auto mb-6" style="background: var(--gradient-warm);"></div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Privacy Policy
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
                        Pioneer Missions Africa ("we", "our", or "us") respects your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your information when you visit our website or use our services.
                    </p>
                </div>
            </div>

            <!-- Information We Collect -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Information We Collect</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-pma-green);">Personal Information</h3>
                            <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                                When you register an account, join our network, or contact us, we may collect:
                            </p>
                            <ul class="list-disc list-inside mt-2 space-y-1 pma-body" style="color: var(--color-olive);">
                                <li>Name and contact information (email, phone number)</li>
                                <li>Location information (city, province, country)</li>
                                <li>Account credentials (username, password)</li>
                                <li>Fellowship or ministry information (if applicable)</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-pma-green);">Automatically Collected Information</h3>
                            <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                                When you visit our website, we may automatically collect certain information including your IP address, browser type, device information, and pages visited. This helps us improve our website and services.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How We Use Your Information -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">How We Use Your Information</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li>Provide and maintain our services</li>
                        <li>Process your network registration and manage your account</li>
                        <li>Respond to your inquiries and prayer requests</li>
                        <li>Send you updates about our ministry (with your consent)</li>
                        <li>Connect believers with fellowship groups in their area</li>
                        <li>Improve our website and services</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </div>
            </div>

            <!-- Information Sharing -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Information Sharing</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li><strong>Network Directory:</strong> If you join our network, your approved profile information may be visible to other network members to facilitate fellowship connections.</li>
                        <li><strong>Service Providers:</strong> We may share information with trusted service providers who assist us in operating our website (such as hosting providers).</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information if required by law or to protect the rights and safety of our ministry and users.</li>
                    </ul>
                </div>
            </div>

            <!-- Data Security -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Data Security</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.
                    </p>
                </div>
            </div>

            <!-- Your Rights -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Your Rights</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pma-body" style="color: var(--color-olive);">
                        <li>Access the personal information we hold about you</li>
                        <li>Request correction of inaccurate information</li>
                        <li>Request deletion of your personal information</li>
                        <li>Withdraw consent for communications at any time</li>
                        <li>Update your network profile or account settings</li>
                    </ul>
                    <p class="pma-body leading-relaxed mt-4" style="color: var(--color-olive);">
                        To exercise any of these rights, please contact us using the information below.
                    </p>
                </div>
            </div>

            <!-- Cookies -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Cookies</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        Our website uses cookies to enhance your browsing experience. Cookies are small text files stored on your device that help us remember your preferences and understand how you use our website. You can control cookie settings through your browser preferences.
                    </p>
                </div>
            </div>

            <!-- Children's Privacy -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Children's Privacy</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        Our services are not directed to children under the age of 13. We do not knowingly collect personal information from children. If you believe we have inadvertently collected information from a child, please contact us immediately so we can delete it.
                    </p>
                </div>
            </div>

            <!-- Changes to This Policy -->
            <div class="pma-card-elevated mb-8">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Changes to This Policy</h2>
                    <p class="pma-body leading-relaxed" style="color: var(--color-olive);">
                        We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.
                    </p>
                </div>
            </div>

            <!-- Contact Us -->
            <div class="pma-card-elevated">
                <div class="p-8 lg:p-12">
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Contact Us</h2>
                    <p class="pma-body leading-relaxed mb-4" style="color: var(--color-olive);">
                        If you have any questions about this Privacy Policy or our data practices, please contact us:
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
