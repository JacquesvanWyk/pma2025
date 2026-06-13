<x-mail::message>
# Deposit Received — Thank You!

Hi {{ $booking->name }},

We have received your deposit payment for **Camp Meeting 2026**. Your spot is confirmed!

---

## Booking Summary

| | |
|---|---|
| **Accommodation** | {{ $booking->accommodationType?->name ?? '—' }} |
| **Adults** | {{ $booking->adults }} |
@if($booking->children > 0)
| **Children** | {{ $booking->children }} |
@endif
@if(!$booking->accommodationType?->is_day_visitor)
| **Nights** | {{ $booking->nights }} |
@endif
| **Estimated Total** | R {{ number_format($booking->estimated_total, 2) }} |
| **Deposit Paid** | R {{ number_format($booking->deposit_amount, 2) }} |
| **Reference** | {{ $booking->eftReference() }} |

---

We look forward to seeing you at Wilderness Ebb & Flow, **9–11 October 2026**.

Thanks,<br>
Pioneer Missions Africa
</x-mail::message>
