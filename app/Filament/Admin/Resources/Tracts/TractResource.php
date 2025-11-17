<?php

namespace App\Filament\Admin\Resources\Tracts;

use App\Filament\Admin\Resources\Tracts\Pages\CreateTract;
use App\Filament\Admin\Resources\Tracts\Pages\EditTract;
use App\Filament\Admin\Resources\Tracts\Pages\ListTracts;
use App\Filament\Admin\Resources\Tracts\Schemas\TractForm;
use App\Filament\Admin\Resources\Tracts\Tables\TractsTable;
use App\Models\Tract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TractResource extends Resource
{
    protected static ?string $model = Tract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    public static function form(Schema $schema): Schema
    {
        return TractForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TractsTable::configure($table);
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
            'index' => ListTracts::route('/'),
            'create' => CreateTract::route('/create'),
            'edit' => EditTract::route('/{record}/edit'),
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
