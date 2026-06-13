<?php

namespace App\Filament\Admin\Resources\CampTshirtOrders\Pages;

use App\Filament\Admin\Resources\CampTshirtOrders\CampTshirtOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCampTshirtOrder extends EditRecord
{
    protected static string $resource = CampTshirtOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make(), RestoreAction::make()];
    }
}
