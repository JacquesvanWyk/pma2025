<?php

namespace App\Filament\Admin\Resources\FeedPosts\Pages;

use App\Filament\Admin\Resources\FeedPosts\FeedPostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedPost extends EditRecord
{
    protected static string $resource = FeedPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
