<?php

namespace App\Filament\Admin\Resources\CampBookings\Pages;

use App\Filament\Admin\Resources\CampBookings\CampBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampBookings extends ListRecords
{
    protected static string $resource = CampBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
