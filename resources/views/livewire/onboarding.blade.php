<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Pioneer Missions Africa!</h1>
        <p class="text-xl text-gray-600">To get started, please choose how you would like to join the network.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Individual/Family Option -->
        <a href="{{ route('network.register.individual') }}" class="group relative block h-full bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-[var(--color-indigo)] hover:shadow-lg transition-all duration-300">
            <div class="flex flex-col items-center text-center h-full">
                <div class="h-16 w-16 bg-indigo-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-indigo-100 transition-colors">
                    <svg class="w-8 h-8 text-[var(--color-indigo)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[var(--color-indigo)] transition-colors">Individual / Family</h2>
                <p class="text-gray-600 mb-6 flex-grow">Add yourself or your family to the map. Connect with other believers in your area.</p>
                <span class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 group-hover:bg-indigo-200 transition-colors w-full">
                    Register Individual/Family
                </span>
            </div>
        </a>

        <!-- Fellowship/Group Option -->
        <a href="{{ route('network.register.fellowship') }}" class="group relative block h-full bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-[var(--color-pma-green)] hover:shadow-lg transition-all duration-300">
            <div class="flex flex-col items-center text-center h-full">
                <div class="h-16 w-16 bg-green-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-[var(--color-pma-green)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[var(--color-pma-green)] transition-colors">Fellowship / Group</h2>
                <p class="text-gray-600 mb-6 flex-grow">Register your home church, bible study group, or ministry fellowship on the map.</p>
                <span class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 group-hover:bg-green-200 transition-colors w-full">
                    Register Fellowship
                </span>
            </div>
        </a>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900 underline">
            Skip for now, take me to the dashboard
        </a>
    </div>
</div>
