<?php

namespace App\Filament\Admin\Resources\Fellowships;

use App\Filament\Admin\Resources\Fellowships\Pages\CreateFellowship;
use App\Filament\Admin\Resources\Fellowships\Pages\EditFellowship;
use App\Filament\Admin\Resources\Fellowships\Pages\ListFellowships;
use App\Filament\Admin\Resources\Fellowships\Schemas\FellowshipForm;
use App\Filament\Admin\Resources\Fellowships\Tables\FellowshipsTable;
use App\Models\NetworkMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FellowshipResource extends Resource
{
    protected static ?string $model = NetworkMember::class;

    protected static ?string $modelLabel = 'Fellowship';

    protected static ?string $pluralModelLabel = 'Fellowships';

    protected static ?string $slug = 'fellowships';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Network';

    protected static ?int $navigationSort = 2;

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', 'group')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
