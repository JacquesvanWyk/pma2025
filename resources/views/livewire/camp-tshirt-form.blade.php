<?php

use App\Models\CampTshirtOrder;
use App\Models\MerchandiseItem;
use App\Rules\ValidEmailDomain;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $website = ''; // Honeypot
    public float $donationAmount = 0;
    public bool $submitted = false;
    public ?int $orderId = null;

    // Lines: [{size, quantity}]
    public array $lines = [
        ['size' => '', 'quantity' => 1],
    ];

    public float $unitPrice = 0;
    public float $subtotal = 0;
    public float $total = 0;
    public int $totalQuantity = 0;
    public array $availableSizes = [];
    public string $payFastMerchantId = '';
    public array $payFastFields = [];
    public bool $readyToPayFast = false;

    public function mount(MerchandiseItem $item): void
    {
        $this->unitPrice = (float) $item->price;
        $this->availableSizes = $item->sizes ?? [];
        $this->payFastMerchantId = config('camp.payfast_merchant_id', '13157150');
        $this->recalculate();
    }

    public function addLine(): void
    {
        $this->lines[] = ['size' => '', 'quantity' => 1];
    }

    public function removeLine(int $index): void
    {
        if (count($this->lines) > 1) {
            array_splice($this->lines, $index, 1);
            $this->lines = array_values($this->lines);
            $this->recalculate();
        }
    }

    public function updatedLines(): void { $this->recalculate(); }
    public function updatedDonationAmount(): void { $this->recalculate(); }

    private function recalculate(): void
    {
        $this->totalQuantity = array_sum(array_column($this->lines, 'quantity'));
        $this->subtotal = round($this->totalQuantity * $this->unitPrice, 2);
        $this->total = round($this->subtotal + max(0, (float) $this->donationAmount), 2);
    }

    public function submit(): void
    {
        if (! empty($this->website)) {
            $this->submitted = true;
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', new ValidEmailDomain],
            'phone' => ['nullable', 'string', 'max:20'],
            'donationAmount' => ['numeric', 'min:0', 'max:10000'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.size' => ['required', 'string'],
            'lines.*.quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $totalQty = $this->$totalQuantity;
        $sizeSummary = collect($this->lines)
            ->map(fn ($l) => $l['size'].' ×'.$l['quantity'])
            ->join(', ');

        $order = CampTshirtOrder::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'size' => $sizeSummary,
            'quantity' => $totalQty,
            'unit_price' => $this->unitPrice,
            'donation_amount' => max(0, (float) $this->donationAmount),
            'total' => $this->total,
            'payment_status' => 'pending',
            'lines' => $this->lines,
        ]);

        $this->orderId = $order->id;

        $this->payFastFields = [
            'merchant_id' => $this->payFastMerchantId,
            'merchant_key' => env('PAYFAST_MERCHANT_KEY', ''),
            'return_url' => route('camp-meeting.thank-you', ['type' => 'tshirt', 'ref' => $order->id]),
            'cancel_url' => route('camp-meeting'),
            'notify_url' => route('camp-meeting.payfast.notify'),
            'name_first' => explode(' ', $validated['name'])[0],
            'name_last' => implode(' ', array_slice(explode(' ', $validated['name']), 1)) ?: '-',
            'email_address' => $validated['email'] ?? '',
            'm_payment_id' => (string) $order->id,
            'amount' => number_format($this->total, 2, '.', ''),
            'item_name' => 'Camp T-Shirt Order',
            'item_description' => $sizeSummary,
        ];

        $this->readyToPayFast = true;
    }
}; ?>

