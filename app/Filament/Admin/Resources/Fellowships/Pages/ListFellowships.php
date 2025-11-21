<?php

namespace App\Filament\Admin\Resources\Fellowships\Pages;

use App\Filament\Admin\Resources\Fellowships\FellowshipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFellowships extends ListRecords
{
    protected static string $resource = FellowshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
