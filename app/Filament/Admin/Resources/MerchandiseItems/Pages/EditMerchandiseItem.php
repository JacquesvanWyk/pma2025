<?php

namespace App\Filament\Admin\Resources\MerchandiseItems\Pages;

use App\Filament\Admin\Resources\MerchandiseItems\MerchandiseItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMerchandiseItem extends EditRecord
{
    protected static string $resource = MerchandiseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make(), RestoreAction::make()];
    }
}
