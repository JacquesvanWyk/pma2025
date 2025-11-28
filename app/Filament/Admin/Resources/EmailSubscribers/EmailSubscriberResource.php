<?php

namespace App\Filament\Admin\Resources\EmailSubscribers;

use App\Filament\Admin\Resources\EmailSubscribers\Pages\CreateEmailSubscriber;
use App\Filament\Admin\Resources\EmailSubscribers\Pages\EditEmailSubscriber;
use App\Filament\Admin\Resources\EmailSubscribers\Pages\ListEmailSubscribers;
use App\Filament\Admin\Resources\EmailSubscribers\Schemas\EmailSubscriberForm;
use App\Filament\Admin\Resources\EmailSubscribers\Tables\EmailSubscribersTable;
use App\Models\EmailSubscriber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmailSubscriberResource extends Resource
{
    protected static ?string $model = EmailSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Communications';

    protected static ?string $navigationLabel = 'Subscribers';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return EmailSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailSubscribersTable::configure($table);
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
            'index' => ListEmailSubscribers::route('/'),
            'create' => CreateEmailSubscriber::route('/create'),
            'edit' => EditEmailSubscriber::route('/{record}/edit'),
        ];
    }
}
