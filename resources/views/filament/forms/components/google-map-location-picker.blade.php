<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            map: null,
            marker: null,
            autocomplete: null,
            latField: '{{ $getLatitudeField() }}',
            lngField: '{{ $getLongitudeField() }}',
            cityField: '{{ $getCityField() }}',
            provinceField: '{{ $getProvinceField() }}',
            countryField: '{{ $getCountryField() }}',
            defaultZoom: {{ $getDefaultZoom() }},
            selectedZoom: {{ $getSelectedZoom() }},
            defaultCenter: {{ json_encode($getDefaultCenter()) }},
            height: '{{ $getHeight() }}',

            init() {
                this.loadGoogleMaps();
            },

            loadGoogleMaps() {
                if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                    this.initMap();
                } else {
                    if (!document.getElementById('filament-google-maps-script')) {
                        const script = document.createElement('script');
                        script.id = 'filament-google-maps-script';
                        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places';
                        script.async = true;
                        script.defer = true;
                        script.onload = () => this.initMap();
                        document.head.appendChild(script);
                    } else {
                        setTimeout(() => this.loadGoogleMaps(), 100);
                    }
                }
            },

            initMap() {
                const mapContainer = this.$refs.mapContainer;
                if (!mapContainer) return;

                const lat = $wire.get('data.' + this.latField);
                const lng = $wire.get('data.' + this.lngField);

                const hasCoords = lat && lng && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng));
                const position = hasCoords
                    ? { lat: parseFloat(lat), lng: parseFloat(lng) }
                    : this.defaultCenter;
                const zoom = hasCoords ? this.selectedZoom : this.defaultZoom;

                this.map = new google.maps.Map(mapContainer, {
                    center: position,
                    zoom: zoom,
                    mapTypeId: 'roadmap',
                    mapTypeControl: true,
                    streetViewControl: false,
                });

                this.marker = new google.maps.Marker({
                    position: position,
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    title: 'Drag to adjust location'
                });

                const searchInput = this.$refs.searchInput;
                if (searchInput) {
                    this.autocomplete = new google.maps.places.Autocomplete(searchInput, {
                        fields: ['geometry', 'address_components', 'name']
                    });

                    this.autocomplete.addListener('place_changed', () => {
                        const place = this.autocomplete.getPlace();
                        if (!place.geometry || !place.geometry.location) {
                            return;
                        }

                        const location = place.geometry.location;
                        this.updateLocation(location.lat(), location.lng(), place.address_components);
                    });
                }

                google.maps.event.addListener(this.marker, 'dragend', () => {
                    const pos = this.marker.getPosition();
                    this.updateCoordinates(pos.lat(), pos.lng());
                });

                google.maps.event.addListener(this.map, 'click', (event) => {
                    this.marker.setPosition(event.latLng);
                    this.updateCoordinates(event.latLng.lat(), event.latLng.lng());
                });
            },

            updateLocation(lat, lng, addressComponents) {
                this.marker.setPosition({ lat, lng });
                this.map.setCenter({ lat, lng });
                this.map.setZoom(this.selectedZoom);

                $wire.set('data.' + this.latField, lat);
                $wire.set('data.' + this.lngField, lng);

                if (addressComponents) {
                    let city = '';
                    let province = '';
                    let country = '';

                    addressComponents.forEach(component => {
                        if (component.types.includes('locality')) {
                            city = component.long_name;
                        } else if (component.types.includes('administrative_area_level_1')) {
                            province = component.long_name;
                        } else if (component.types.includes('country')) {
                            country = component.long_name;
                        }
                    });

                    if (!city) {
                        addressComponents.forEach(component => {
                            if (component.types.includes('sublocality') ||
                                component.types.includes('administrative_area_level_2')) {
                                city = component.long_name;
                            }
                        });
                    }

                    if (this.cityField) $wire.set('data.' + this.cityField, city);
                    if (this.provinceField) $wire.set('data.' + this.provinceField, province);
                    if (this.countryField) $wire.set('data.' + this.countryField, country);
                }
            },

            updateCoordinates(lat, lng) {
                $wire.set('data.' + this.latField, lat);
                $wire.set('data.' + this.lngField, lng);
            }
        }"
        class="space-y-3"
    >
        <input
            type="text"
            x-ref="searchInput"
            placeholder="Search for a city or town..."
            class="fi-input block w-full rounded-lg border-none bg-white/0 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ring-1 ring-inset ring-gray-950/10 dark:ring-white/20 ps-3 pe-3"
        />

        <div
            x-ref="mapContainer"
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700"
            :style="'height: ' + height"
        ></div>

        <div class="text-xs text-gray-500 dark:text-gray-400">
            Click on the map or drag the marker to set a location. Use the search box to find a specific place.
        </div>
    </div>
</x-dynamic-component>
