<?php

namespace App\Filament\Admin\Resources\MerchandiseItems\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MerchandiseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Item Details')
                ->icon('heroicon-o-tag')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('R')
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on camp page')
                        ->helperText('Enable only once price is confirmed')
                        ->default(false),

                    Forms\Components\TagsInput::make('sizes')
                        ->label('Available sizes')
                        ->placeholder('Add size (e.g. S, M, L)')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Product image')
                        ->image()
                        ->disk('public_images')
                        ->directory('camp')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
