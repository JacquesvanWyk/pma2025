<?php

namespace App\Filament\Admin\Resources\FeedPosts\Pages;

use App\Filament\Admin\Resources\FeedPosts\FeedPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedPost extends CreateRecord
{
    protected static string $resource = FeedPostResource::class;
}
