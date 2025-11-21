<?php

namespace App\Filament\Admin\Resources\FeedPosts\Pages;

use App\Filament\Admin\Resources\FeedPosts\FeedPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedPosts extends ListRecords
{
    protected static string $resource = FeedPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
