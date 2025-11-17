<?php

namespace App\Filament\Admin\Resources\Studies\Pages;

use App\Filament\Admin\Resources\Studies\StudyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudies extends ListRecords
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
