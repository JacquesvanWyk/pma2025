<x-mail::message>
# New Camp Booking

**{{ $booking->name }}** has registered for Camp Meeting 2026.

| | |
|---|---|
| **Name** | {{ $booking->name }} |
| **Email** | {{ $booking->email ?? '—' }} |
| **Phone** | {{ $booking->phone ?? '—' }} |
| **Accommodation** | {{ $booking->accommodationType?->name ?? '—' }} |
| **Adults** | {{ $booking->adults }} |
| **Children** | {{ $booking->children }} |
| **Nights** | {{ $booking->nights }} |
| **Est. Total** | R {{ number_format($booking->estimated_total, 2) }} |
| **Deposit** | R {{ number_format($booking->deposit_amount, 2) }} |
| **EFT Reference** | {{ $eftReference }} |

@if($booking->notes)
**Notes:** {{ $booking->notes }}
@endif

<x-mail::button :url="config('app.url') . '/admin/camp-bookings/' . $booking->id . '/edit'">
View in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
