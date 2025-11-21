<x-layouts.app :title="$type === 'individual' ? 'Register as Individual Believer' : 'Register Fellowship Group'">
    <div class="max-w-4xl mx-auto p-6">
            @if(session('success'))
            <div class="pma-card p-6 mb-8" style="background: var(--color-pma-green); color: white;">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="pma-body">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <!-- Registration Form -->
            <div class="pma-card-elevated p-8">
                <form action="{{ isset($networkMember) ? route('network.update', $networkMember) : route('network.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(isset($networkMember))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="type" value="{{ $type }}">

                    <!-- Basic Information -->
                    <div>
                        <h2 class="pma-heading text-2xl mb-6" style="color: var(--color-indigo);">
                            {{ $type === 'individual' ? 'Your Information' : 'Group Information' }}
                        </h2>

                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    {{ $type === 'individual' ? 'Full Name' : 'Group Name' }} *
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $networkMember->name ?? '') }}"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                @error('name')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Email Address *
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $networkMember->email ?? auth()->user()->email) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                @error('email')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Phone Number (Optional)
                                </label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone', $networkMember->phone ?? '') }}"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                @error('phone')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bio -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    {{ $type === 'individual' ? 'About You (Optional)' : 'About Your Fellowship (Optional)' }}
                                </label>
                                <textarea
                                    name="bio"
                                    rows="4"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">{{ old('bio', $networkMember->bio ?? '') }}</textarea>
                                @error('bio')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Household Members (Individual Only) -->
                            @if($type === 'individual')
                            <div class="pma-card p-6" style="background: rgba(10, 117, 58, 0.05);">
                                <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Household Information</h3>
                                <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">This information helps PMA understand the reach of our network.</p>

                                <div class="mb-4">
                                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                        Total Believers in Household
                                    </label>
                                    <input
                                        type="number"
                                        name="total_believers"
                                        value="{{ old('total_believers', $networkMember->total_believers ?? 1) }}"
                                        min="1"
                                        class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                        style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                        onfocus="this.style.borderColor='var(--color-pma-green)';"
                                        onblur="this.style.borderColor='var(--color-cream-dark)';">
                                    <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">Including yourself, how many believers are in your household?</p>
                                </div>
                            </div>
                            @endif

                            <!-- Meeting Times (Group Only) -->
                            @if($type === 'group')
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Meeting Times (Optional)
                                </label>
                                <textarea
                                    name="meeting_times"
                                    rows="2"
                                    placeholder="e.g., Sundays 10:00 AM"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">{{ old('meeting_times', $networkMember->meeting_times ?? '') }}</textarea>
                            </div>
                            @endif

                            <!-- Languages -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Languages (Optional)
                                </label>
                                <select
                                    name="languages[]"
                                    multiple
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);">
                                    @foreach(\App\Models\Language::orderBy('name')->get() as $language)
                                        <option value="{{ $language->id }}" @selected(isset($networkMember) && $networkMember->languages->contains($language->id))>{{ $language->name }}</option>
                                    @endforeach
                                </select>
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">Hold Ctrl/Cmd to select multiple languages</p>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div>
                        <h2 class="pma-heading text-2xl mb-6" style="color: var(--color-indigo);">
                            Location Information
                        </h2>

                        <div class="pma-card p-6 mb-4" style="background: rgba(10, 117, 58, 0.05);">
                            <p class="pma-body text-sm" style="color: var(--color-olive);">
                                <strong>Privacy Note:</strong> We only need your general area (city/town), not your street address. This helps us connect believers in the same region.
                            </p>
                        </div>

                        <div id="location-picker-container" class="space-y-4">
                            <!-- Location Search -->
                            <div>
                                <label for="location-search-input" class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Search for your city or town *
                                </label>
                                <input
                                    type="text"
                                    id="location-search-input"
                                    placeholder="Enter your city or town (e.g., Cape Town, Johannesburg)"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                            </div>

                            <!-- Map Container -->
                            <div>
                                <div id="registration-map" class="w-full h-96 rounded-lg border-2" style="border-color: var(--color-cream-dark);"></div>
                            </div>

                            <!-- Hidden Fields for Form Submission -->
                            <input type="hidden" name="latitude" id="latitude-input" value="{{ old('latitude', $networkMember->latitude ?? '') }}" required>
                            <input type="hidden" name="longitude" id="longitude-input" value="{{ old('longitude', $networkMember->longitude ?? '') }}" required>
                            <input type="hidden" name="city" id="city-input" value="{{ old('city', $networkMember->city ?? '') }}">
                            <input type="hidden" name="province" id="province-input" value="{{ old('province', $networkMember->province ?? '') }}">
                            <input type="hidden" name="country" id="country-input" value="{{ old('country', $networkMember->country ?? '') }}">

                            <!-- Selected Location Display -->
                            <div id="selected-location" class="hidden pma-card p-4" style="background: rgba(10, 117, 58, 0.1);">
                                <h4 class="pma-heading text-sm mb-2" style="color: var(--color-indigo);">Selected Location:</h4>
                                <p class="pma-body text-sm" style="color: var(--color-olive);">
                                    <strong>City:</strong> <span id="display-city">-</span><br>
                                    <strong>Province/State:</strong> <span id="display-province">-</span><br>
                                    <strong>Country:</strong> <span id="display-country">-</span>
                                </p>
                            </div>

                            @error('latitude')
                                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                            @enderror
                            @error('longitude')
                                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="pma-card p-6" style="background: var(--color-cream);">
                        <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Privacy Settings</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="show_email" value="1" {{ old('show_email', $networkMember->show_email ?? true) ? 'checked' : '' }} class="mr-2">
                                <span class="pma-body text-sm" style="color: var(--color-olive);">Show email publicly</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="show_phone" value="1" {{ old('show_phone', $networkMember->show_phone ?? false) ? 'checked' : '' }} class="mr-2">
                                <span class="pma-body text-sm" style="color: var(--color-olive);">Show phone publicly</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('dashboard') }}" class="pma-btn pma-btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="pma-btn pma-btn-primary">
                            {{ isset($networkMember) ? 'Update Registration' : 'Submit Registration' }}
                        </button>
                    </div>
                </form>
            </div>
    </div>

    @push('scripts')
    <script>
        let registrationMap;
        let registrationMarker;
        let registrationAutocomplete;
        let registrationMapInitialized = false;

        function initRegistrationMap() {
            if (registrationMapInitialized) return;

            const mapContainer = document.getElementById('registration-map');
            const addressInput = document.getElementById('location-search-input');

            if (!mapContainer || !addressInput) {
                console.log('Map container or input not found, retrying...');
                setTimeout(initRegistrationMap, 100);
                return;
            }

            console.log('Initializing registration map...');

            // Get existing coordinates or use default
            const existingLat = parseFloat(document.getElementById('latitude-input').value);
            const existingLng = parseFloat(document.getElementById('longitude-input').value);

            const defaultCenter = { lat: -30.5595, lng: 22.9375 };
            const position = (existingLat && existingLng)
                ? { lat: existingLat, lng: existingLng }
                : defaultCenter;

            const initialZoom = (existingLat && existingLng) ? 10 : 5;

            // Create map
            registrationMap = new google.maps.Map(mapContainer, {
                center: position,
                zoom: initialZoom,
                mapTypeId: 'roadmap',
                mapTypeControl: true,
                streetViewControl: false,
            });

            // Create draggable marker
            registrationMarker = new google.maps.Marker({
                position: position,
                map: registrationMap,
                draggable: true,
                animation: google.maps.Animation.DROP,
                title: 'Drag to adjust location'
            });

            // Show existing location if available
            if (existingLat && existingLng) {
                const cityVal = document.getElementById('city-input').value;
                const provinceVal = document.getElementById('province-input').value;
                const countryVal = document.getElementById('country-input').value;

                if (cityVal) {
                    document.getElementById('display-city').textContent = cityVal;
                    document.getElementById('display-province').textContent = provinceVal || '-';
                    document.getElementById('display-country').textContent = countryVal;
                    document.getElementById('selected-location').classList.remove('hidden');
                }
            }

            // Initialize autocomplete - allows cities, towns, and localities across Africa
            registrationAutocomplete = new google.maps.places.Autocomplete(addressInput, {
                fields: ['geometry', 'address_components', 'name']
                // No restrictions - allows all African countries and all place types
            });

            // Handle place selection
            registrationAutocomplete.addListener('place_changed', function() {
                const place = registrationAutocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    alert('No location found. Please try again.');
                    return;
                }

                const location = place.geometry.location;
                const addressComponents = place.address_components || [];

                let city = '';
                let province = '';
                let country = '';

                // Parse address components
                addressComponents.forEach(component => {
                    if (component.types.includes('locality')) {
                        city = component.long_name;
                    } else if (component.types.includes('administrative_area_level_1')) {
                        province = component.long_name;
                    } else if (component.types.includes('country')) {
                        country = component.long_name;
                    }
                });

                // Fallback for city
                if (!city) {
                    addressComponents.forEach(component => {
                        if (component.types.includes('sublocality') ||
                            component.types.includes('administrative_area_level_2')) {
                            city = component.long_name;
                        }
                    });
                }

                // Update marker and map
                registrationMarker.setPosition(location);
                registrationMap.setCenter(location);
                registrationMap.setZoom(12);

                // Update form fields
                document.getElementById('latitude-input').value = location.lat();
                document.getElementById('longitude-input').value = location.lng();
                document.getElementById('city-input').value = city;
                document.getElementById('province-input').value = province;
                document.getElementById('country-input').value = country;

                // Update display
                document.getElementById('display-city').textContent = city || '-';
                document.getElementById('display-province').textContent = province || '-';
                document.getElementById('display-country').textContent = country || '-';
                document.getElementById('selected-location').classList.remove('hidden');

                console.log('Location updated:', { city, province, country, lat: location.lat(), lng: location.lng() });
            });

            // Handle marker drag
            google.maps.event.addListener(registrationMarker, 'dragend', function() {
                const newPosition = registrationMarker.getPosition();
                document.getElementById('latitude-input').value = newPosition.lat();
                document.getElementById('longitude-input').value = newPosition.lng();
                console.log('Marker dragged to:', newPosition.lat(), newPosition.lng());
            });

            // Handle map click
            google.maps.event.addListener(registrationMap, 'click', function(event) {
                registrationMarker.setPosition(event.latLng);
                document.getElementById('latitude-input').value = event.latLng.lat();
                document.getElementById('longitude-input').value = event.latLng.lng();
            });

            registrationMapInitialized = true;
            console.log('Registration map initialized successfully');
        }

        // Load Google Maps API if not already loaded
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            console.log('Loading Google Maps API...');
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initRegistrationMap';
            script.async = true;
            script.defer = true;
            script.onerror = function() {
                console.error('Failed to load Google Maps API');
            };
            document.head.appendChild(script);
        } else {
            console.log('Google Maps already loaded, initializing...');
            setTimeout(initRegistrationMap, 100);
        }
    </script>
    @endpush
</x-layouts.app>
