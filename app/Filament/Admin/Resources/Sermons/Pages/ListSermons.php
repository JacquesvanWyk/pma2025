<?php

namespace App\Filament\Admin\Resources\Sermons\Pages;

use App\Filament\Admin\Resources\Sermons\SermonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSermons extends ListRecords
{
    protected static string $resource = SermonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
