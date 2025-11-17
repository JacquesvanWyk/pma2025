@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="/images/hero-africa-landscape.jpg" alt="African landscape" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
        </div>
        
        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 animate-fade-in-down">
                Proclaiming the Everlasting Gospel
            </h1>
            <p class="text-xl sm:text-2xl mb-8 max-w-3xl mx-auto animate-fade-in-up">
                "A knowledge of God is the foundation of all true education and of all true service."
            </p>
            
            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12 animate-fade-in">
                <a href="{{ route('donate') }}" class="inline-flex items-center px-8 py-4 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    Support Our Mission
                </a>
                <a href="{{ route('sermons') }}" class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold rounded-lg shadow-lg border border-white/30 transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Watch Sermons
                </a>
            </div>
            
            {{-- Monthly Pledge Progress --}}
            <div class="max-w-md mx-auto animate-fade-in-up">
                <p class="text-sm mb-2 opacity-90">Monthly Pledge Progress</p>
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-1">
                    <div class="bg-amber-600 h-6 rounded-full flex items-center justify-center text-sm font-semibold" style="width: {{ ($currentPledges / 35000) * 100 }}%">
                        R{{ number_format($currentPledges) }} / R35,000
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    {{-- Welcome Section --}}
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                        Welcome to Pioneer Missions Africa
                    </h2>
                    <p class="text-lg text-gray-700 mb-6">
                        We are a ministry determined in our efforts to proclaim the Everlasting Gospel. 
                        Our mission is to spread the knowledge of the one true God and His Son across Africa, 
                        supporting individuals, groups, and home churches in their spiritual journey.
                    </p>
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-amber-600 mb-1">15+</div>
                            <div class="text-sm text-gray-600">Years Serving</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-amber-600 mb-1">1000+</div>
                            <div class="text-sm text-gray-600">Lives Touched</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-amber-600 mb-1">50+</div>
                            <div class="text-sm text-gray-600">Resources</div>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 font-semibold">
                        Learn More About Us
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
                <div class="order-1 lg:order-2">
                    <img src="/images/ministry-team.jpg" alt="Ministry Team" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Studies --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Featured Studies</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Explore our collection of biblical studies focused on present truth and the pioneering message
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredStudies as $study)
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                {{ $study->category->name }}
                            </span>
                            <span class="text-gray-500 text-sm">{{ $study->read_time }} min read</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $study->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $study->excerpt }}</p>
                        <a href="{{ route('studies.show', $study->slug) }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 font-semibold">
                            Read Study
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('studies') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg transition-colors duration-200">
                    View All Studies
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Latest Sermons --}}
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Latest Sermons</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Watch our latest messages from our YouTube channel
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestSermons as $sermon)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $sermon->youtube_id }}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            class="w-full h-full"
                        ></iframe>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $sermon->title }}</h3>
                        <p class="text-gray-600 mb-2">{{ $sermon->speaker }}</p>
                        <p class="text-sm text-gray-500">{{ $sermon->date_preached->format('F j, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('sermons') }}" class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    View All Sermons
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Resources Section --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Ministry Resources</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Download free resources to support your spiritual journey
                </p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('resources.tracts') }}" class="group bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors duration-200">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tracts</h3>
                    <p class="text-sm text-gray-600">Shareable gospel literature</p>
                </a>
                
                <a href="{{ route('resources.ebooks') }}" class="group bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors duration-200">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">E-Books</h3>
                    <p class="text-sm text-gray-600">Digital books and guides</p>
                </a>
                
                <a href="{{ route('resources.notes') }}" class="group bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors duration-200">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Study Notes</h3>
                    <p class="text-sm text-gray-600">In-depth study materials</p>
                </a>
                
                <a href="{{ route('resources.dvd') }}" class="group bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors duration-200">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 16h4m10 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DVD Ministry</h3>
                    <p class="text-sm text-gray-600">Video resources on DVD</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Support Section --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-amber-500 to-amber-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Support Our Mission</h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto opacity-90">
                    Your support helps us spread the Everlasting Gospel across Africa. 
                    Join us in this vital work of proclaiming present truth.
                </p>
                
                <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto mb-12">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-2">One-Time Gift</h3>
                        <p class="opacity-90 mb-4">Make a single donation to support our ministry</p>
                        <a href="{{ route('donate.once') }}" class="inline-flex items-center px-6 py-3 bg-white text-amber-600 hover:bg-gray-100 font-semibold rounded-lg transition-colors duration-200">
                            Give Now
                        </a>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-2">Monthly Pledge</h3>
                        <p class="opacity-90 mb-4">Commit to regular support of our mission</p>
                        <a href="{{ route('donate.monthly') }}" class="inline-flex items-center px-6 py-3 bg-white text-amber-600 hover:bg-gray-100 font-semibold rounded-lg transition-colors duration-200">
                            Pledge Monthly
                        </a>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-2">Partner With Us</h3>
                        <p class="opacity-90 mb-4">Join us as a ministry partner</p>
                        <a href="{{ route('partner') }}" class="inline-flex items-center px-6 py-3 bg-white text-amber-600 hover:bg-gray-100 font-semibold rounded-lg transition-colors duration-200">
                            Learn More
                        </a>
                    </div>
                </div>
                
                {{-- Testimonial --}}
                <div class="max-w-3xl mx-auto">
                    <blockquote class="text-lg italic opacity-90">
                        "This ministry has been a blessing to our home church. The resources and support 
                        have helped us grow in our understanding of God's truth."
                    </blockquote>
                    <cite class="block mt-4 text-sm">- Brother John, Johannesburg</cite>
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter Section --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-900 rounded-2xl p-8 lg:p-12 text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Stay Connected</h2>
                <p class="text-lg text-gray-300 mb-8">
                    Subscribe to our newsletter for updates, new studies, and ministry news
                </p>
                
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Enter your email" 
                            required
                            class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500"
                        >
                        <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            Subscribe
                        </button>
                    </div>
                    <div class="mt-4">
                        <label class="inline-flex items-center text-gray-300">
                            <input type="radio" name="language" value="en" checked class="mr-2">
                            <span class="mr-4">English</span>
                        </label>
                        <label class="inline-flex items-center text-gray-300">
                            <input type="radio" name="language" value="af" class="mr-2">
                            <span>Afrikaans</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-fade-in-down {
        animation: fade-in-down 1s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 1s ease-out 0.2s both;
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out 0.4s both;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush