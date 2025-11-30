<x-layouts.app :title="isset($ministry) ? 'Edit Ministry' : 'Register Ministry'">
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
                logoPreview: '{{ isset($ministry) && $ministry->logo ? asset('storage/' . $ministry->logo) : '' }}',
                handleLogo(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.logoPreview = URL.createObjectURL(file);
                    }
                }
            }">
                <div class="mb-6">
                    <h1 class="pma-heading text-3xl" style="color: var(--color-indigo);">
                        {{ isset($ministry) ? 'Edit Ministry' : 'Register Your Ministry' }}
                    </h1>
                    <p class="pma-body mt-2" style="color: var(--color-olive);">
                        Add your ministry to the believer network map so others can discover and connect with your work.
                    </p>
                </div>

                <form action="{{ isset($ministry) ? route('network.ministry.update', $ministry) : route('network.store.ministry') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @if(isset($ministry))
                        @method('PUT')
                    @endif

                    <!-- Basic Information -->
                    <div>
                        <h2 class="pma-heading text-2xl mb-6" style="color: var(--color-indigo);">
                            Ministry Information
                        </h2>

                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Ministry Name *
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $ministry->name ?? '') }}"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                @error('name')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Contact Email (Optional)
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $ministry->email ?? auth()->user()?->email) }}"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
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
                                    value="{{ old('phone', $ministry->phone ?? '') }}"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                @error('phone')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    About Your Ministry (Optional)
                                </label>
                                <textarea
                                    name="description"
                                    rows="4"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">{{ old('description', $ministry->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Logo -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Ministry Logo (Optional)
                                </label>

                                <!-- Logo Preview Area -->
                                <div class="flex items-center gap-4 mb-3" x-show="logoPreview">
                                    <img :src="logoPreview" alt="Logo Preview" class="w-20 h-20 rounded-lg object-contain border-2 border-gray-200 shadow-sm bg-white p-1">
                                    <button type="button" @click="logoPreview = ''; $refs.logoInput.value = ''" class="text-xs text-red-600 hover:underline">Remove</button>
                                </div>

                                <input
                                    type="file"
                                    name="logo"
                                    accept="image/*"
                                    x-ref="logoInput"
                                    @change="handleLogo($event)"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">JPG or PNG, max 2MB.</p>
                                @error('logo')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Focus Areas -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Focus Areas (Optional)
                                </label>
                                <input
                                    type="text"
                                    name="focus_areas"
                                    value="{{ old('focus_areas', isset($ministry) && $ministry->focus_areas ? implode(', ', $ministry->focus_areas) : '') }}"
                                    placeholder="e.g. Evangelism, Youth Ministry, Missions, Discipleship"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">What are the main areas your ministry focuses on? Separate with commas.</p>
                                @error('focus_areas')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Languages -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Languages Served (Optional)
                                </label>
                                <input
                                    type="text"
                                    name="languages"
                                    value="{{ old('languages', isset($ministry) && $ministry->languages ? implode(', ', $ministry->languages) : '') }}"
                                    placeholder="e.g. English, Afrikaans, Zulu, Swahili"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">What languages does your ministry operate in? Separate with commas.</p>
                                @error('languages')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Tags (Optional)
                                </label>
                                <input
                                    type="text"
                                    name="tags"
                                    value="{{ old('tags', isset($ministry) && $ministry->tags ? implode(', ', $ministry->tags) : '') }}"
                                    placeholder="e.g. Non-profit, Church, Outreach, Education"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                <p class="pma-body text-xs mt-1" style="color: var(--color-olive);">Add keywords to help people find your ministry. Separate with commas.</p>
                                @error('tags')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Social Links & Website -->
                            <div class="pma-card p-6" style="background: rgba(124, 58, 237, 0.05);">
                                <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Online Presence (Optional)</h3>
                                <p class="pma-body text-sm mb-4" style="color: var(--color-olive);">Share your website and social media links so others can connect with your ministry.</p>

                                <div class="space-y-4">
                                    <!-- Website -->
                                    <div>
                                        <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                                Website
                                            </span>
                                        </label>
                                        <input
                                            type="url"
                                            name="website"
                                            value="{{ old('website', $ministry->website ?? '') }}"
                                            placeholder="https://example.com"
                                            class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                            style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                            onfocus="this.style.borderColor='#7C3AED';"
                                            onblur="this.style.borderColor='var(--color-cream-dark)';">
                                        @error('website')
                                            <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Facebook -->
                                    <div>
                                        <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                Facebook
                                            </span>
                                        </label>
                                        <input
                                            type="url"
                                            name="facebook"
                                            value="{{ old('facebook', $ministry->facebook ?? '') }}"
                                            placeholder="https://facebook.com/yourpage"
                                            class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                            style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                            onfocus="this.style.borderColor='#7C3AED';"
                                            onblur="this.style.borderColor='var(--color-cream-dark)';">
                                        @error('facebook')
                                            <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Instagram -->
                                    <div>
                                        <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                Instagram
                                            </span>
                                        </label>
                                        <input
                                            type="url"
                                            name="instagram"
                                            value="{{ old('instagram', $ministry->instagram ?? '') }}"
                                            placeholder="https://instagram.com/yourhandle"
                                            class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                            style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                            onfocus="this.style.borderColor='#7C3AED';"
                                            onblur="this.style.borderColor='var(--color-cream-dark)';">
                                        @error('instagram')
                                            <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- X (Twitter) -->
                                    <div>
                                        <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                                X (Twitter)
                                            </span>
                                        </label>
                                        <input
                                            type="url"
                                            name="twitter"
                                            value="{{ old('twitter', $ministry->twitter ?? '') }}"
                                            placeholder="https://x.com/yourhandle"
                                            class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                            style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                            onfocus="this.style.borderColor='#7C3AED';"
                                            onblur="this.style.borderColor='var(--color-cream-dark)';">
                                        @error('twitter')
                                            <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- YouTube -->
                                    <div>
                                        <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                YouTube
                                            </span>
                                        </label>
                                        <input
                                            type="url"
                                            name="youtube"
                                            value="{{ old('youtube', $ministry->youtube ?? '') }}"
                                            placeholder="https://youtube.com/@yourchannel"
                                            class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                            style="border-color: var(--color-cream-dark); background: white; color: var(--color-indigo);"
                                            onfocus="this.style.borderColor='#7C3AED';"
                                            onblur="this.style.borderColor='var(--color-cream-dark)';">
                                        @error('youtube')
                                            <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div>
                        <h2 class="pma-heading text-2xl mb-6" style="color: var(--color-indigo);">
                            Location Information
                        </h2>

                        <div class="pma-card p-6 mb-4" style="background: rgba(124, 58, 237, 0.05);">
                            <p class="pma-body text-sm" style="color: var(--color-olive);">
                                <strong>Privacy Note:</strong> We only need your general area (city/town), not your street address. This helps others find ministries in their region.
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
                                    onfocus="this.style.borderColor='#7C3AED'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                            </div>

                            <!-- Map Container -->
                            <div>
                                <div id="registration-map" class="w-full h-96 rounded-lg border-2" style="border-color: var(--color-cream-dark);"></div>
                            </div>

                            <!-- Hidden Fields for Form Submission -->
                            <input type="hidden" name="latitude" id="latitude-input" value="{{ old('latitude', $ministry->latitude ?? '') }}" required>
                            <input type="hidden" name="longitude" id="longitude-input" value="{{ old('longitude', $ministry->longitude ?? '') }}" required>
                            <input type="hidden" name="city" id="city-input" value="{{ old('city', $ministry->city ?? '') }}">
                            <input type="hidden" name="province" id="province-input" value="{{ old('province', $ministry->province ?? '') }}">
                            <input type="hidden" name="country" id="country-input" value="{{ old('country', $ministry->country ?? '') }}">

                            <!-- Selected Location Display -->
                            <div id="selected-location" class="hidden pma-card p-4" style="background: rgba(124, 58, 237, 0.1);">
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
                                <input type="checkbox" name="show_email" value="1" {{ old('show_email', $ministry->show_email ?? true) ? 'checked' : '' }} class="mr-2">
                                <span class="pma-body text-sm" style="color: var(--color-olive);">Show email publicly</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="show_phone" value="1" {{ old('show_phone', $ministry->show_phone ?? false) ? 'checked' : '' }} class="mr-2">
                                <span class="pma-body text-sm" style="color: var(--color-olive);">Show phone publicly</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('dashboard') }}" class="pma-btn pma-btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-lg font-semibold text-white transition-all" style="background: #7C3AED;" onmouseover="this.style.background='#6D28D9'" onmouseout="this.style.background='#7C3AED'">
                            {{ isset($ministry) ? 'Update Ministry' : 'Submit Ministry' }}
                        </button>
                    </div>
                </form>
            </div>
    </div>

    @push('scripts')
    <script>
        window.initRegistrationMap = function() {
            const mapContainer = document.getElementById('registration-map');
            const addressInput = document.getElementById('location-search-input');

            if (!mapContainer || !addressInput) {
                return;
            }

            if (mapContainer.dataset.initialized === 'true') {
                return;
            }

            const latInput = document.getElementById('latitude-input');
            const lngInput = document.getElementById('longitude-input');

            const existingLat = latInput ? parseFloat(latInput.value) : null;
            const existingLng = lngInput ? parseFloat(lngInput.value) : null;

            const defaultCenter = { lat: -29.0, lng: 24.0 };
            const position = (existingLat && existingLng)
                ? { lat: existingLat, lng: existingLng }
                : defaultCenter;

            const initialZoom = (existingLat && existingLng) ? 12 : 5;

            const map = new google.maps.Map(mapContainer, {
                center: position,
                zoom: initialZoom,
                mapTypeId: 'roadmap',
                mapTypeControl: true,
                streetViewControl: false,
            });

            const marker = new google.maps.Marker({
                position: position,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                title: 'Drag to adjust location'
            });

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

            const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                fields: ['geometry', 'address_components', 'name']
            });

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

                marker.setPosition(location);
                map.setCenter(location);
                map.setZoom(12);

                if(latInput) latInput.value = location.lat();
                if(lngInput) lngInput.value = location.lng();

                const cityInput = document.getElementById('city-input');
                if(cityInput) cityInput.value = city;

                const provinceInput = document.getElementById('province-input');
                if(provinceInput) provinceInput.value = province;

                const countryInput = document.getElementById('country-input');
                if(countryInput) countryInput.value = country;

                const displayCity = document.getElementById('display-city');
                if(displayCity) displayCity.textContent = city || '-';

                const displayProvince = document.getElementById('display-province');
                if(displayProvince) displayProvince.textContent = province || '-';

                const displayCountry = document.getElementById('display-country');
                if(displayCountry) displayCountry.textContent = country || '-';

                const locationDisplay = document.getElementById('selected-location');
                if(locationDisplay) locationDisplay.classList.remove('hidden');
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                const newPosition = marker.getPosition();
                if(latInput) latInput.value = newPosition.lat();
                if(lngInput) lngInput.value = newPosition.lng();
            });

            google.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
                if(latInput) latInput.value = event.latLng.lat();
                if(lngInput) lngInput.value = event.latLng.lng();
            });

            mapContainer.dataset.initialized = 'true';
        };

        function loadGoogleMapsApi() {
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                if (!document.getElementById('google-maps-script')) {
                    const script = document.createElement('script');
                    script.id = 'google-maps-script';
                    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initRegistrationMap';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }
            } else {
                setTimeout(window.initRegistrationMap, 100);
                setTimeout(window.initRegistrationMap, 500);
            }
        }

        loadGoogleMapsApi();
        document.addEventListener('livewire:navigated', loadGoogleMapsApi);
    </script>
    @endpush
</x-layouts.app>
