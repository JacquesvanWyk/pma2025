<?php

namespace App\Filament\Admin\Resources\PrayerRequests\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PrayerRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->components([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'prayed' => 'Prayed',
                                'answered' => 'Answered',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),

                Forms\Components\Textarea::make('request')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                Grid::make(2)
                    ->components([
                        Forms\Components\TextInput::make('prayer_room_date')
                            ->label('Prayer Room Session')
                            ->placeholder('e.g., 27 August 2025')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_private')
                            ->label('Private Request')
                            ->default(false),
                    ]),

                Forms\Components\Textarea::make('admin_notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
