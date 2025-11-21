<div class="space-y-6">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Location Type Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">I am a/an:</label>
        <div class="flex gap-4">
            <label class="flex items-center">
                <input type="radio" wire:model.live="locationType" value="individual" class="radio radio-primary">
                <span class="ml-2">Individual</span>
            </label>
            <label class="flex items-center">
                <input type="radio" wire:model.live="locationType" value="fellowship" class="radio radio-primary">
                <span class="ml-2">Fellowship</span>
            </label>
            <label class="flex items-center">
                <input type="radio" wire:model.live="locationType" value="ministry" class="radio radio-primary">
                <span class="ml-2">Ministry</span>
            </label>
        </div>
    </div>

    <!-- Location Search -->
    <div>
        <label for="location-search" class="block text-sm font-medium text-gray-700 mb-2">
            Search for your city or town
        </label>
        <input
            type="text"
            id="location-search"
            placeholder="Enter your city or town (e.g., Cape Town, Johannesburg)"
            class="input input-bordered w-full"
        >
        <p class="text-xs text-gray-500 mt-1">We only need your general area (city/town), not your street address.</p>
    </div>

    <!-- Map Container -->
    <div>
        <div id="map" class="w-full h-96 rounded-lg border border-gray-300"></div>
    </div>

    <!-- Selected Location Display -->
    @if($city)
        <div class="bg-base-200 p-4 rounded-lg">
            <h4 class="font-semibold mb-2">Selected Location:</h4>
            <p class="text-sm">
                <strong>City:</strong> {{ $city }}<br>
                @if($province)
                    <strong>Province/State:</strong> {{ $province }}<br>
                @endif
                <strong>Country:</strong> {{ $country }}
            </p>
        </div>
    @endif

    <!-- Save Button -->
    <div class="flex justify-end gap-2">
        <button
            type="button"
            wire:click="saveLocation"
            class="btn btn-primary"
            @if(!$city || !$latitude || !$longitude) disabled @endif
        >
            Save Location
        </button>
    </div>

    @push('scripts')
    <script>
        let map;
        let marker;
        let autocomplete;

        function initMap() {
            // Default center (South Africa)
            const defaultCenter = { lat: -30.5595, lng: 22.9375 };

            @if($latitude && $longitude)
                const initialCenter = { lat: {{ floatval($latitude) }}, lng: {{ floatval($longitude) }} };
                const initialZoom = 10;
            @else
                const initialCenter = defaultCenter;
                const initialZoom = 5;
            @endif

            // Initialize map
            map = new google.maps.Map(document.getElementById('map'), {
                center: initialCenter,
                zoom: initialZoom,
                mapTypeControl: false,
                streetViewControl: false,
            });

            // Add marker if location exists
            @if($latitude && $longitude)
                marker = new google.maps.Marker({
                    position: initialCenter,
                    map: map,
                    draggable: false,
                });
            @endif

            // Initialize autocomplete
            const input = document.getElementById('location-search');
            autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['(cities)'],
                componentRestrictions: { country: [] }, // Allow all countries
            });

            // Listen for place selection
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    alert('No location found for this search. Please try again.');
                    return;
                }

                // Extract location details
                const location = place.geometry.location;
                const addressComponents = place.address_components;

                let city = '';
                let province = '';
                let country = '';

                // Parse address components
                addressComponents.forEach(component => {
                    const types = component.types;

                    if (types.includes('locality')) {
                        city = component.long_name;
                    } else if (types.includes('administrative_area_level_1')) {
                        province = component.long_name;
                    } else if (types.includes('country')) {
                        country = component.long_name;
                    }
                });

                // If no locality, try sublocality or administrative_area_level_2
                if (!city) {
                    addressComponents.forEach(component => {
                        if (component.types.includes('sublocality') ||
                            component.types.includes('administrative_area_level_2')) {
                            city = component.long_name;
                        }
                    });
                }

                // Update map
                map.setCenter(location);
                map.setZoom(12);

                // Update or create marker
                if (marker) {
                    marker.setPosition(location);
                } else {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        draggable: false,
                    });
                }

                // Update Livewire component
                @this.updateLocation(
                    city,
                    province,
                    country,
                    location.lat(),
                    location.lng()
                );
            });
        }

        // Load Google Maps API
        if (!window.google || !window.google.maps) {
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        } else {
            initMap();
        }

        // Listen for location saved event
        window.addEventListener('location-saved', event => {
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    </script>
    @endpush
</div>
