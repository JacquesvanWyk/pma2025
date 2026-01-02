<?php

namespace App\Filament\Admin\Resources\Sermons;

use App\Filament\Admin\Resources\Sermons\Pages\CreateSermon;
use App\Filament\Admin\Resources\Sermons\Pages\EditSermon;
use App\Filament\Admin\Resources\Sermons\Pages\ListSermons;
use App\Filament\Admin\Resources\Sermons\Schemas\SermonForm;
use App\Filament\Admin\Resources\Sermons\Tables\SermonsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\Sermon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SermonResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = Sermon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Sermons';

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return SermonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SermonsTable::configure($table);
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
            'index' => ListSermons::route('/'),
            'create' => CreateSermon::route('/create'),
            'edit' => EditSermon::route('/{record}/edit'),
            'slide-editor' => Pages\SlideEditor::route('/{record}/slides'),
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
