<?php

namespace App\Filament\Admin\Resources\PrayerRequests\Pages;

use App\Filament\Admin\Resources\PrayerRequests\PrayerRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrayerRequest extends CreateRecord
{
    protected static string $resource = PrayerRequestResource::class;
}
