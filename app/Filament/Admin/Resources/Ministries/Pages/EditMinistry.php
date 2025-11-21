<?php

namespace App\Filament\Admin\Resources\Ministries\Pages;

use App\Filament\Admin\Resources\Ministries\MinistryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMinistry extends EditRecord
{
    protected static string $resource = MinistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
