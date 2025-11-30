<?php

namespace App\Filament\Admin\Resources\PictureStudies\Pages;

use App\Filament\Admin\Resources\PictureStudies\PictureStudyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePictureStudy extends CreateRecord
{
    protected static string $resource = PictureStudyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
