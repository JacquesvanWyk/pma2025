<?php

namespace App\Filament\Resources\PostReactionResource\Pages;

use App\Filament\Resources\PostReactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPostReaction extends ViewRecord
{
    protected static string $resource = PostReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
