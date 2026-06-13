<?php

namespace App\Filament\Admin\Resources\AccommodationTypes\Pages;

use App\Filament\Admin\Resources\AccommodationTypes\AccommodationTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccommodationTypes extends ListRecords
{
    protected static string $resource = AccommodationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
