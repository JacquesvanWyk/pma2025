<?php

namespace App\Filament\Admin\Resources\CampTshirtOrders;

use App\Filament\Admin\Resources\CampTshirtOrders\Pages\CreateCampTshirtOrder;
use App\Filament\Admin\Resources\CampTshirtOrders\Pages\EditCampTshirtOrder;
use App\Filament\Admin\Resources\CampTshirtOrders\Pages\ListCampTshirtOrders;
use App\Filament\Admin\Resources\CampTshirtOrders\Schemas\CampTshirtOrderForm;
use App\Filament\Admin\Resources\CampTshirtOrders\Tables\CampTshirtOrdersTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\CampTshirtOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampTshirtOrderResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = CampTshirtOrder::class;

    protected static ?string $modelLabel = 'Merchandise Order';

    protected static ?string $pluralModelLabel = 'Merchandise Orders';

    protected static ?string $slug = 'camp-tshirt-orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static \UnitEnum|string|null $navigationGroup = 'Camp Meeting';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CampTshirtOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampTshirtOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampTshirtOrders::route('/'),
            'create' => CreateCampTshirtOrder::route('/create'),
            'edit' => EditCampTshirtOrder::route('/{record}/edit'),
        ];
    }
}
