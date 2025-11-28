<?php

namespace App\Filament\Admin\Resources\PrayerRoomSessions\Pages;

use App\Filament\Admin\Resources\PrayerRoomSessions\PrayerRoomSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrayerRoomSession extends EditRecord
{
    protected static string $resource = PrayerRoomSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
