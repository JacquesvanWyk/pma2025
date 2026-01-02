<?php

namespace App\Filament\Admin\Resources\SlidePresentations;

use App\Filament\Admin\Resources\SlidePresentations\Pages\ListSlidePresentations;
use App\Filament\Admin\Resources\SlidePresentations\Pages\ViewSlidePresentation;
use App\Filament\Admin\Resources\SlidePresentations\Tables\SlidePresentationsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\SlidePresentation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SlidePresentationResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = SlidePresentation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Slide Presentations';

    protected static UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return SlidePresentationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSlidePresentations::route('/'),
            'view' => ViewSlidePresentation::route('/{record}'),
        ];
    }
}