<div>
    @if($readyToPayFast)
        <div class="text-center py-8">
            <p class="pma-body text-gray-500 mb-3">Redirecting to secure payment...</p>
            <form id="payfast-tshirt-form" action="https://payment.payfast.io/eng/process" method="POST">
                @foreach($payFastFields as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <noscript>
                    <button type="submit" class="pma-btn pma-btn-primary">Pay Now</button>
                </noscript>
            </form>
        </div>
        <script>document.getElementById('payfast-tshirt-form').submit();</script>
    @elseif($submitted)
        <div class="p-4 rounded-lg" style="background: var(--color-pma-green); color: white;">
            <span class="font-semibold">Order received!</span>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            {{-- Honeypot --}}
            <div class="absolute -left-[9999px]" aria-hidden="true">
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            {{-- Size lines --}}
            <div class="space-y-3">
                <label class="block pma-heading-light text-sm" style="color: var(--color-indigo);">
                    Sizes & Quantities <span class="text-red-500">*</span>
                </label>

                @foreach($lines as $i => $line)
                <div class="flex items-center gap-3">
                    <select wire:model.live="lines.{{ $i }}.size"
                            class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body text-sm @error('lines.'.$i.'.size') border-red-500 @enderror">
                        <option value="">— Size —</option>
                        @foreach($availableSizes as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>

                    <input type="number" wire:model.live="lines.{{ $i }}.quantity"
                           min="1" max="10" placeholder="Qty"
                           class="w-20 px-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body text-sm text-center @error('lines.'.$i.'.quantity') border-red-500 @enderror">

                    <span class="text-sm pma-body text-gray-500 w-24 text-right flex-shrink-0">
                        R{{ number_format(($line['quantity'] ?? 1) * $unitPrice, 0) }}
                    </span>

                    @if(count($lines) > 1)
                    <button type="button" wire:click="removeLine({{ $i }})"
                            class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @else
                    <div class="w-8 flex-shrink-0"></div>
                    @endif
                </div>
                @foreach($errors->get('lines.'.$i.'.size') as $err)
                    <p class="text-sm text-red-600 -mt-2">{{ $err }}</p>
                @endforeach
                @endforeach

                <button type="button" wire:click="addLine"
                        class="inline-flex items-center gap-2 text-sm font-semibold transition-colors"
                        style="color: var(--color-pma-green);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add another size
                </button>
            </div>

            {{-- Personal details --}}
            <div>
                <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Full Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" placeholder="John Doe"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Email</label>
                    <input type="email" wire:model="email" placeholder="john@example.com"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('email') border-red-500 @enderror">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Phone</label>
                    <input type="tel" wire:model="phone" placeholder="0794703941"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body">
                </div>
            </div>

            {{-- Donation --}}
            <div>
                <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                    Suggested donation
                    <span class="text-xs text-gray-400 ml-1">— help support the camp (optional)</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pma-body font-semibold">R</span>
                    <input type="number" wire:model.live="donationAmount" min="0" step="10" placeholder="0"
                           class="w-full pl-8 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body">
                </div>
            </div>

            {{-- Total --}}
            <div class="rounded-xl p-4 text-sm pma-body space-y-2" style="background: var(--color-cream);">
                @foreach($lines as $line)
                @if(!empty($line['size']))
                <div class="flex justify-between text-gray-600">
                    <span>{{ $line['size'] }} × {{ $line['quantity'] }} shirt{{ $line['quantity'] > 1 ? 's' : '' }}</span>
                    <span>R{{ number_format(($line['quantity'] ?? 1) * $unitPrice, 0) }}</span>
                </div>
                @endif
                @endforeach
                @if($donationAmount > 0)
                <div class="flex justify-between text-gray-600">
                    <span>Donation</span>
                    <span>R{{ number_format($donationAmount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold border-t border-gray-200 pt-2" style="color: var(--color-pma-green);">
                    <span>Total ({{ $totalQuantity }} shirt{{ $totalQuantity > 1 ? 's' : '' }})</span>
                    <span>R{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <button type="submit" class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center" wire:loading.attr="disabled">
                <span wire:loading.remove>Pay via PayFast — R{{ number_format($total, 2) }}</span>
                <span wire:loading class="inline-flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </form>
    @endif
</div>
