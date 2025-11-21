<?php

namespace App\Filament\Admin\Resources\Individuals;

use App\Filament\Admin\Resources\Individuals\Pages\CreateIndividual;
use App\Filament\Admin\Resources\Individuals\Pages\EditIndividual;
use App\Filament\Admin\Resources\Individuals\Pages\ListIndividuals;
use App\Filament\Admin\Resources\Individuals\Schemas\IndividualForm;
use App\Filament\Admin\Resources\Individuals\Tables\IndividualsTable;
use App\Models\Individual;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndividualResource extends Resource
{
    protected static ?string $model = Individual::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    public static function form(Schema $schema): Schema
    {
        return IndividualForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IndividualsTable::configure($table);
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
            'index' => ListIndividuals::route('/'),
            'create' => CreateIndividual::route('/create'),
            'edit' => EditIndividual::route('/{record}/edit'),
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
