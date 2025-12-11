<?php

namespace App\Filament\Admin\Resources\VideoProjects\Pages;

use App\Filament\Admin\Resources\VideoProjects\VideoProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVideoProjects extends ManageRecords
{
    protected static string $resource = VideoProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
