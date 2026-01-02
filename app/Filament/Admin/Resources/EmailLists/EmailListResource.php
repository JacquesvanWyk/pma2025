<?php

namespace App\Filament\Admin\Resources\EmailLists;

use App\Filament\Admin\Resources\EmailLists\Pages\CreateEmailList;
use App\Filament\Admin\Resources\EmailLists\Pages\EditEmailList;
use App\Filament\Admin\Resources\EmailLists\Pages\ListEmailLists;
use App\Filament\Admin\Resources\EmailLists\Schemas\EmailListForm;
use App\Filament\Admin\Resources\EmailLists\Tables\EmailListsTable;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\EmailList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmailListResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = EmailList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'Communications';

    protected static ?string $navigationLabel = 'Email Lists';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EmailListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailListsTable::configure($table);
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
            'index' => ListEmailLists::route('/'),
            'create' => CreateEmailList::route('/create'),
            'edit' => EditEmailList::route('/{record}/edit'),
        ];
    }
}
