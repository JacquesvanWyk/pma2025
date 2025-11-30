@php
    $networkMembers = \App\Models\NetworkMember::query()->approved()->get();
    $individualCount = $networkMembers->where('type', 'individual')->count();
    $groupCount = $networkMembers->where('type', 'group')->count();
    $totalBelievers = $networkMembers->sum('total_believers');
    $networkMembersJson = json_encode($networkMembers->map(function ($member) {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'type' => $member->type,
            'latitude' => $member->latitude,
            'longitude' => $member->longitude,
        ];
    })->toArray());
@endphp

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Step Indicator -->
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-2">
            @for ($i = 1; $i <= 3; $i++)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                        {{ $step >= $i ? 'bg-[var(--color-pma-green)] text-white' : 'bg-gray-200 text-gray-500' }}">
                        {{ $i }}
                    </div>
                    @if ($i < 3)
                        <div class="w-12 h-1 mx-1 rounded transition-all duration-300
                            {{ $step > $i ? 'bg-[var(--color-pma-green)]' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Step 1: Welcome -->
    @if ($step === 1)
        <div class="text-center">
            <div class="mb-8">
                <div class="w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <img src="{{ url('images/PMALogoDarkText.png') }}" alt="Pioneer Missions Africa" class="w-full h-full object-contain">
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Pioneer Missions Africa!</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    We are a faith-based community committed to sharing the truth of the only true God and His Son, Jesus Christ.
                </p>
                <p class="text-lg text-gray-500 mt-4 max-w-2xl mx-auto">
                    Together, we strive to uplift communities, strengthen faith, and spread hope in every corner of Africa.
                </p>
            </div>

            <button wire:click="nextStep" class="inline-flex items-center gap-2 px-8 py-4 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg transition-all hover:-translate-y-1">
                Get Started
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Step 2: Network Map Preview -->
    @if ($step === 2)
        <div>
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Join Our Network Map</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Connect with believers across Africa. Add yourself or your fellowship to the map so others can find and connect with you.
                </p>
            </div>

            <!-- Mini Map Inline -->
            <div class="mb-8" wire:ignore>
                <div class="relative" id="onboarding-mini-map-container" x-data x-init="$nextTick(() => { if (typeof window.initOnboardingMap === 'function') window.initOnboardingMap(); })">
                    <div id="onboarding-members-data" style="display: none;">{!! $networkMembersJson !!}</div>

                    <div class="relative h-[350px] w-full overflow-hidden rounded-2xl shadow-lg border border-gray-200">
                        <div id="onboarding-map" class="w-full h-full z-0"></div>

                        <div class="absolute bottom-4 left-4 z-[500]">
                            <div class="bg-white/95 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/50">
                                <div class="flex gap-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-[var(--color-indigo)]">{{ number_format($individualCount) }}</div>
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Individuals</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-[var(--color-pma-green)]">{{ number_format($groupCount) }}</div>
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fellowships</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalBelievers) }}</div>
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Believers</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-[var(--color-indigo)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Why join the network?</h3>
                        <ul class="text-gray-600 space-y-1">
                            <li>Find other believers in your area</li>
                            <li>Connect with fellowships and home churches</li>
                            <li>Share your skills and ministry gifts</li>
                            <li>Build a community of faith across Africa</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button wire:click="previousStep" class="inline-flex items-center gap-2 px-6 py-3 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back
                </button>
                <button wire:click="nextStep" class="inline-flex items-center gap-2 px-8 py-4 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white rounded-xl font-semibold shadow-lg transition-all hover:-translate-y-1">
                    Continue
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Step 3: Choose Registration Type -->
    @if ($step === 3)
        <div>
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Add Yourself to the Map</h1>
                <p class="text-lg text-gray-600">Choose how you would like to appear on the network map.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-8">
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

            <div class="flex items-center justify-between">
                <button wire:click="previousStep" class="inline-flex items-center gap-2 px-6 py-3 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back
                </button>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900 underline">
                    Skip for now, take me to the dashboard
                </a>
            </div>
        </div>
    @endif
</div>

@assets
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
@endassets

@push('scripts')
<script>
    window.initOnboardingMap = function() {
        if (typeof L === 'undefined' || typeof L.markerClusterGroup === 'undefined') {
            setTimeout(window.initOnboardingMap, 100);
            return;
        }

        var mapElement = document.getElementById('onboarding-map');
        if (!mapElement || mapElement._leaflet_id) return;

        var map = L.map('onboarding-map', {
            zoomControl: false,
            scrollWheelZoom: false,
            dragging: false,
            touchZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            maxZoom: 10
        }).setView([-29.0, 24.0], 5);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '',
            subdomains: 'abcd',
            maxZoom: 10
        }).addTo(map);

        var markerGroup = L.markerClusterGroup({
            showCoverageOnHover: false,
            zoomToBoundsOnClick: false,
            spiderfyOnMaxZoom: false,
            disableClusteringAtZoom: 11,
            removeOutsideVisibleBounds: true,
            iconCreateFunction: function(cluster) {
                return new L.DivIcon({
                    html: '<div style="background-color: #0A753A; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 3px solid rgba(255,255,255,0.5);"><span>' + cluster.getChildCount() + '</span></div>',
                    className: '',
                    iconSize: new L.Point(36, 36)
                });
            }
        });

        map.addLayer(markerGroup);

        var dataElement = document.getElementById('onboarding-members-data');
        if (dataElement) {
            var members = JSON.parse(dataElement.textContent);
            var markers = [];
            members.forEach(function(member) {
                var isIndividual = member.type === 'individual';
                var color = isIndividual ? '#1E2749' : '#0A753A';
                var icon = L.divIcon({
                    html: '<div style="width:32px;height:32px;background:white;border-radius:50%;border:3px solid ' + color + ';display:flex;align-items:center;justify-content:center;">' + (isIndividual ? '👤' : '⛪') + '</div>',
                    className: '',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });
                markers.push(L.marker([member.latitude, member.longitude], { icon: icon }));
            });
            markerGroup.addLayers(markers);
        }
    };
</script>
@endpush
