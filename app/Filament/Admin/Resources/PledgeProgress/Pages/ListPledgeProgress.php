<?php

namespace App\Filament\Admin\Resources\PledgeProgress\Pages;

use App\Filament\Admin\Resources\PledgeProgress\PledgeProgressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPledgeProgress extends ListRecords
{
    protected static string $resource = PledgeProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
