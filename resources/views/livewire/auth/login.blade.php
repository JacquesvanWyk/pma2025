<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Welcome Back')" :description="__('Sign in to your Pioneer Missions Africa account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5">
        <!-- Email Address -->
        <div>
            <label class="block pma-body text-sm font-medium mb-2" style="color: var(--color-indigo);">
                {{ __('Email address') }}
            </label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
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
            <div class="flex justify-between items-center mb-2">
                <label class="block pma-body text-sm font-medium" style="color: var(--color-indigo);">
                    {{ __('Password') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="pma-body text-sm hover:underline" style="color: var(--color-pma-green);" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="current-password"
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

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                wire:model="remember"
                type="checkbox"
                id="remember"
                class="w-4 h-4 rounded transition-all"
                style="border-color: var(--color-pma-green); color: var(--color-pma-green);"
            >
            <label for="remember" class="ml-2 pma-body text-sm" style="color: var(--color-olive);">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="pma-btn pma-btn-primary w-full">
            {{ __('Log in') }}
        </button>
    </form>

    @if (Route::has('register'))
        <div class="text-center pma-body text-sm" style="color: var(--color-olive);">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: var(--color-pma-green);" wire:navigate>
                {{ __('Sign up') }}
            </a>
        </div>
    @endif
</div>
