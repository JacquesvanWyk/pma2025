<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $message_type = 'general';
    public string $message = '';
    public bool $sent = false;
    public string $website = ''; // Honeypot field

    public function send(): void
    {
        // Honeypot check - if filled, it's a bot
        if (! empty($this->website)) {
            $this->sent = true; // Fake success to confuse bots
            return;
        }

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message_type' => 'required|in:general,prayer,partnership,support,resources',
            'message' => 'required|string|max:5000',
        ]);

        Mail::to('jvw679@gmail.com')->send(new ContactFormMail($validated));

        $this->reset(['name', 'email', 'phone', 'message_type', 'message', 'website']);
        $this->sent = true;
    }
}; ?>

<div>
    @if($sent)
    <div class="p-4 mb-6 rounded-lg" style="background: var(--color-pma-green); color: white;">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-semibold">Thank you for your message! We will get back to you soon.</span>
        </div>
    </div>
    @endif

    <form wire:submit="send" class="space-y-6">
        <!-- Honeypot field - hidden from users, visible to bots -->
        <div class="absolute -left-[9999px]" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" wire:model="website" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div>
            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                Full Name
            </label>
            <input type="text" wire:model="name" placeholder="John Doe"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body @error('name') border-red-500 @enderror"
                   style="focus:ring-color: var(--color-pma-green);">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                Email Address
            </label>
            <input type="email" wire:model="email" placeholder="john@example.com"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body @error('email') border-red-500 @enderror"
                   style="focus:ring-color: var(--color-pma-green);">
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                Phone Number
            </label>
            <input type="tel" wire:model="phone" placeholder="0794703941"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                   style="focus:ring-color: var(--color-pma-green);">
        </div>

        <div>
            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                Message Type
            </label>
            <select wire:model="message_type"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body"
                    style="focus:ring-color: var(--color-pma-green);">
                <option value="general">General Inquiry</option>
                <option value="prayer">Prayer Request</option>
                <option value="partnership">Partnership Opportunity</option>
                <option value="support">Technical Support</option>
                <option value="resources">Resource Request</option>
            </select>
        </div>

        <div>
            <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                Your Message
            </label>
            <textarea wire:model="message" rows="6" placeholder="Tell us how we can help you..."
                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:border-transparent pma-body resize-none @error('message') border-red-500 @enderror"
                      style="focus:ring-color: var(--color-pma-green);"></textarea>
            @error('message')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center" wire:loading.attr="disabled">
            <span wire:loading.remove>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Message
            </span>
            <span wire:loading class="inline-flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
            </span>
        </button>
    </form>
</div>
