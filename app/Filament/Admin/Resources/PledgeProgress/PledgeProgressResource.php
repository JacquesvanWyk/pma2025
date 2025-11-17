<?php

namespace App\Filament\Admin\Resources\PledgeProgress;

use App\Filament\Admin\Resources\PledgeProgress\Pages\CreatePledgeProgress;
use App\Filament\Admin\Resources\PledgeProgress\Pages\EditPledgeProgress;
use App\Filament\Admin\Resources\PledgeProgress\Pages\ListPledgeProgress;
use App\Filament\Admin\Resources\PledgeProgress\Schemas\PledgeProgressForm;
use App\Filament\Admin\Resources\PledgeProgress\Tables\PledgeProgressTable;
use App\Models\PledgeProgress;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PledgeProgressResource extends Resource
{
    protected static ?string $model = PledgeProgress::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Monthly Pledges';

    protected static ?string $modelLabel = 'Pledge Progress';

    protected static ?string $pluralModelLabel = 'Pledge Progress';

    public static function form(Schema $schema): Schema
    {
        return PledgeProgressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PledgeProgressTable::configure($table);
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
            'index' => ListPledgeProgress::route('/'),
            'create' => CreatePledgeProgress::route('/create'),
            'edit' => EditPledgeProgress::route('/{record}/edit'),
        ];
    }
}
