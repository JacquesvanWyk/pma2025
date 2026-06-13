<?php

namespace App\Filament\Admin\Resources\CampBookings\Pages;

use App\Filament\Admin\Resources\CampBookings\CampBookingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCampBooking extends EditRecord
{
    protected static string $resource = CampBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make(), RestoreAction::make()];
    }

    protected function afterSave(): void
    {
        $this->record->recalculateTotals();
        $this->record->save();
    }
}
