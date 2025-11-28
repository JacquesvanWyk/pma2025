<?php

namespace App\Filament\Admin\Resources\EmailSubscribers\Pages;

use App\Filament\Admin\Resources\EmailSubscribers\EmailSubscriberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailSubscribers extends ListRecords
{
    protected static string $resource = EmailSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
