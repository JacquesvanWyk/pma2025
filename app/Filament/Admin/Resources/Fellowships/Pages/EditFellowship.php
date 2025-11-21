<?php

namespace App\Filament\Admin\Resources\Fellowships\Pages;

use App\Filament\Admin\Resources\Fellowships\FellowshipResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFellowship extends EditRecord
{
    protected static string $resource = FellowshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
