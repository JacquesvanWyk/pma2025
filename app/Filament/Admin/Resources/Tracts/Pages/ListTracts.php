<?php

namespace App\Filament\Admin\Resources\Tracts\Pages;

use App\Filament\Admin\Resources\Tracts\TractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTracts extends ListRecords
{
    protected static string $resource = TractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
