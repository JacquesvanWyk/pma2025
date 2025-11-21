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

                        <div class="space-y-5">
                            <!-- Address -->
                            <div>
                                <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                    Address
                                </label>
                                <textarea
                                    name="address"
                                    rows="2"
                                    placeholder="Street address, city, province"
                                    class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                    style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                    onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                    onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">{{ old('address', $networkMember->address ?? '') }}</textarea>
                                @error('address')
                                    <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Coordinates Helper -->
                            <div class="pma-card p-4" style="background: rgba(10, 117, 58, 0.1);">
                                <p class="pma-body text-sm mb-2" style="color: var(--color-indigo);">
                                    <strong>Need help finding your coordinates?</strong>
                                </p>
                                <ol class="pma-body text-sm space-y-1 ml-4 list-decimal" style="color: var(--color-olive);">
                                    <li>Go to <a href="https://www.google.com/maps" target="_blank" class="underline" style="color: var(--color-pma-green);">Google Maps</a></li>
                                    <li>Right-click on your location</li>
                                    <li>Click on the coordinates to copy them</li>
                                    <li>Paste them in the fields below</li>
                                </ol>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Latitude -->
                                <div>
                                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                        Latitude *
                                    </label>
                                    <input
                                        type="number"
                                        step="any"
                                        name="latitude"
                                        value="{{ old('latitude', $networkMember->latitude ?? '') }}"
                                        required
                                        placeholder="-26.2041"
                                        class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                        style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                        onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                        onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                    @error('latitude')
                                        <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                                        Longitude *
                                    </label>
                                    <input
                                        type="number"
                                        step="any"
                                        name="longitude"
                                        value="{{ old('longitude', $networkMember->longitude ?? '') }}"
                                        required
                                        placeholder="28.0473"
                                        class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                                        style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                                        onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                                        onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';">
                                    @error('longitude')
                                        <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
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
</x-layouts.app>
