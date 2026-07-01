<?php

use App\Mail\CampBookingAdminNotificationMail;
use App\Mail\CampBookingConfirmationMail;
use App\Models\AccommodationType;
use App\Models\CampBooking;
use App\Rules\ValidEmailDomain;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $website = ''; // Honeypot

    public ?int $accommodation_type_id = null;

    public ?int $adults = 2;

    public ?int $children = 0;

    public ?int $nights = 1;

    public string $notes = '';

    public bool $submitted = false;

    public ?int $bookingId = null;

    public string $eftReference = '';

    public float $estimatedTotal = 0;

    public float $depositAmount = 0;

    public array $accommodationTypes = [];

    public ?array $selectedType = null;

    public function mount(): void
    {
        $this->refreshAccommodationTypes();
    }

    public function refreshAccommodationTypes(): void
    {
        $this->accommodationTypes = AccommodationType::active()
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'base_price' => (float) $t->base_price,
                'base_adults' => $t->base_adults,
                'extra_adult_price' => $t->extra_adult_price ? (float) $t->extra_adult_price : null,
                'extra_child_price' => $t->extra_child_price ? (float) $t->extra_child_price : null,
                'max_adults' => $t->max_adults,
                'max_children' => $t->max_children,
                'total_units' => $t->total_units,
                'available_units' => $t->availableUnits(),
                'is_full' => $t->isFull(),
                'is_day_visitor' => $t->is_day_visitor,
                'image' => $t->image,
                'description' => $t->description,
            ])
            ->toArray();
    }

    public function updatedAccommodationTypeId(): void
    {
        $this->selectedType = collect($this->accommodationTypes)
            ->firstWhere('id', (int) $this->accommodation_type_id);

        if ($this->selectedType) {
            $this->adults = $this->selectedType['base_adults'];
            $this->children = 0;
            $this->nights = $this->selectedType['is_day_visitor'] ? 1 : 1;
        }

        $this->recalculate();
    }

    public function updatedAdults(): void
    {
        $this->recalculate();
    }

    public function updatedChildren(): void
    {
        $this->recalculate();
    }

    public function updatedNights(): void
    {
        $this->recalculate();
    }

    private function recalculate(): void
    {
        if (! $this->selectedType) {
            $this->estimatedTotal = 0;
            $this->depositAmount = 0;

            return;
        }

        $base = $this->selectedType['base_price'];
        $extraAdultRate = $this->selectedType['extra_adult_price'] ?? 0;
        $extraChildRate = $this->selectedType['extra_child_price'] ?? 0;
        $baseAdults = $this->selectedType['base_adults'];

        $adults = $this->adults ?? 1;
        $children = $this->children ?? 0;
        $nights = $this->nights ?? 1;

        $extraAdults = max(0, $adults - $baseAdults);
        $nightRate = $base + ($extraAdults * $extraAdultRate) + ($children * $extraChildRate);
        $this->estimatedTotal = round($nightRate * $nights, 2);
        $this->depositAmount = round($this->estimatedTotal * config('camp.deposit_percentage', 0.5), 2);
    }

    public function submit(): void
    {
        if (! empty($this->website)) {
            $this->submitted = true;

            return;
        }

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-]+$/u'],
            'email' => ['nullable', 'email', 'max:255', new ValidEmailDomain],
            'phone' => ['nullable', 'string', 'max:20'],
            'accommodation_type_id' => ['required', 'exists:accommodation_types,id'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'nights' => ['required', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        // Occupancy validation against selected type
        if ($this->selectedType) {
            $rules['adults'][] = 'max:'.$this->selectedType['max_adults'];
            $rules['children'][] = 'max:'.$this->selectedType['max_children'];

            if ($this->selectedType['extra_adult_price'] === null && $this->selectedType['extra_child_price'] === null) {
                $rules['adults'] = ['required', 'integer', 'min:1', 'max:'.$this->selectedType['base_adults']];
                $rules['children'] = ['required', 'integer', 'min:0', 'max:0'];
            }
        }

        $validated = $this->validate($rules);

        $type = AccommodationType::findOrFail($validated['accommodation_type_id']);

        if ($type->isFull()) {
            $this->addError('accommodation_type_id', 'Sorry, this accommodation type is fully booked.');

            return;
        }

        $booking = new CampBooking($validated);
        $booking->recalculateTotals();
        $booking->save();

        $this->bookingId = $booking->id;
        $this->eftReference = $booking->eftReference();
        $this->submitted = true;
        $this->refreshAccommodationTypes();

        if ($booking->email) {
            Mail::to($booking->email)->send(new CampBookingConfirmationMail($booking, $this->eftReference));
        }

        Mail::to(['jvw679@gmail.com', 'virgilcarolus@gmail.com', 'stefanievantaak@hotmail.com'])->send(new CampBookingAdminNotificationMail($booking, $this->eftReference));
    }
}; ?>

