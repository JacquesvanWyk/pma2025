<?php

namespace App\Filament\Admin\Resources\PictureStudies\Pages;

use App\Filament\Admin\Resources\PictureStudies\PictureStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPictureStudies extends ListRecords
{
    protected static string $resource = PictureStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
