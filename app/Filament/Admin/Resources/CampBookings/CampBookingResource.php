<?php

namespace App\Filament\Admin\Resources\CampBookings;

use App\Filament\Admin\Resources\CampBookings\Pages\CreateCampBooking;
use App\Filament\Admin\Resources\CampBookings\Pages\EditCampBooking;
use App\Filament\Admin\Resources\CampBookings\Pages\ListCampBookings;
use App\Filament\Admin\Resources\CampBookings\Schemas\CampBookingForm;
use App\Filament\Admin\Resources\CampBookings\Tables\CampBookingsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\CampBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampBookingResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = CampBooking::class;

    protected static ?string $modelLabel = 'Booking';

    protected static ?string $pluralModelLabel = 'Bookings';

    protected static ?string $slug = 'camp-bookings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static \UnitEnum|string|null $navigationGroup = 'Camp Meeting';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CampBookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampBookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampBookings::route('/'),
            'create' => CreateCampBooking::route('/create'),
            'edit' => EditCampBooking::route('/{record}/edit'),
        ];
    }
}
