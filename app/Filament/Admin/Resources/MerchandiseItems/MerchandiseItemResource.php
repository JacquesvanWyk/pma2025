<?php

namespace App\Filament\Admin\Resources\MerchandiseItems;

use App\Filament\Admin\Resources\MerchandiseItems\Pages\CreateMerchandiseItem;
use App\Filament\Admin\Resources\MerchandiseItems\Pages\EditMerchandiseItem;
use App\Filament\Admin\Resources\MerchandiseItems\Pages\ListMerchandiseItems;
use App\Filament\Admin\Resources\MerchandiseItems\Schemas\MerchandiseItemForm;
use App\Filament\Admin\Resources\MerchandiseItems\Tables\MerchandiseItemsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\MerchandiseItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MerchandiseItemResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = MerchandiseItem::class;

    protected static ?string $modelLabel = 'Merchandise';

    protected static ?string $pluralModelLabel = 'Merchandise';

    protected static ?string $slug = 'merchandise-items';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static \UnitEnum|string|null $navigationGroup = 'Camp Meeting';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return MerchandiseItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MerchandiseItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMerchandiseItems::route('/'),
            'create' => CreateMerchandiseItem::route('/create'),
            'edit' => EditMerchandiseItem::route('/{record}/edit'),
        ];
    }
}
