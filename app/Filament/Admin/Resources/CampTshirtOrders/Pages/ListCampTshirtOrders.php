<?php

namespace App\Filament\Admin\Resources\CampTshirtOrders\Pages;

use App\Filament\Admin\Resources\CampTshirtOrders\CampTshirtOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampTshirtOrders extends ListRecords
{
    protected static string $resource = CampTshirtOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
