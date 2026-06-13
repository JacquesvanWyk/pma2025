<x-mail::message>
# Camp Meeting 2026 — Booking Confirmed

Hi {{ $booking->name }},

Your accommodation interest for **Camp Meeting 2026** has been registered. Please pay your deposit via EFT to secure your spot.

---

## Your Booking

| | |
|---|---|
| **Accommodation** | {{ $booking->accommodationType?->name ?? '—' }} |
| **Adults** | {{ $booking->adults }} |
| **Children** | {{ $booking->children }} |
| **Nights** | {{ $booking->nights }} |
| **Estimated Total** | R {{ number_format($booking->estimated_total, 2) }} |
| **50% Deposit Due** | R {{ number_format($booking->deposit_amount, 2) }} |

---

## EFT Payment Details

| | |
|---|---|
| **Account Name** | {{ config('camp.eft.account_name') }} |
| **Bank** | {{ config('camp.eft.bank') }} |
@if(config('camp.eft.account_number'))
| **Account Number** | {{ config('camp.eft.account_number') }} |
@endif
@if(config('camp.eft.branch_code'))
| **Branch Code** | {{ config('camp.eft.branch_code') }} |
@endif
| **Reference** | **{{ $eftReference }}** |
| **Amount** | **R {{ number_format($booking->deposit_amount, 2) }}** |

**Use exactly the reference above.** Once paid, please email or WhatsApp us your proof of payment.

Deposit deadline: **{{ \Carbon\Carbon::parse(config('camp.deposit_deadline'))->format('j F Y') }}**

---

*Wilderness Ebb & Flow Rest Camp · 9–11 October 2026*

Thanks,<br>
Pioneer Missions Africa
</x-mail::message>