<div class="space-y-10">

    {{-- Accommodation cards (reactive — updates after booking) --}}
    <div>
        <div class="text-center mb-8">
            <h2 class="pma-heading text-3xl md:text-4xl mb-3" style="color: var(--color-indigo);">Accommodation Options</h2>
            <p class="pma-body text-gray-500 max-w-xl mx-auto text-sm">Choose your accommodation below, then register your spot.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($accommodationTypes as $type)
            <div class="rounded-2xl overflow-hidden flex flex-col" style="background: var(--color-cream); border: 1px solid #e5e7eb;">
                @if(!empty($type['image']))
                <img src="{{ asset('images/'.$type['image']) }}" alt="{{ $type['name'] }}"
                     class="w-full h-36 object-cover">
                @endif
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h3 class="pma-heading text-sm leading-snug" style="color: var(--color-indigo);">{{ $type['name'] }}</h3>
                        @if($type['total_units'] !== null)
                            @if($type['is_full'])
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0 whitespace-nowrap" style="background: #fee2e2; color: #dc2626;">FULL</span>
                            @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0 whitespace-nowrap" style="background: #dcfce7; color: #15803d;">{{ $type['available_units'] }} left</span>
                            @endif
                        @endif
                    </div>
                    <p class="text-xs pma-body text-gray-400 mb-3 flex-1">{{ $type['description'] ?? '' }}</p>
                    <div class="text-sm pma-body space-y-1 border-t border-gray-200 pt-3">
                        @if($type['is_day_visitor'])
                        <div class="flex justify-between">
                            <span class="text-gray-500">per person / day</span>
                            <span class="font-bold" style="color: var(--color-pma-green);">R{{ number_format($type['base_price'], 0) }}</span>
                        </div>
                        @else
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ $type['base_adults'] }} adults / night</span>
                            <span class="font-bold" style="color: var(--color-pma-green);">R{{ number_format($type['base_price'], 0) }}</span>
                        </div>
                        @if($type['extra_adult_price'])
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">+ extra adult</span>
                            <span class="text-gray-600">R{{ number_format($type['extra_adult_price'], 0) }}/night</span>
                        </div>
                        @endif
                        @if($type['extra_child_price'])
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">+ child (2–11)</span>
                            <span class="text-gray-600">R{{ number_format($type['extra_child_price'], 0) }}/night</span>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Booking form / confirmation --}}
    <div class="rounded-2xl p-6 md:p-10" style="background: var(--color-cream); border: 1px solid #e5e7eb;">
        @if(!$submitted)
        <h3 class="pma-heading text-2xl mb-1" style="color: var(--color-indigo);">Register Your Spot</h3>
        <p class="pma-body text-gray-500 mb-8 text-sm">Fill in your details below. We'll give you your personal EFT reference for the 50% deposit.</p>
        @endif
    @if($submitted && $bookingId)
        {{-- Success: show EFT details --}}
        <div class="rounded-2xl p-6 md:p-8 space-y-6" style="background: var(--color-cream); border: 2px solid var(--color-pma-green);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--color-pma-green);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="pma-heading text-xl" style="color: var(--color-pma-green);">Booking registered!</h3>
                    <p class="pma-body text-sm text-gray-600">Please pay your deposit via EFT to secure your spot.</p>
                </div>
            </div>

            <div class="rounded-xl p-5 space-y-3" style="background: white; border: 1px solid var(--color-pma-green-light);">
                <h4 class="pma-heading text-base" style="color: var(--color-indigo);">EFT Payment Details</h4>
                <dl class="text-sm pma-body divide-y divide-gray-100">
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Account Name</dt>
                        <dd class="font-semibold text-right">{{ config('camp.eft.account_name') }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Bank</dt>
                        <dd class="font-semibold text-right">{{ config('camp.eft.bank') }}</dd>
                    </div>
                    @if(config('camp.eft.account_number'))
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Account No.</dt>
                        <dd class="font-semibold text-right tabular-nums">{{ config('camp.eft.account_number') }}</dd>
                    </div>
                    @endif
                    @if(config('camp.eft.branch_code'))
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Branch Code</dt>
                        <dd class="font-semibold text-right tabular-nums">{{ config('camp.eft.branch_code') }}</dd>
                    </div>
                    @endif
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Reference</dt>
                        <dd class="font-bold text-base text-right break-all" style="color: var(--color-pma-green);">{{ $eftReference }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="text-gray-500 shrink-0">Deposit Amount</dt>
                        <dd class="font-bold text-base text-right whitespace-nowrap" style="color: var(--color-pma-green);">R {{ number_format($depositAmount, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <p class="text-sm pma-body text-gray-600">
                Deadline: <strong>{{ \Carbon\Carbon::parse(config('camp.deposit_deadline'))->format('j F Y') }}</strong>.
                Use exactly the reference above. Once paid, email or WhatsApp us proof of payment.
            </p>
        </div>
    @elseif($submitted)
        <div class="p-4 rounded-lg" style="background: var(--color-pma-green); color: white;">
            <span class="font-semibold">Registration received! We will be in touch.</span>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            {{-- Honeypot --}}
            <div class="absolute -left-[9999px]" aria-hidden="true">
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            {{-- Accommodation type --}}
            <div>
                <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                    Accommodation Type <span class="text-red-500">*</span>
                </label>
                <select
                        x-on:change="$wire.set('accommodation_type_id', $event.target.value ? Number($event.target.value) : null)"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('accommodation_type_id') border-red-500 @enderror">
                    <option value="">— Select accommodation —</option>
                    @foreach($accommodationTypes as $type)
                        <option value="{{ $type['id'] }}"
                                @selected($accommodation_type_id === $type['id'])
                                {{ $type['is_full'] ? 'disabled' : '' }}>
                            {{ $type['name'] }} — R{{ number_format($type['base_price'], 0) }}/{{ $type['is_day_visitor'] ? 'person/day' : 'night' }}
                            @if($type['total_units'] !== null)
                                ({{ $type['is_full'] ? 'FULL' : $type['available_units'].' available' }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('accommodation_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Guests — always visible --}}
            @php $isDayVisitor = $selectedType && $selectedType['is_day_visitor']; @endphp
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                        {{ $isDayVisitor ? 'Number of Visitors' : 'Adults' }}
                        @if($selectedType && ! $isDayVisitor) <span class="text-xs text-gray-400">(max {{ $selectedType['max_adults'] }})</span> @endif
                    </label>
                    <input type="number" wire:model.live="adults" min="1"
                           max="{{ $selectedType ? $selectedType['max_adults'] : 20 }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('adults') border-red-500 @enderror">
                    @error('adults')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                @if($isDayVisitor)
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Days</label>
                    <input type="number" wire:model.live="nights" min="1" max="3"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('nights') border-red-500 @enderror">
                    @error('nights')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                @else
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">
                        Children
                        <span class="text-xs text-gray-400">(age 2–11)</span>
                    </label>
                    <input type="number" wire:model.live="children" min="0"
                           max="{{ $selectedType ? $selectedType['max_children'] : 20 }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('children') border-red-500 @enderror"
                           {{ ($selectedType && $selectedType['extra_child_price'] === null) ? 'disabled' : '' }}>
                    @error('children')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Nights</label>
                    <input type="number" wire:model.live="nights" min="1" max="30"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body @error('nights') border-red-500 @enderror">
                    @error('nights')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                @endif
            </div>

            {{-- Live price — only once type selected --}}
            @if($selectedType && $estimatedTotal > 0)
            <div class="rounded-xl p-4 grid grid-cols-2 gap-2 text-sm pma-body" style="background: var(--color-cream);">
                @if($isDayVisitor)
                <span class="text-gray-600">{{ $adults }} visitor{{ $adults > 1 ? 's' : '' }} × {{ $nights }} day{{ $nights > 1 ? 's' : '' }} × R{{ number_format($selectedType['base_price'], 0) }}/person/day</span>
                <span class="font-semibold text-right">R {{ number_format($estimatedTotal, 2) }}</span>
                @else
                <span class="text-gray-600">Estimated total ({{ $nights }} night{{ $nights > 1 ? 's' : '' }}, {{ $adults }} adult{{ $adults > 1 ? 's' : '' }}{{ $children > 0 ? ', '.$children.' child'.($children > 1 ? 'ren' : '') : '' }})</span>
                <span class="font-semibold text-right">R {{ number_format($estimatedTotal, 2) }}</span>
                <span class="text-gray-600">50% deposit due by {{ \Carbon\Carbon::parse(config('camp.deposit_deadline'))->format('j F Y') }}</span>
                <span class="font-bold text-right" style="color: var(--color-pma-green);">R {{ number_format($depositAmount, 2) }}</span>
                @endif
            </div>
            @endif

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

            <div>
                <label class="block pma-heading-light text-sm mb-2" style="color: var(--color-indigo);">Notes / Requests</label>
                <textarea wire:model="notes" rows="3" placeholder="Any special requests or notes..."
                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 pma-body resize-none"></textarea>
            </div>

            <button type="submit"
                    class="pma-btn pma-btn-primary w-full inline-flex items-center justify-center"
                    wire:loading.attr="disabled"
                    {{ ($selectedType && $selectedType['is_full']) ? 'disabled' : '' }}>
                <span wire:loading.remove>Register My Spot</span>
                <span wire:loading class="inline-flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting...
                </span>
            </button>
        </form>
    @endif
    </div>
</div>
