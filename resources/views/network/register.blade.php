<x-layouts.app :title="$type === 'individual' ? 'Register Individual or Family' : 'Register Fellowship Group'">
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
            <div class="pma-card-elevated p-8" x-data="{ 
                languages: {{ json_encode(old('languages', isset($networkMember) ? $networkMember->languages->pluck('id')->toArray() : [])) }},
                imagePreview: '{{ isset($networkMember) && $networkMember->image_path ? asset('storage/' . $networkMember->image_path) : '' }}',
                handleImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imagePreview = URL.createObjectURL(file);
                    }
                }
            }">
                <form action="{{ isset($networkMember) ? route('network.update', $networkMember) : route('network.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @if(isset($networkMember))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="type" value="{{ $type }}">

                    <!-- Basic Information -->
                    <div>
                        <h2 class="pma-heading text-2xl mb-6" style="color: var(--color-indigo);">
                            {{ $type === 'individual' ? 'Individual/Family Information' : 'Group Information' }}
                        </h2>

                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    {{ $type === 'individual' ? 'Family Name' : 'Group Name' }} *
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

                            <!-- Profile Picture -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Profile Picture (Optional)
                                </label>
                                
                                <!-- Image Preview Area -->
                                <div class="flex items-center gap-4 mb-3" x-show="imagePreview">
                                    <img :src="imagePreview" alt="Profile Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                                    <button type="button" @click="imagePreview = ''; $refs.fileInput.value = ''" class="text-xs text-red-600 hover:underline">Remove</button>
                                </div>

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    x-ref="fileInput"
                                    @change="handleImage($event)"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[var(--color-pma-green)] file:text-white hover:file:bg-[var(--color-pma-green-dark)]"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">JPG or PNG, max 2MB.</p>
                                @error('image')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($type === 'individual')
                            <!-- Professional Skills -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Professional Trade / Skills (Optional)
                                </label>
                                <input
                                    type="text"
                                    name="professional_skills"
                                    value="{{ old('professional_skills', isset($networkMember) && $networkMember->professional_skills ? implode(', ', $networkMember->professional_skills) : '') }}"
                                    placeholder="e.g. Plumber, Accountant, Web Developer"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">List your profession or trade to help us identify resources within the Body of Christ. Separate with commas.</p>
                                @error('professional_skills')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ministry Skills -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Ministry Gifts & Service (Optional)
                                </label>
                                <input
                                    type="text"
                                    name="ministry_skills"
                                    value="{{ old('ministry_skills', isset($networkMember) && $networkMember->ministry_skills ? implode(', ', $networkMember->ministry_skills) : '') }}"
                                    placeholder="e.g. Preaching, Youth Ministry, Music, Hospitality"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">How do you serve the body? Separate with commas.</p>
                                @error('ministry_skills')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif

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
                                    placeholder="e.g., Sabbaths 10:00 AM"
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
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach(\App\Models\Language::orderBy('name')->get() as $language)
                                        <label class="cursor-pointer group relative">
                                            <input type="checkbox" name="languages[]" value="{{ $language->id }}" class="peer sr-only" x-model="languages">
                                            <div class="px-3 py-2 rounded-lg border text-sm text-center transition-all duration-200
                                                peer-checked:bg-[var(--color-pma-green)] peer-checked:text-white peer-checked:border-[var(--color-pma-green)]
                                                bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50">
                                                {{ $language->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="pma-body text-xs mt-2" style="color: var(--color-olive);">Select all that apply.</p>
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
        // Define globally for Google Maps callback
        window.initRegistrationMap = function() {
            const mapContainer = document.getElementById('registration-map');
            const addressInput = document.getElementById('location-search-input');

            // Safety check
            if (!mapContainer || !addressInput) {
                return;
            }

            // Prevent double initialization on the same element
            if (mapContainer.dataset.initialized === 'true') {
                return;
            }

            console.log('Initializing registration map...');

            // Get existing coordinates or use default
            const latInput = document.getElementById('latitude-input');
            const lngInput = document.getElementById('longitude-input');
            
            const existingLat = latInput ? parseFloat(latInput.value) : null;
            const existingLng = lngInput ? parseFloat(lngInput.value) : null;

            const defaultCenter = { lat: -29.0, lng: 24.0 }; // Central SA
            const position = (existingLat && existingLng)
                ? { lat: existingLat, lng: existingLng }
                : defaultCenter;

            const initialZoom = (existingLat && existingLng) ? 12 : 5;

            // Create map
            const map = new google.maps.Map(mapContainer, {
                center: position,
                zoom: initialZoom,
                mapTypeId: 'roadmap',
                mapTypeControl: true,
                streetViewControl: false,
            });

            // Create draggable marker
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                title: 'Drag to adjust location'
            });

            // Show existing location if available
            if (existingLat && existingLng) {
                const cityVal = document.getElementById('city-input')?.value;
                const provinceVal = document.getElementById('province-input')?.value;
                const countryVal = document.getElementById('country-input')?.value;

                if (cityVal) {
                    const displayCity = document.getElementById('display-city');
                    if(displayCity) displayCity.textContent = cityVal;
                    
                    const displayProvince = document.getElementById('display-province');
                    if(displayProvince) displayProvince.textContent = provinceVal || '-';
                    
                    const displayCountry = document.getElementById('display-country');
                    if(displayCountry) displayCountry.textContent = countryVal || '-';
                    
                    const locationDisplay = document.getElementById('selected-location');
                    if(locationDisplay) locationDisplay.classList.remove('hidden');
                }
            }

            // Initialize autocomplete
            const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                fields: ['geometry', 'address_components', 'name']
            });

            // Handle place selection
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

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
                marker.setPosition(location);
                map.setCenter(location);
                map.setZoom(12);

                // Update form fields
                if(latInput) latInput.value = location.lat();
                if(lngInput) lngInput.value = location.lng();
                
                const cityInput = document.getElementById('city-input');
                if(cityInput) cityInput.value = city;
                
                const provinceInput = document.getElementById('province-input');
                if(provinceInput) provinceInput.value = province;
                
                const countryInput = document.getElementById('country-input');
                if(countryInput) countryInput.value = country;

                // Update display
                const displayCity = document.getElementById('display-city');
                if(displayCity) displayCity.textContent = city || '-';
                
                const displayProvince = document.getElementById('display-province');
                if(displayProvince) displayProvince.textContent = province || '-';
                
                const displayCountry = document.getElementById('display-country');
                if(displayCountry) displayCountry.textContent = country || '-';
                
                const locationDisplay = document.getElementById('selected-location');
                if(locationDisplay) locationDisplay.classList.remove('hidden');
            });

            // Handle marker drag
            google.maps.event.addListener(marker, 'dragend', function() {
                const newPosition = marker.getPosition();
                if(latInput) latInput.value = newPosition.lat();
                if(lngInput) lngInput.value = newPosition.lng();
            });

            // Handle map click
            google.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
                if(latInput) latInput.value = event.latLng.lat();
                if(lngInput) lngInput.value = event.latLng.lng();
            });

            // Mark as initialized
            mapContainer.dataset.initialized = 'true';
        };

        // Function to check/load Google Maps
        function loadGoogleMapsApi() {
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                // Only load if not already loading/loaded
                if (!document.getElementById('google-maps-script')) {
                    console.log('Loading Google Maps API...');
                    const script = document.createElement('script');
                    script.id = 'google-maps-script';
                    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initRegistrationMap';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }
            } else {
                // Already loaded, init directly
                // Use a small delay to ensure DOM elements from Livewire transition are ready
                setTimeout(window.initRegistrationMap, 100);
                // Retry once more just in case
                setTimeout(window.initRegistrationMap, 500);
            }
        }

        // Run on initial load
        loadGoogleMapsApi();

        // Run on Livewire navigation (SPA transition)
        document.addEventListener('livewire:navigated', loadGoogleMapsApi);
    </script>
    @endpush
</x-layouts.app>
