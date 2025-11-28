<?php

namespace App\Filament\Admin\Resources\PrayerRoomSessions\Pages;

use App\Filament\Admin\Resources\PrayerRoomSessions\PrayerRoomSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrayerRoomSessions extends ListRecords
{
    protected static string $resource = PrayerRoomSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
