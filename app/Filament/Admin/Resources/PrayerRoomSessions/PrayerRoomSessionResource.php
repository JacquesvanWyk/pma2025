<?php

namespace App\Filament\Admin\Resources\PrayerRoomSessions;

use App\Filament\Admin\Resources\PrayerRoomSessions\Pages\CreatePrayerRoomSession;
use App\Filament\Admin\Resources\PrayerRoomSessions\Pages\EditPrayerRoomSession;
use App\Filament\Admin\Resources\PrayerRoomSessions\Pages\ListPrayerRoomSessions;
use App\Filament\Admin\Resources\PrayerRoomSessions\Schemas\PrayerRoomSessionForm;
use App\Filament\Admin\Resources\PrayerRoomSessions\Tables\PrayerRoomSessionsTable;
use App\Models\PrayerRoomSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PrayerRoomSessionResource extends Resource
{
    protected static ?string $model = PrayerRoomSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|UnitEnum|null $navigationGroup = 'Ministry';

    protected static ?string $navigationLabel = 'Prayer Room Sessions';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PrayerRoomSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrayerRoomSessionsTable::configure($table);
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
            'index' => ListPrayerRoomSessions::route('/'),
            'create' => CreatePrayerRoomSession::route('/create'),
            'edit' => EditPrayerRoomSession::route('/{record}/edit'),
        ];
    }
}
