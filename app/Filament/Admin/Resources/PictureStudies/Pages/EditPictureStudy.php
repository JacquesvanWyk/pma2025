<?php

namespace App\Filament\Admin\Resources\PictureStudies\Pages;

use App\Filament\Admin\Resources\PictureStudies\PictureStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPictureStudy extends EditRecord
{
    protected static string $resource = PictureStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
