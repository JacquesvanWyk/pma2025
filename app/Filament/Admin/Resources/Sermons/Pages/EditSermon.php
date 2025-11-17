<?php

namespace App\Filament\Admin\Resources\Sermons\Pages;

use App\Filament\Admin\Resources\Sermons\SermonResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSermon extends EditRecord
{
    protected static string $resource = SermonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
