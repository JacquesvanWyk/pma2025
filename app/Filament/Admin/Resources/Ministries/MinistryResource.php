<?php

namespace App\Filament\Admin\Resources\Ministries;

use App\Filament\Admin\Resources\Ministries\Pages\CreateMinistry;
use App\Filament\Admin\Resources\Ministries\Pages\EditMinistry;
use App\Filament\Admin\Resources\Ministries\Pages\ListMinistries;
use App\Filament\Admin\Resources\Ministries\Schemas\MinistryForm;
use App\Filament\Admin\Resources\Ministries\Tables\MinistriesTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\Ministry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinistryResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = Ministry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static \UnitEnum|string|null $navigationGroup = 'Network';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return MinistryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MinistriesTable::configure($table);
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
            'index' => ListMinistries::route('/'),
            'create' => CreateMinistry::route('/create'),
            'edit' => EditMinistry::route('/{record}/edit'),
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
