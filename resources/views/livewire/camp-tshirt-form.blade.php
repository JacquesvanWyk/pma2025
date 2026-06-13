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
    public string $delivery = 'collect'; // collect|pudo
    public float $donationAmount = 0;
    public bool $submitted = false;
    public ?int $orderId = null;

    // Lines: [{item_id, size, quantity}]
    public array $lines = [
        ['item_id' => '', 'size' => '', 'quantity' => 1],
    ];

    public array $items = []; // [{id, name, price, sizes}]
    public float $deliveryFee = 0;
    public float $subtotal = 0;
    public float $total = 0;
    public int $totalQuantity = 0;
    public array $payFastFields = [];
    public bool $readyToPayFast = false;

    const PUDO_FEE = 60.00;

    public function mount(): void
    {
        $this->items = MerchandiseItem::active()
            ->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name,
                'price' => (float) $i->price,
                'sizes' => $i->sizes ?? [],
            ])
            ->toArray();

        $this->recalculate();
    }

    public function addLine(): void
    {
        $this->lines[] = ['item_id' => '', 'size' => '', 'quantity' => 1];
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
    public function updatedDelivery(): void { $this->recalculate(); }

    private function itemById(int|string $id): ?array
    {
        return collect($this->items)->firstWhere('id', (int) $id);
    }

    private function recalculate(): void
    {
        $this->totalQuantity = 0;
        $this->subtotal = 0;

        foreach ($this->lines as $line) {
            if (! empty($line['item_id'])) {
                $item = $this->itemById($line['item_id']);
                if ($item) {
                    $qty = max(1, (int) ($line['quantity'] ?? 1));
                    $this->totalQuantity += $qty;
                    $this->subtotal += round($qty * $item['price'], 2);
                }
            }
        }

        $this->deliveryFee = $this->delivery === 'pudo' ? self::PUDO_FEE : 0;
        $this->total = round($this->subtotal + $this->deliveryFee + max(0, (float) $this->donationAmount), 2);
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
            'delivery' => ['required', 'in:collect,pudo'],
            'donationAmount' => ['numeric', 'min:0', 'max:10000'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.item_id' => ['required', 'exists:merchandise_items,id'],
            'lines.*.size' => ['required', 'string'],
            'lines.*.quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $sizeSummary = collect($this->lines)
            ->map(function ($l) {
                $item = $this->itemById($l['item_id']);
                return ($item ? $item['name'].' ' : '').$l['size'].' ×'.$l['quantity'];
            })
            ->join(', ');

        $firstItem = $this->itemById($this->lines[0]['item_id'] ?? 0);
        $unitPrice = $firstItem ? $firstItem['price'] : 0;

        $order = CampTshirtOrder::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'delivery' => $this->delivery,
            'delivery_fee' => $this->deliveryFee,
            'size' => $sizeSummary,
            'quantity' => $this->totalQuantity,
            'unit_price' => $unitPrice,
            'donation_amount' => max(0, (float) $this->donationAmount),
            'total' => $this->total,
            'payment_status' => 'pending',
            'lines' => $this->lines,
        ]);

        $this->orderId = $order->id;

        $payFastKey = env('PAYFAST_MERCHANT_KEY', '');

        $this->payFastFields = [
            'merchant_id' => config('camp.payfast_merchant_id', '13157150'),
            'merchant_key' => $payFastKey,
            'return_url' => route('camp-meeting.thank-you', ['type' => 'tshirt', 'ref' => $order->id]),
            'cancel_url' => route('camp-meeting'),
            'notify_url' => route('camp-meeting.payfast.notify'),
            'name_first' => explode(' ', $validated['name'])[0],
            'name_last' => implode(' ', array_slice(explode(' ', $validated['name']), 1)) ?: '-',
            'email_address' => $validated['email'] ?? '',
            'm_payment_id' => (string) $order->id,
            'amount' => number_format($this->total, 2, '.', ''),
            'item_name' => 'Camp Merchandise Order',
            'item_description' => $sizeSummary.($this->delivery === 'pudo' ? ' (Pudo delivery)' : ' (Collect at camp)'),
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

            {{-- Item lines --}}
            <div class="space-y-3">
                <label class="block pma-heading-light text-sm" style="color: var(--color-indigo);">
                    Items <span class="text-red-500">*</span>
                </label>

                @foreach($lines as $i => $line)
                @php
                    $selectedItem = !empty($line['item_id']) ? collect($items)->firstWhere('id', (int)$line['item_id']) : null;
                    $availableSizes = $selectedItem ? $selectedItem['sizes'] : [];
                @endphp
                <div class="grid grid-cols-12 gap-2 items-start">
                    {{-- Product --}}
                    <div class="col-span-5">
                        <select wire:model.live="lines.{{ $i }}.item_id"
                                class="w-full px-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body text-sm @error('lines.'.$i.'.item_id') border-red-500 @enderror">
                            <option value="">— Product —</option>
                            @foreach($items as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }} (R{{ number_format($item['price'], 0) }})</option>
                            @endforeach
                        </select>
                        @foreach($errors->get('lines.'.$i.'.item_id') as $err)
                            <p class="text-xs text-red-600 mt-1">{{ $err }}</p>
                        @endforeach
                    </div>

                    {{-- Size --}}
                    <div class="col-span-4">
                        <select wire:model.live="lines.{{ $i }}.size"
                                class="w-full px-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body text-sm @error('lines.'.$i.'.size') border-red-500 @enderror"
                                {{ empty($availableSizes) ? 'disabled' : '' }}>
                            <option value="">— Size —</option>
                            @foreach($availableSizes as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                        @foreach($errors->get('lines.'.$i.'.size') as $err)
                            <p class="text-xs text-red-600 mt-1">{{ $err }}</p>
                        @endforeach
                    </div>

                    {{-- Qty --}}
                    <div class="col-span-2">
                        <input type="number" wire:model.live="lines.{{ $i }}.quantity"
                               min="1" max="10" placeholder="Qty"
                               class="w-full px-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body text-sm text-center @error('lines.'.$i.'.quantity') border-red-500 @enderror">
                    </div>

                    {{-- Remove --}}
                    <div class="col-span-1 flex items-center justify-center pt-1">
                        @if(count($lines) > 1)
                        <button type="button" wire:click="removeLine({{ $i }})"
                                class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach

                <button type="button" wire:click="addLine"
                        class="inline-flex items-center gap-2 text-sm font-semibold transition-colors"
                        style="color: var(--color-pma-green);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add another item
                </button>
            </div>

            {{-- Delivery --}}
            <div>
                <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                    Delivery <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                           style="{{ $delivery === 'collect' ? 'border-color: var(--color-pma-green); background: #f0fdf4;' : 'border-color: #e5e7eb; background: white;' }}">
                        <input type="radio" wire:model.live="delivery" value="collect" class="sr-only">
                        <div class="flex-1">
                            <div class="pma-heading-light text-sm font-semibold" style="color: var(--color-indigo);">Collect at camp</div>
                            <div class="text-xs pma-body text-gray-400 mt-0.5">Pick up in October · Free</div>
                        </div>
                        <div class="text-sm font-bold" style="color: var(--color-pma-green);">Free</div>
                    </label>
                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                           style="{{ $delivery === 'pudo' ? 'border-color: var(--color-pma-green); background: #f0fdf4;' : 'border-color: #e5e7eb; background: white;' }}">
                        <input type="radio" wire:model.live="delivery" value="pudo" class="sr-only">
                        <div class="flex-1">
                            <div class="pma-heading-light text-sm font-semibold" style="color: var(--color-indigo);">Pudo delivery</div>
                            <div class="text-xs pma-body text-gray-400 mt-0.5">Courier to your door</div>
                        </div>
                        <div class="text-sm font-bold" style="color: var(--color-indigo);">+R60</div>
                    </label>
                </div>
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

            {{-- Order summary --}}
            @if($subtotal > 0)
            <div class="rounded-xl p-4 text-sm pma-body space-y-2" style="background: var(--color-cream);">
                @foreach($lines as $line)
                @php $lineItem = !empty($line['item_id']) ? collect($items)->firstWhere('id', (int)$line['item_id']) : null; @endphp
                @if($lineItem && !empty($line['size']))
                <div class="flex justify-between text-gray-600">
                    <span>{{ $lineItem['name'] }} · {{ $line['size'] }} × {{ $line['quantity'] }}</span>
                    <span>R{{ number_format(($line['quantity'] ?? 1) * $lineItem['price'], 0) }}</span>
                </div>
                @endif
                @endforeach
                @if($deliveryFee > 0)
                <div class="flex justify-between text-gray-600">
                    <span>Pudo delivery</span>
                    <span>R{{ number_format($deliveryFee, 0) }}</span>
                </div>
                @endif
                @if($donationAmount > 0)
                <div class="flex justify-between text-gray-600">
                    <span>Donation</span>
                    <span>R{{ number_format($donationAmount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold border-t border-gray-200 pt-2" style="color: var(--color-pma-green);">
                    <span>Total</span>
                    <span>R{{ number_format($total, 2) }}</span>
                </div>
            </div>
            @endif

            <button type="submit" class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center" wire:loading.attr="disabled">
                <span wire:loading.remove>Pay via PayFast{{ $total > 0 ? ' — R'.number_format($total, 2) : '' }}</span>
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
