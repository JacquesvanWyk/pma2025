<?php

namespace App\Observers;

use App\Mail\CampDepositReceiptMail;
use App\Models\CampBooking;
use Illuminate\Support\Facades\Mail;

class CampBookingObserver
{
    public function updated(CampBooking $booking): void
    {
        if ($booking->wasChanged('deposit_paid') && $booking->deposit_paid && $booking->email) {
            Mail::to($booking->email)->send(new CampDepositReceiptMail($booking));
        }
    }
}
