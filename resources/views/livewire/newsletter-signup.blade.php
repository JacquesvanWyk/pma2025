<div>
    @if($success)
        <div class="text-center py-4">
            <svg class="w-12 h-12 mx-auto mb-3 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="{{ $variant === 'footer' ? 'text-white' : 'text-white' }} font-semibold">Thank you for subscribing!</p>
            <p class="{{ $variant === 'footer' ? 'text-gray-400' : 'text-white/70' }} text-sm mt-1">You'll receive our updates soon.</p>
        </div>
    @else
        @if($variant === 'footer')
            {{-- Footer variant - compact --}}
            <div class="space-y-3">
                <div class="relative">
                    <input type="email"
                           wire:model="email"
                           placeholder="Enter your email address"
                           class="w-full bg-white/5 border border-white/10 rounded-l px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-[var(--color-pma-green)] focus:ring-1 focus:ring-[var(--color-pma-green)] transition-all pr-28">
                    <button wire:click="subscribe"
                            wire:loading.attr="disabled"
                            class="absolute right-1.5 top-1.5 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50">
                        <span wire:loading.remove>Subscribe</span>
                        <span wire:loading>...</span>
                    </button>
                </div>
                @if($showLanguage)
                <div class="flex gap-4 text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="language" value="en" class="w-4 h-4" style="accent-color: var(--color-pma-green);">
                        <span class="text-gray-400">English</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="language" value="af" class="w-4 h-4" style="accent-color: var(--color-pma-green);">
                        <span class="text-gray-400">Afrikaans</span>
                    </label>
                </div>
                @endif
                @error('email') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
            </div>
        @else
            {{-- Default variant - full section --}}
            <form wire:submit="subscribe" class="max-w-xl mx-auto w-full">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <input type="email"
                           wire:model="email"
                           placeholder="Enter your email address"
                           required
                           class="flex-1 px-6 py-4 rounded-lg text-lg pma-body"
                           style="background: white; color: var(--color-indigo); border: 2px solid transparent;">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="pma-btn pma-btn-primary px-8 py-4 text-lg whitespace-nowrap disabled:opacity-50">
                        <span wire:loading.remove>Subscribe</span>
                        <span wire:loading>Subscribing...</span>
                    </button>
                </div>
                @if($showLanguage)
                <div class="flex justify-center gap-8 text-sm">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" wire:model="language" value="en"
                               class="w-5 h-5 accent-current"
                               style="accent-color: var(--color-ochre);">
                        <span class="pma-body text-white/90 group-hover:text-white transition-colors">English</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" wire:model="language" value="af"
                               class="w-5 h-5"
                               style="accent-color: var(--color-ochre);">
                        <span class="pma-body text-white/90 group-hover:text-white transition-colors">Afrikaans</span>
                    </label>
                </div>
                @endif
                @error('email') <p class="text-red-400 text-sm mt-2 text-center">{{ $message }}</p> @enderror
            </form>
        @endif
    @endif
</div>
