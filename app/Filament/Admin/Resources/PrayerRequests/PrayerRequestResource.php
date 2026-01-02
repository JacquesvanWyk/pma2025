<?php

namespace App\Filament\Admin\Resources\PrayerRequests;

use App\Filament\Admin\Resources\PrayerRequests\Pages\CreatePrayerRequest;
use App\Filament\Admin\Resources\PrayerRequests\Pages\EditPrayerRequest;
use App\Filament\Admin\Resources\PrayerRequests\Pages\ListPrayerRequests;
use App\Filament\Admin\Resources\PrayerRequests\Schemas\PrayerRequestForm;
use App\Filament\Admin\Resources\PrayerRequests\Tables\PrayerRequestsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\PrayerRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrayerRequestResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = PrayerRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static \UnitEnum|string|null $navigationGroup = 'Community';

    protected static ?string $navigationLabel = 'Prayer Requests';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PrayerRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrayerRequestsTable::configure($table);
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
            'index' => ListPrayerRequests::route('/'),
            'create' => CreatePrayerRequest::route('/create'),
            'edit' => EditPrayerRequest::route('/{record}/edit'),
        ];
    }
}
