<?php

namespace App\Filament\Admin\Resources\PrayerRequests\Pages;

use App\Filament\Admin\Resources\PrayerRequests\PrayerRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrayerRequest extends EditRecord
{
    protected static string $resource = PrayerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
