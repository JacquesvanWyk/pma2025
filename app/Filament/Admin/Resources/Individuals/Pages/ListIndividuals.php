<?php

namespace App\Filament\Admin\Resources\Individuals\Pages;

use App\Filament\Admin\Resources\Individuals\IndividualResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIndividuals extends ListRecords
{
    protected static string $resource = IndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
