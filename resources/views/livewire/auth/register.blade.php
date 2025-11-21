<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Join Our Community')" :description="__('Create your Pioneer Missions Africa account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-5">
        <!-- Name -->
        <div>
            <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                {{ __('Name') }}
            </label>
            <input
                wire:model="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="{{ __('Full name') }}"
                class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';"
            >
            @error('name')
                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                {{ __('Email address') }}
            </label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
                class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';"
            >
            @error('email')
                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                {{ __('Password') }}
            </label>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Password') }}"
                class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';"
            >
            @error('password')
                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                {{ __('Confirm password') }}
            </label>
            <input
                wire:model="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Confirm password') }}"
                class="w-full px-4 py-3 rounded-lg border-2 transition-all pma-body"
                style="border-color: var(--color-cream-dark); background: var(--color-cream); color: var(--color-indigo);"
                onfocus="this.style.borderColor='var(--color-pma-green)'; this.style.background='white';"
                onblur="this.style.borderColor='var(--color-cream-dark)'; this.style.background='var(--color-cream)';"
            >
            @error('password_confirmation')
                <p class="pma-body text-sm mt-1" style="color: var(--color-terracotta);">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="pma-btn pma-btn-primary w-full">
            {{ __('Create account') }}
        </button>
    </form>

    <div class="text-center pma-body text-sm" style="color: var(--color-olive);">
        <span>{{ __('Already have an account?') }}</span>
        <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: var(--color-pma-green);" wire:navigate>
            {{ __('Log in') }}
        </a>
    </div>
</div>
