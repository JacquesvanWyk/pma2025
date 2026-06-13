<?php

namespace App\Filament\Admin\Resources\AccommodationTypes\Pages;

use App\Filament\Admin\Resources\AccommodationTypes\AccommodationTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAccommodationType extends EditRecord
{
    protected static string $resource = AccommodationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make(), RestoreAction::make()];
    }
}
