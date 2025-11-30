<x-layouts.app :title="__('Dashboard')">
    @if(session('message'))
    <flux:callout variant="info" class="mb-6">
        {{ session('message') }}
    </flux:callout>
    @endif

    @if(session('success'))
    <flux:callout variant="success" class="mb-6">
        {{ session('success') }}
    </flux:callout>
    @endif

    @if(auth()->user()->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')))
    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-indigo-800 font-medium">You have admin access</span>
        </div>
        <a href="/admin" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
            Go to Admin Panel
        </a>
    </div>
    @endif

    @php
        $networkMembers = auth()->user()->networkMembers;
    @endphp

    @if($networkMembers->isNotEmpty())
    <div class="space-y-8 mb-8">
        @foreach($networkMembers as $profile)
        <!-- Network Profile Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    @if($profile->image_path)
                        <img src="{{ asset('storage/' . $profile->image_path) }}" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                    @else
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 text-2xl font-bold">
                            {{ mb_substr($profile->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold" style="color: var(--color-indigo);">
                            {{ $profile->name }}
                        </h2>
                        <p class="text-sm mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($profile->status === 'approved') bg-green-100 text-green-800
                                @elseif($profile->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($profile->status) }}
                            </span>
                            <span class="ml-2 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                                {{ $profile->type === 'individual' ? 'Individual' : 'Fellowship' }}
                            </span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('network.edit', $profile) }}" class="px-4 py-2 rounded-lg font-medium transition" style="background: var(--color-pma-green); color: white;">
                    Edit Profile
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Basic Information</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-600">Name:</dt>
                            <dd class="font-medium">{{ $profile->name }}</dd>
                        </div>
                        @if($profile->show_email)
                        <div>
                            <dt class="text-sm text-gray-600">Email:</dt>
                            <dd class="font-medium">{{ $profile->email }}</dd>
                        </div>
                        @endif
                        @if($profile->phone && $profile->show_phone)
                        <div>
                            <dt class="text-sm text-gray-600">Phone:</dt>
                            <dd class="font-medium">{{ $profile->phone }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Location</h3>
                    <dl class="space-y-2">
                        @if($profile->city)
                        <div>
                            <dt class="text-sm text-gray-600">City:</dt>
                            <dd class="font-medium">{{ $profile->city }}</dd>
                        </div>
                        @endif
                        @if($profile->province)
                        <div>
                            <dt class="text-sm text-gray-600">Province:</dt>
                            <dd class="font-medium">{{ $profile->province }}</dd>
                        </div>
                        @endif
                        @if($profile->country)
                        <div>
                            <dt class="text-sm text-gray-600">Country:</dt>
                            <dd class="font-medium">{{ $profile->country }}</dd>
                        </div>
                        @endif
                        @if($profile->type === 'individual' && $profile->total_believers)
                        <div>
                            <dt class="text-sm text-gray-600">Total Believers in Household:</dt>
                            <dd class="font-medium">{{ $profile->total_believers }}</dd>
                        </div>
                        @endif
                        @if($profile->type === 'group' && $profile->meeting_times)
                        <div>
                            <dt class="text-sm text-gray-600">Meeting Times:</dt>
                            <dd class="font-medium">{{ $profile->meeting_times }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($profile->bio)
            <div class="mt-6">
                <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Bio</h3>
                <p class="text-gray-700">{{ $profile->bio }}</p>
            </div>
            @endif

            @if($profile->type === 'individual')
                @if($profile->professional_skills)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Professional Skills</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->professional_skills as $skill)
                            <span class="px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($profile->ministry_skills)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Ministry Gifts</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->ministry_skills as $skill)
                            <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

            @if($profile->languages->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-semibold mb-2" style="color: var(--color-indigo);">Languages</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($profile->languages as $language)
                        <span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">{{ $language->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($profile->status === 'pending')
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Pending Approval:</strong> This profile is currently under review by our team. You'll receive an email notification once it's approved.
                </p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Stats Overview -->
    {{-- <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--color-indigo);">Dashboard Overview</h2>
        <livewire:dashboard.stats-overview />
    </div> --}}

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-2 gap-8 mb-8">
        {{-- <!-- Recent Activity -->
        <livewire:dashboard.recent-activity /> --}}

        <!-- Notifications Widget -->
        <livewire:dashboard.notifications-widget />
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-8 mb-8">
        <h3 class="text-xl font-bold mb-6" style="color: var(--color-indigo);">Quick Actions</h3>

        <div class="grid md:grid-cols-4 gap-4">
            <!-- View Network Feed -->
            <a href="{{ route('network.index') }}" class="block group">
                <div class="bg-white rounded-lg border-2 border-gray-200 text-center hover:border-gray-400 hover:shadow-md transition-all cursor-pointer p-6">
                    <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Network Feed</p>
                </div>
            </a>

            <!-- View Network Map -->
            <a href="{{ route('network.index') }}#network-map" class="block group">
                <div class="bg-white rounded-lg border-2 border-gray-200 text-center hover:border-gray-400 hover:shadow-md transition-all cursor-pointer p-6">
                    <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Network Map</p>
                </div>
            </a>

            <!-- Register Individual -->
            <a href="{{ route('network.register.individual') }}" class="block group">
                <div class="bg-white rounded-lg border-2 border-gray-200 text-center hover:border-gray-400 hover:shadow-md transition-all cursor-pointer p-6">
                    <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Join as Individual/Family</p>
                </div>
            </a>

            <!-- Register Fellowship -->
            <a href="{{ route('network.register.fellowship') }}" class="block group">
                <div class="bg-white rounded-lg border-2 border-gray-200 text-center hover:border-gray-400 hover:shadow-md transition-all cursor-pointer p-6">
                    <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Join as Fellowship/Group</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Getting Started Guide -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-indigo-200 p-8">
        <h3 class="text-xl font-bold mb-4" style="color: var(--color-indigo);">Welcome to Pioneer Missions Africa</h3>
        <p class="mb-4" style="color: var(--color-olive);">
            Connect with fellow believers across Africa through our believer network. Share updates, prayer requests, and testimonies with the community.
        </p>
        <div class="flex gap-4">
            <a href="{{ route('network.index') }}" class="px-6 py-3 rounded-lg font-medium transition" style="background: var(--color-pma-green); color: white;">
                Explore Network
            </a>
            <a href="{{ route('about') }}" class="px-6 py-3 rounded-lg font-medium border-2 transition hover:bg-gray-50" style="border-color: var(--color-indigo); color: var(--color-indigo);">
                Learn More
            </a>
        </div>
    </div>
</x-layouts.app>
