<?php

namespace App\Filament\Admin\Resources\Donations;

use App\Filament\Admin\Resources\Donations\Pages\CreateDonation;
use App\Filament\Admin\Resources\Donations\Pages\EditDonation;
use App\Filament\Admin\Resources\Donations\Pages\ListDonations;
use App\Filament\Admin\Resources\Donations\Pages\ViewDonation;
use App\Filament\Admin\Resources\Donations\Schemas\DonationForm;
use App\Filament\Admin\Resources\Donations\Schemas\DonationInfolist;
use App\Filament\Admin\Resources\Donations\Tables\DonationsTable;
use App\Models\Donation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationGroup = 'Finances';

    protected static ?string $navigationLabel = 'Donations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DonationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DonationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationsTable::configure($table);
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
            'index' => ListDonations::route('/'),
            'create' => CreateDonation::route('/create'),
            'view' => ViewDonation::route('/{record}'),
            'edit' => EditDonation::route('/{record}/edit'),
        ];
    }
}
