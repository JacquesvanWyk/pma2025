<?php

namespace App\Filament\Admin\Resources\CampBookings\Pages;

use App\Filament\Admin\Resources\CampBookings\CampBookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCampBooking extends CreateRecord
{
    protected static string $resource = CampBookingResource::class;
}
