<div class="space-y-0">
    <!-- Filters Section -->
    <section class="relative py-4" style="background: var(--color-cream); border-bottom: 1px solid rgba(0,0,0,0.1); z-index: 1000 !important; position: relative !important;">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Location Search -->
                <div class="md:col-span-2">
                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Search Location
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text"
                               wire:model.live="searchLocation"
                               placeholder="Find believers near..."
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-2 transition-all"
                               style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                    </div>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Type
                    </label>
                    <select wire:model.live="typeFilter"
                            class="w-full px-4 py-2 rounded-lg border-2 transition-all"
                            style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                        <option value="all">All Types</option>
                        <option value="individual">Individuals</option>
                        <option value="group">Fellowship Groups</option>
                    </select>
                </div>

                <!-- Language Filter -->
                <div>
                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                        Language
                    </label>
                    <select wire:model.live="languageFilter"
                            class="w-full px-4 py-2 rounded-lg border-2 transition-all"
                            style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                        <option value="all">All Languages</option>
                        @foreach($availableLanguages as $language)
                            <option value="{{ $language->code }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="relative" style="height: 70vh;">
        <!-- Hidden data element for JavaScript -->
        <div id="network-members-data" style="display: none;">{!! $this->getNetworkMembersJsonProperty() !!}</div>

        <!-- Leaflet Map Container -->
        <div id="network-map" class="w-full h-full"></div>

        <!-- Map Info Panel -->
        <div class="absolute top-4 right-4" style="max-width: 300px; z-index: 999;">
            <div class="pma-card p-4">
                <h3 class="pma-heading text-lg mb-3" style="color: var(--color-indigo);">
                    Network Statistics
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Total Members:</span>
                        <span class="pma-heading text-sm font-semibold">{{ $networkMembers->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Individuals:</span>
                        <span class="pma-heading text-sm font-semibold">{{ $networkMembers->where('type', 'individual')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Groups:</span>
                        <span class="pma-heading text-sm font-semibold">{{ $networkMembers->where('type', 'group')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Network Button -->
        <div class="absolute bottom-4 left-4" style="z-index: 999;">
            <a href="{{ route('network.join') }}" class="pma-btn pma-btn-primary">
                <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Join the Network
            </a>
        </div>
    </div>
</div>

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    let networkMap = null;
    let markerGroup = null;

    function initNetworkMap() {
        if (typeof L === 'undefined') {
            setTimeout(initNetworkMap, 500);
            return;
        }

        // Initialize map centered on South Africa
        networkMap = L.map('network-map').setView([-30.5595, 22.9375], 5);

        // Add CartoDB Voyager tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors © CARTO',
            subdomains: 'abcd',
            maxZoom: 20,
        }).addTo(networkMap);

        // Create marker group
        markerGroup = L.featureGroup().addTo(networkMap);

        // Load initial markers
        updateNetworkMarkers();
    }

    function updateNetworkMarkers() {
        console.log('updateNetworkMarkers called');
        if (!networkMap || !markerGroup) return;

        // Clear existing markers
        markerGroup.clearLayers();

        // Get fresh network members data from the DOM
        const networkMembersElement = document.getElementById('network-members-data');
        if (!networkMembersElement) {
            console.log('network-members-data element not found');
            return;
        }

        const networkMembers = JSON.parse(networkMembersElement.textContent);
        console.log('Network members loaded:', networkMembers.length);
        const markers = [];

        networkMembers.forEach((member) => {
            // Create custom icon based on type
            const icon = L.divIcon({
                html: `<div class="marker-popup" style="background: ${member.type === 'individual' ? 'var(--color-indigo)' : 'var(--color-pma-green)'};
                     color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
                     font-weight: bold; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                    ${member.type === 'individual' ? '👤' : '⛪'}
                </div>`,
                className: 'custom-marker-icon',
                iconSize: [30, 30],
                iconAnchor: [15, 15],
            });

            // Create marker with popup
            const marker = L.marker([member.latitude, member.longitude], { icon: icon })
                .bindPopup(`
                    <div class="p-2" style="min-width: 200px;">
                        <h4 class="font-bold text-lg mb-1" style="color: var(--color-indigo);">${member.name}</h4>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="inline-block px-2 py-1 rounded text-xs"
                                  style="background: ${member.type === 'individual' ? 'var(--color-indigo)' : 'var(--color-pma-green)'}; color: white;">
                                ${member.type === 'individual' ? 'Individual' : 'Fellowship Group'}
                            </span>
                        </p>
                        ${member.languages && member.languages.length > 0 ? `
                            <div class="mb-1">
                                ${member.languages.map(language =>
                                    `<span class="inline-block px-2 py-1 rounded text-xs mr-1"
                                          style="background: var(--color-olive-light); color: white;">
                                        ${language.name}
                                    </span>`
                                ).join('')}
                            </div>
                        ` : ''}
                        <p class="text-sm mb-2">${member.bio ? member.bio.substring(0, 100) + (member.bio.length > 100 ? '...' : '') : ''}</p>
                        <a href="/network/${member.id}"
                           class="inline-flex items-center px-3 py-1 rounded text-sm"
                           style="background: var(--color-pma-green); color: white; text-decoration: none;">
                            View Profile
                        </a>
                    </div>
                `);

            markerGroup.addLayer(marker);
            markers.push(marker);
        });

        // Keep the map at the South Africa overview level - don't auto-zoom to markers
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initNetworkMap);

    // Update markers when Livewire component updates
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized');

        // Watch for changes to the network members data element
        const dataElement = document.getElementById('network-members-data');
        if (dataElement) {
            const observer = new MutationObserver(() => {
                console.log('Data element changed, updating markers');
                updateNetworkMarkers();
            });

            observer.observe(dataElement, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }

        // Also listen for Livewire updates
        Livewire.hook('morph.updated', ({ el, component }) => {
            const hasDataElement = el.querySelector('#network-members-data');
            if (hasDataElement) {
                console.log('Livewire morphed, updating markers');
                setTimeout(() => updateNetworkMarkers(), 100);
            }
        });
    });
</script>
@endpush