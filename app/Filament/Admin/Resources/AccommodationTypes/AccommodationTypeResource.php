<?php

namespace App\Filament\Admin\Resources\AccommodationTypes;

use App\Filament\Admin\Resources\AccommodationTypes\Pages\CreateAccommodationType;
use App\Filament\Admin\Resources\AccommodationTypes\Pages\EditAccommodationType;
use App\Filament\Admin\Resources\AccommodationTypes\Pages\ListAccommodationTypes;
use App\Filament\Admin\Resources\AccommodationTypes\Schemas\AccommodationTypeForm;
use App\Filament\Admin\Resources\AccommodationTypes\Tables\AccommodationTypesTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\AccommodationType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccommodationTypeResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = AccommodationType::class;

    protected static ?string $modelLabel = 'Accommodation Type';

    protected static ?string $pluralModelLabel = 'Accommodation Types';

    protected static ?string $slug = 'accommodation-types';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static \UnitEnum|string|null $navigationGroup = 'Camp Meeting';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return AccommodationTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccommodationTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAccommodationTypes::route('/'),
            'create' => CreateAccommodationType::route('/create'),
            'edit' => EditAccommodationType::route('/{record}/edit'),
        ];
    }
}
