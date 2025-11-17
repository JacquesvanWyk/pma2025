<?php

namespace App\Filament\Admin\Resources\PledgeProgress\Pages;

use App\Filament\Admin\Resources\PledgeProgress\PledgeProgressResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPledgeProgress extends EditRecord
{
    protected static string $resource = PledgeProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
