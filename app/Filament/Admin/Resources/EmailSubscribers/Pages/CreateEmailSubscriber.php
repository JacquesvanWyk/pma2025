<?php

namespace App\Filament\Admin\Resources\EmailSubscribers\Pages;

use App\Filament\Admin\Resources\EmailSubscribers\EmailSubscriberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailSubscriber extends CreateRecord
{
    protected static string $resource = EmailSubscriberResource::class;
}
