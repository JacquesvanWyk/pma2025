<div class="relative space-y-6">
    <!-- Modern Filters Section -->
    <div class="relative z-10 -mb-10 container mx-auto px-6">
        <div class="bg-white/95 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 p-6 max-w-5xl mx-auto transform translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Location Search -->
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-[var(--color-indigo)] uppercase tracking-wider mb-2">
                        Search
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[var(--color-pma-green)] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live="searchLocation"
                               placeholder="Search by name, city, or keyword..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[var(--color-pma-green)] focus:border-transparent outline-none transition-all placeholder-gray-400"
                        >
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-[var(--color-indigo)] uppercase tracking-wider mb-2">
                        Type
                    </label>
                    <div class="relative">
                        <select wire:model.live="typeFilter"
                                class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[var(--color-pma-green)] focus:border-transparent outline-none transition-all appearance-none cursor-pointer"
                        >
                            <option value="all">All Types</option>
                            <option value="individual">Individuals</option>
                            <option value="group">Fellowship Groups</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Language Filter -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-[var(--color-indigo)] uppercase tracking-wider mb-2">
                        Language
                    </label>
                    <div class="relative">
                        <select wire:model.live="languageFilter"
                                class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[var(--color-pma-green)] focus:border-transparent outline-none transition-all appearance-none cursor-pointer"
                        >
                            <option value="all">All Languages</option>
                            @foreach($availableLanguages as $language)
                                <option value="{{ $language->code }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Results Count Badge (Visual only) -->
                <div class="md:col-span-1 flex justify-center pb-2">
                    <div class="flex flex-col items-center">
                        <span class="text-2xl font-bold text-[var(--color-pma-green)]">{{ $networkMembers->count() }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Found</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <section class="relative h-[80vh] w-full overflow-hidden rounded-3xl shadow-inner border border-gray-200/50">
        <!-- Hidden data element for JavaScript -->
        <div id="network-members-data" style="display: none;">{!! $this->getNetworkMembersJsonProperty() !!}</div>

        <!-- Leaflet Map Container -->
        <div id="network-map" class="w-full h-full z-0"></div>

        <!-- Map Info Panel (Floating Statistics) -->
        <div class="absolute top-24 right-4 z-[500] hidden md:block">
            <div class="bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/50 w-72 transition-opacity hover:opacity-100 max-h-[70vh] overflow-y-auto">
                <h3 class="font-bold text-[var(--color-indigo)] mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-pma-green)] animate-pulse"></span>
                    Network Stats
                </h3>
                <div class="space-y-3">
                    <!-- Individuals/Families Section -->
                    <div class="p-2 bg-indigo-50/50 rounded border border-indigo-100/50">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-500 uppercase">Individual/Family</span>
                            <span class="font-bold text-[var(--color-indigo)]">{{ $networkMembers->where('type', 'individual')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-gray-400">Believers</span>
                            <span class="text-sm font-bold text-[var(--color-pma-green)]">{{ number_format($networkMembers->where('type', 'individual')->sum('total_believers')) }}</span>
                        </div>
                    </div>
                    <!-- Fellowship/Groups Section -->
                    <div class="p-2 bg-green-50/50 rounded border border-green-100/50">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-500 uppercase">Fellowship/Group</span>
                            <span class="font-bold text-[var(--color-indigo)]">{{ $networkMembers->where('type', 'group')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-gray-400">Believers</span>
                            <span class="text-sm font-bold text-[var(--color-pma-green)]">{{ number_format($networkMembers->where('type', 'group')->sum('total_believers')) }}</span>
                        </div>
                    </div>

                    <!-- Top Professional Skills -->
                    @if($topProfessionalSkills->count() > 0)
                    <div class="p-2 bg-gray-50/50 rounded border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Top Skills</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($topProfessionalSkills as $skill)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Top Ministry Gifts -->
                    @if($topMinistrySkills->count() > 0)
                    <div class="p-2 bg-gray-50/50 rounded border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Top Ministry Gifts</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($topMinistrySkills as $gift)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-50 text-green-700 border border-green-100">{{ $gift }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Top Languages -->
                    @if($topLanguages->count() > 0)
                    <div class="p-2 bg-gray-50/50 rounded border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Top Languages</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($topLanguages as $language)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">{{ $language }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Geographic Spread -->
                    @if($geographicSpread->count() > 0)
                    <div class="p-2 bg-gray-50/50 rounded border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Locations</div>
                        @foreach($geographicSpread as $country => $provinces)
                        <div class="mb-2 last:mb-0">
                            <div class="flex items-center gap-1 text-xs font-medium text-gray-700 mb-1">
                                <span>📍</span>
                                <span>{{ $country }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1 pl-4">
                                @foreach($provinces as $province => $believerCount)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">{{ $province }}: <span class="font-bold">{{ $believerCount }}</span></span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- New This Month -->
                    @if($newThisMonth > 0)
                    <div class="p-2 bg-gray-50/50 rounded border border-gray-100/50">
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <span>🆕</span>
                            <span>{{ $newThisMonth }} new this month</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Join Network Button (Floating) -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-[500]">
            <a href="{{ route('network.join') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--color-indigo)] hover:bg-[var(--color-indigo-light)] text-white rounded-full font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Yourself to the Map
            </a>
        </div>
    </section>
</div>

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<!-- Leaflet MarkerCluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<!-- Leaflet MarkerCluster JS -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
    let networkMap = null;
    let markerGroup = null;

    function initNetworkMap() {
        if (typeof L === 'undefined' || typeof L.markerClusterGroup === 'undefined') {
            setTimeout(initNetworkMap, 500);
            return;
        }

        // Initialize map centered on South Africa with a cleaner style
        networkMap = L.map('network-map', {
            zoomControl: false,
            scrollWheelZoom: false,
            maxZoom: 12 // Restrict max zoom to prevent street-level detail
        }).setView([-29.0, 24.0], 6); // Better center for SA

        L.control.zoom({
            position: 'topright'
        }).addTo(networkMap);

        // Add CartoDB Voyager tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 12, // Also restrict tile layer max zoom
        }).addTo(networkMap);

        // Initialize Marker Cluster Group instead of Feature Group
        markerGroup = L.markerClusterGroup({
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            // spiderfyOnMaxZoom: true, // Removed to prevent showing individual markers at max zoom
            disableClusteringAtZoom: 13, // Ensure clustering is always active within allowed zoom levels
            removeOutsideVisibleBounds: true,
            iconCreateFunction: function(cluster) {
                var childCount = cluster.getChildCount();
                var c = ' marker-cluster-';
                if (childCount < 10) {
                    c += 'small';
                } else if (childCount < 100) {
                    c += 'medium';
                } else {
                    c += 'large';
                }

                return new L.DivIcon({ 
                    html: '<div style="background-color: var(--color-pma-green); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 4px solid rgba(255,255,255,0.5); box-shadow: 0 4px 6px rgba(0,0,0,0.1);"><span>' + childCount + '</span></div>', 
                    className: 'custom-cluster-icon', 
                    iconSize: new L.Point(40, 40) 
                });
            }
        });
        
        networkMap.addLayer(markerGroup);

        // Load initial markers
        updateNetworkMarkers();
    }

    function maskEmail(email) {
        if (!email) return '';
        let parts = email.split('@');
        if (parts.length < 2) return email;
        let name = parts[0];
        let visible = name.length > 3 ? name.substring(0, 3) : name.substring(0, 1);
        return visible + '****@' + parts[1];
    }

    function maskPhone(phone) {
        if (!phone) return '';
        return phone.substring(0, 3) + '****' + phone.substring(phone.length - 3);
    }

    function updateNetworkMarkers() {
        if (!networkMap || !markerGroup) return;

        // Clear existing markers
        markerGroup.clearLayers();

        // Get fresh network members data from the DOM
        const networkMembersElement = document.getElementById('network-members-data');
        if (!networkMembersElement) return;

        const networkMembers = JSON.parse(networkMembersElement.textContent);
        
        const markers = [];

        networkMembers.forEach((member) => {
            const isIndividual = member.type === 'individual';
            const color = isIndividual ? '#1E2749' : '#0A753A'; // Indigo vs Green
            
            // Modern Custom Icon logic
            let iconContent;
            if (member.image_path) {
                iconContent = `<img src="/storage/${member.image_path}" class="w-full h-full rounded-full object-cover shadow-md box-border" style="border: 3px solid ${color};" alt="${member.name}">`;
            } else {
                iconContent = `
                    <div class="absolute inset-0 rounded-full shadow-lg" style="background: white; border: 3px solid ${color};"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-gray-800 text-lg">
                        ${isIndividual ? '👤' : '⛪'}
                    </div>
                `;
            }

            const icon = L.divIcon({
                html: `
                    <div class="relative group cursor-pointer transition-transform duration-300 hover:scale-110" style="width: 40px; height: 40px;">
                        ${iconContent}
                        ${!member.image_path ? `<div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-white rotate-45"></div>` : ''}
                    </div>
                `,
                className: 'custom-marker-icon-modern', 
                iconSize: [40, 40],
                iconAnchor: [20, 45], // Tip of the pin
                popupAnchor: [0, -45]
            });

            // Create marker with modernized popup
            const marker = L.marker([member.latitude, member.longitude], { icon: icon })
                .bindPopup(`
                    <div class="font-sans p-1 min-w-[220px]">
                        <div class="flex items-start gap-3 mb-3">
                            ${member.image_path ? 
                                `<img src="/storage/${member.image_path}" class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm flex-shrink-0">` 
                                : ''}
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide text-white"
                                          style="background-color: ${color}">
                                        ${isIndividual ? 'Individual' : 'Group'}
                                    </span>
                                    ${member.total_believers > 0 ? `
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1">
                                        <span class="text-xs">👥</span> ${member.total_believers}
                                    </span>
                                    ` : ''}
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 leading-tight">${member.name}</h4>
                            </div>
                        </div>
                        
                        ${member.meeting_times ? `
                            <div class="mb-2 p-2 bg-gray-50 rounded border border-gray-100">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Meeting Times</div>
                                <p class="text-xs text-gray-700">${member.meeting_times}</p>
                            </div>
                        ` : ''}

                        ${member.professional_skills && member.professional_skills.length > 0 ? `
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Trade & Skills</div>
                                <div class="flex flex-wrap gap-1">
                                    ${member.professional_skills.map(skill => 
                                        `<span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">${skill}</span>`
                                    ).join('')}
                                </div>
                            </div>
                        ` : ''}

                        ${member.ministry_skills && member.ministry_skills.length > 0 ? `
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Ministry Gifts</div>
                                <div class="flex flex-wrap gap-1">
                                    ${member.ministry_skills.map(skill => 
                                        `<span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-700 border border-green-100">${skill}</span>`
                                    ).join('')}
                                </div>
                            </div>
                        ` : ''}

                        ${member.languages && member.languages.length > 0 ? `
                            <div class="mb-2">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Languages</div>
                                <div class="flex flex-wrap gap-1">
                                    ${member.languages.map(lang => 
                                        `<span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            ${lang.name}
                                        </span>`
                                    ).join('')}
                                </div>
                            </div>
                        ` : ''}
                        
                        ${member.bio ? `
                            <div x-data="{ expanded: false, hasLongBio: ${member.bio.length > 100} }" class="mb-3">
                                <p x-show="!expanded" class="text-sm text-gray-600 line-clamp-3">${member.bio}</p>
                                <p x-show="expanded" class="text-sm text-gray-600 whitespace-pre-wrap">${member.bio}</p>
                                <button x-show="hasLongBio"
                                        @click="expanded = !expanded"
                                        class="text-xs font-semibold text-[var(--color-pma-green)] hover:underline mt-1 focus:outline-none">
                                    <span x-text="expanded ? 'Show Less' : 'Read All'"></span>
                                </button>
                            </div>
                        ` : '<p class="text-sm text-gray-600 mb-3">No bio available.</p>'}

                        ${(member.website_url || member.facebook_url || member.twitter_url || member.youtube_url) ? `
                            <div class="mb-3">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Online Presence</div>
                                <div class="flex flex-wrap gap-2">
                                    ${member.website_url ? `
                                        <a href="${member.website_url}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-[var(--color-pma-green)] hover:!text-white transition-colors text-xs font-medium"
                                           title="Website">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            Website
                                        </a>
                                    ` : ''}
                                    ${member.facebook_url ? `
                                        <a href="${member.facebook_url}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:!text-white transition-colors text-xs font-medium"
                                           title="Facebook">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Facebook
                                        </a>
                                    ` : ''}
                                    ${member.twitter_url ? `
                                        <a href="${member.twitter_url}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-800 hover:!text-white transition-colors text-xs font-medium"
                                           title="X (Twitter)">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                            X
                                        </a>
                                    ` : ''}
                                    ${member.youtube_url ? `
                                        <a href="${member.youtube_url}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:!text-white transition-colors text-xs font-medium"
                                           title="YouTube">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                            YouTube
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        ` : ''}

                        ${(member.show_email || member.show_phone) ? `
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <!-- Toggleable Contact Info -->
                                <div id="contact-masked-${member.id}" 
                                     class="mb-2 cursor-pointer p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors"
                                     onclick="document.getElementById('contact-full-${member.id}').classList.remove('hidden'); this.classList.add('hidden');">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Contact Details</span>
                                        <span class="text-[10px] text-[var(--color-pma-green)] font-medium">Show</span>
                                    </div>
                                    ${member.show_email && member.email ? `
                                        <div class="text-xs text-gray-500 mt-1 truncate">${maskEmail(member.email)}</div>
                                    ` : ''}
                                    ${member.show_phone && member.phone ? `
                                        <div class="text-xs text-gray-500 mt-0.5">${maskPhone(member.phone)}</div>
                                    ` : ''}
                                </div>

                                <div id="contact-full-${member.id}" class="hidden mb-3 p-2 bg-gray-50 rounded border border-gray-100">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Contact Details</div>
                                    ${member.show_email && member.email ? `
                                        <div class="text-xs text-gray-700 mb-1 select-all font-medium">${member.email}</div>
                                    ` : ''}
                                    ${member.show_phone && member.phone ? `
                                        <div class="text-xs text-gray-700 select-all font-medium">${member.phone}</div>
                                    ` : ''}
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    ${member.show_email && member.email ? `
                                        <a href="mailto:${member.email}" class="flex-1 py-1.5 rounded text-center text-xs font-bold bg-gray-100 text-gray-700 hover:bg-[var(--color-pma-green)] hover:!text-white transition-colors" title="Send Email">
                                            Email
                                        </a>
                                    ` : ''}
                                    ${member.show_phone && member.phone ? `
                                        <a href="tel:${member.phone}" class="flex-1 py-1.5 rounded text-center text-xs font-bold bg-gray-100 text-gray-700 hover:bg-[var(--color-pma-green)] hover:!text-white transition-colors" title="Call">
                                            Call
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `, {
                    closeButton: false,
                    className: 'modern-leaflet-popup',
                    minWidth: 260
                });

            markers.push(marker);
        });

        // Add all markers to cluster group at once
        markerGroup.addLayers(markers);
    }

    // Initialize map when page loads
    function setupMap() {
        if (networkMap) {
            networkMap.remove();
            networkMap = null;
            markerGroup = null;
        }
        initNetworkMap();
        if (networkMap) {
            networkMap.on('popupopen', function (e) {
                let popupDiv = e.popup.getElement().querySelector('.leaflet-popup-content');
                if (popupDiv && typeof Alpine !== 'undefined') {
                    Alpine.initTree(popupDiv);
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', setupMap);

    // Re-initialize map when navigating with wire:navigate
    document.addEventListener('livewire:navigated', setupMap);

    // Update markers when Livewire component updates
    document.addEventListener('livewire:init', () => {
        // Watch for changes to the network members data element
        const dataElement = document.getElementById('network-members-data');
        if (dataElement) {
            const observer = new MutationObserver(() => {
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
                setTimeout(() => updateNetworkMarkers(), 100);
            }
        });
    });
</script>

<style>
    /* Leaflet Popup Customization */
    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 16px;
    }
    .leaflet-popup-tip {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    /* Remove default marker bg if needed */
    .custom-marker-icon-modern {
        background: transparent;
        border: none;
    }
    /* Custom Cluster Icon Style */
    .custom-cluster-icon {
        background: transparent;
        border: none;
    }
</style>
@endpush