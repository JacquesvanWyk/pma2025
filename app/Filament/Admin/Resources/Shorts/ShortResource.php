<?php

namespace App\Filament\Admin\Resources\Shorts;

use App\Filament\Admin\Resources\Shorts\Pages\CreateShort;
use App\Filament\Admin\Resources\Shorts\Pages\EditShort;
use App\Filament\Admin\Resources\Shorts\Pages\ListShorts;
use App\Filament\Admin\Resources\Shorts\Schemas\ShortForm;
use App\Filament\Admin\Resources\Shorts\Tables\ShortsTable;
use App\Models\Short;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShortResource extends Resource
{
    protected static ?string $model = Short::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $navigationLabel = 'Shorts';

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ShortForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShorts::route('/'),
            'create' => CreateShort::route('/create'),
            'edit' => EditShort::route('/{record}/edit'),
        ];
    }
}
