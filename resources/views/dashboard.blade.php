<x-layouts.app :title="__('Dashboard')">
    @if(session('message'))
    <flux:callout variant="info" class="mb-6">
        {{ session('message') }}
    </flux:callout>
    @endif

    <!-- Stats Overview -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--color-indigo);">Dashboard Overview</h2>
        <livewire:dashboard.stats-overview />
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Activity -->
        <livewire:dashboard.recent-activity />

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
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Join as Individual</p>
                </div>
            </a>

            <!-- Register Fellowship -->
            <a href="{{ route('network.register.fellowship') }}" class="block group">
                <div class="bg-white rounded-lg border-2 border-gray-200 text-center hover:border-gray-400 hover:shadow-md transition-all cursor-pointer p-6">
                    <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="font-semibold text-sm" style="color: var(--color-indigo);">Join as Fellowship</p>
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
