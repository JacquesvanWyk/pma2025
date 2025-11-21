<?php

namespace App\Filament\Admin\Resources\FeedPosts;

use App\Filament\Admin\Resources\FeedPosts\Pages\CreateFeedPost;
use App\Filament\Admin\Resources\FeedPosts\Pages\EditFeedPost;
use App\Filament\Admin\Resources\FeedPosts\Pages\ListFeedPosts;
use App\Filament\Admin\Resources\FeedPosts\Schemas\FeedPostForm;
use App\Filament\Admin\Resources\FeedPosts\Tables\FeedPostsTable;
use App\Models\FeedPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedPostResource extends Resource
{
    protected static ?string $model = FeedPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    public static function form(Schema $schema): Schema
    {
        return FeedPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedPosts::route('/'),
            'create' => CreateFeedPost::route('/create'),
            'edit' => EditFeedPost::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
