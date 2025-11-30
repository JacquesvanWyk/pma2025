<?php

namespace App\Filament\Admin\Resources\PictureStudies;

use App\Filament\Admin\Resources\PictureStudies\Pages\CreatePictureStudy;
use App\Filament\Admin\Resources\PictureStudies\Pages\EditPictureStudy;
use App\Filament\Admin\Resources\PictureStudies\Pages\ListPictureStudies;
use App\Filament\Admin\Resources\PictureStudies\Schemas\PictureStudyForm;
use App\Filament\Admin\Resources\PictureStudies\Tables\PictureStudiesTable;
use App\Models\PictureStudy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PictureStudyResource extends Resource
{
    protected static ?string $model = PictureStudy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Picture Studies';

    protected static ?string $modelLabel = 'Picture Study';

    protected static ?string $pluralModelLabel = 'Picture Studies';

    public static function form(Schema $schema): Schema
    {
        return PictureStudyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PictureStudiesTable::configure($table);
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
            'index' => ListPictureStudies::route('/'),
            'create' => CreatePictureStudy::route('/create'),
            'edit' => EditPictureStudy::route('/{record}/edit'),
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
