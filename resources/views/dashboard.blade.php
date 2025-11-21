<x-layouts.app :title="__('Dashboard')">
    @if(session('message'))
    <flux:callout variant="info" class="mb-6">
        {{ session('message') }}
    </flux:callout>
    @endif

    <!-- Network Registration Section -->
    <div class="bg-white rounded-xl shadow-sm p-8">
        <h2 class="text-2xl font-bold text-zinc-900 mb-2">Join the Believer Network</h2>
        <p class="text-base text-zinc-600 mb-8">Connect with fellow believers across Africa. Choose how you'd like to register:</p>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Individual Registration -->
            <a href="{{ route('network.register.individual') }}" class="block group">
                <div class="bg-white rounded-xl border-2 border-zinc-200 text-center hover:border-zinc-400 hover:shadow-lg transition-all cursor-pointer p-8">
                    <svg class="h-20 w-20 mx-auto mb-6 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="text-xl font-bold text-zinc-900 mb-3">Individual Believer</h3>
                    <p class="text-sm text-zinc-600 mb-6">Join as an individual believer and connect with others in your area</p>
                    <flux:button variant="primary">Register as Individual</flux:button>
                </div>
            </a>

            <!-- Fellowship Registration -->
            <a href="{{ route('network.register.fellowship') }}" class="block group">
                <div class="bg-white rounded-xl border-2 border-zinc-200 text-center hover:border-zinc-400 hover:shadow-lg transition-all cursor-pointer p-8">
                    <svg class="h-20 w-20 mx-auto mb-6 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-zinc-900 mb-3">Fellowship Group</h3>
                    <p class="text-sm text-zinc-600 mb-6">Register a fellowship group or congregation</p>
                    <flux:button variant="primary">Register Fellowship</flux:button>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>
