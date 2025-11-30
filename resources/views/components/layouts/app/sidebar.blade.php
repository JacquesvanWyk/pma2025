<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="bg-green-700 dark:bg-green-800 border-r border-green-600 dark:border-green-900">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('dashboard') }}"
                    logo="{{ url('images/PMALogoDarkText.png') }}"
                />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="text-white">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="text-white hover:text-white">{{ __('Dashboard') }}</flux:sidebar.item>

                @php
                    $individualProfile = auth()->user()->individualProfile;
                    $firstFellowship = auth()->user()->fellowshipProfiles()->first();
                    $firstMinistry = auth()->user()->ministries()->first();
                    $isNetworkRoute = request()->routeIs('network.*') || request()->routeIs('network.register.*');
                @endphp

                <flux:sidebar.group expandable icon="globe-alt" heading="Network" :expanded="$isNetworkRoute" class="text-white">
                    <flux:sidebar.item icon="map" :href="route('network.index')" :current="request()->routeIs('network.index')" wire:navigate class="text-white hover:text-white">{{ __('Network Map') }}</flux:sidebar.item>

                    <flux:sidebar.item icon="user" :href="$individualProfile ? route('network.edit', $individualProfile) : route('network.register.individual')" :current="request()->routeIs('network.register.individual') || (isset($networkMember) && $networkMember->is($individualProfile))" wire:navigate class="text-white hover:text-white">{{ __('My Individual/Family') }}</flux:sidebar.item>

                    <flux:sidebar.item icon="user-group" :href="$firstFellowship ? route('network.edit', $firstFellowship) : route('network.register.fellowship')" :current="request()->routeIs('network.register.fellowship') || (isset($networkMember) && $networkMember->is($firstFellowship))" wire:navigate class="text-white hover:text-white">{{ __('My Fellowship') }}</flux:sidebar.item>

                    <flux:sidebar.item icon="building-office" :href="$firstMinistry ? route('network.ministry.edit', $firstMinistry) : route('network.register.ministry')" :current="request()->routeIs('network.register.ministry') || request()->routeIs('network.ministry.edit')" wire:navigate class="text-white hover:text-white">{{ __('My Ministry') }}</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile :name="auth()->user()->name" />

                <flux:menu>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @fluxScripts
        @stack('scripts')
    </body>
</html>
