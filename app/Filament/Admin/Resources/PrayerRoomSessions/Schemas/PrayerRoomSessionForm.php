<?php

namespace App\Filament\Admin\Resources\PrayerRoomSessions\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrayerRoomSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Session Details')
                    ->icon('heroicon-o-heart')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Praying for Our Friends')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('session_date')
                            ->required()
                            ->label('Date & Time'),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('prayer-room')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Optional description or theme for this session')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
