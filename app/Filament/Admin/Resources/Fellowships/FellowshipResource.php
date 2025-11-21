<?php

namespace App\Filament\Admin\Resources\Fellowships;

use App\Filament\Admin\Resources\Fellowships\Pages\CreateFellowship;
use App\Filament\Admin\Resources\Fellowships\Pages\EditFellowship;
use App\Filament\Admin\Resources\Fellowships\Pages\ListFellowships;
use App\Filament\Admin\Resources\Fellowships\Schemas\FellowshipForm;
use App\Filament\Admin\Resources\Fellowships\Tables\FellowshipsTable;
use App\Models\Fellowship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FellowshipResource extends Resource
{
    protected static ?string $model = Fellowship::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return FellowshipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FellowshipsTable::configure($table);
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
            'index' => ListFellowships::route('/'),
            'create' => CreateFellowship::route('/create'),
            'edit' => EditFellowship::route('/{record}/edit'),
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
