@extends('layouts.public')

@section('title', 'Support - Pioneer Missions Africa')
@section('description', 'Learn about how Pioneer Missions Africa supports individuals, groups, and home churches across Africa.')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <h1 class="text-4xl font-bold mb-8 text-center">Support</h1>
                
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 leading-relaxed mb-8">
                        Pioneer Missions Africa is committed to supporting individuals, groups, and home churches across South Africa and Africa in their spiritual journey and understanding of God's truth.
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Who We Support</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Individuals seeking deeper biblical understanding</li>
                            <li>Small groups studying pioneer truths</li>
                            <li>Home churches across Africa</li>
                            <li>Ministries aligned with our theological position</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">How We Support</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Providing study materials and resources</li>
                            <li>Offering theological guidance and counsel</li>
                            <li>Sharing educational content through our media department</li>
                            <li>Publishing materials through our publishing department</li>
                            <li>Promoting biblical health principles</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Our Approach</h2>
                        <p class="text-gray-700 leading-relaxed">
                            We believe in accountability to Christ, not to the ministry. Our support is offered freely, recognizing that God's truth should be shared without financial barriers. We accept donations only when God impresses individuals to support the ministry.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Get Support</h2>
                        <p class="text-gray-700 leading-relaxed">
                            If you're interested in receiving support from Pioneer Missions Africa, please contact us. We're here to help you grow in your understanding of God's truth and connect with like-minded believers.
                        </p>
                    </section>

                    <div class="mt-12 text-center">
                        <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors mr-4">
                            Contact Us
                        </a>
                        <a href="{{ route('resources') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                            View Resources
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection