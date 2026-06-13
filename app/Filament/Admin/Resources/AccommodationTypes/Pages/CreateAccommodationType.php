<?php

namespace App\Filament\Admin\Resources\AccommodationTypes\Pages;

use App\Filament\Admin\Resources\AccommodationTypes\AccommodationTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAccommodationType extends CreateRecord
{
    protected static string $resource = AccommodationTypeResource::class;
}
