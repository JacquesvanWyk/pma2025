<?php

namespace App\Filament\Resources\NetworkMemberResource\Pages;

use App\Filament\Resources\NetworkMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNetworkMembers extends ListRecords
{
    protected static string $resource = NetworkMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
