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
        <button type="submit" class="pma-btn pma-btn-primary w-full flex items-center justify-center gap-2" wire:loading.attr="disabled">
            <svg wire:loading class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove>{{ __('Create account') }}</span>
            <span wire:loading>{{ __('Creating account...') }}</span>
        </button>
    </form>

    <div class="text-center pma-body text-sm" style="color: var(--color-olive);">
        <span>{{ __('Already have an account?') }}</span>
        <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: var(--color-pma-green);" wire:navigate>
            {{ __('Log in') }}
        </a>
    </div>
</div>
