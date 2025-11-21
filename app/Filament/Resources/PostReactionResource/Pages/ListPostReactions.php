<?php

namespace App\Filament\Resources\PostReactionResource\Pages;

use App\Filament\Resources\PostReactionResource;
use App\Models\PostReaction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPostReactions extends ListRecords
{
    protected static string $resource = PostReactionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'like' => Tab::make('Likes')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'like'))
                ->badge(PostReaction::query()->where('type', 'like')->count()),
            'pray' => Tab::make('Pray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'pray'))
                ->badge(PostReaction::query()->where('type', 'pray')->count()),
            'amen' => Tab::make('Amen')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'amen'))
                ->badge(PostReaction::query()->where('type', 'amen')->count()),
            'heart' => Tab::make('Love')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'heart'))
                ->badge(PostReaction::query()->where('type', 'heart')->count()),
        ];
    }
}
