<?php

namespace App\Filament\Admin\Resources\EmailLists\Pages;

use App\Filament\Admin\Resources\EmailLists\EmailListResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailList extends CreateRecord
{
    protected static string $resource = EmailListResource::class;
}
