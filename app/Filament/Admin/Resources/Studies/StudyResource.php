<?php

namespace App\Filament\Admin\Resources\Studies;

use App\Filament\Admin\Resources\Studies\Pages\CreateStudy;
use App\Filament\Admin\Resources\Studies\Pages\EditStudy;
use App\Filament\Admin\Resources\Studies\Pages\ListStudies;
use App\Filament\Admin\Resources\Studies\Schemas\StudyForm;
use App\Filament\Admin\Resources\Studies\Tables\StudiesTable;
use App\Models\Study;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function form(Schema $schema): Schema
    {
        return StudyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudiesTable::configure($table);
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
            'index' => ListStudies::route('/'),
            'create' => CreateStudy::route('/create'),
            'edit' => EditStudy::route('/{record}/edit'),
        ];
    }

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
