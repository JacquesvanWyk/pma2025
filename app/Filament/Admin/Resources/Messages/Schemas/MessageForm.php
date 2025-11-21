<?php

namespace App\Filament\Admin\Resources\Messages\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message Details')
                    ->icon('heroicon-o-envelope')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('from_user_id')
                            ->label('From User')
                            ->relationship('sender', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('recipient_type')
                            ->label('Recipient Type')
                            ->options([
                                'App\Models\Individual' => 'Individual',
                                'App\Models\Fellowship' => 'Fellowship',
                                'App\Models\Ministry' => 'Ministry',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('recipient_id')
                            ->label('Recipient')
                            ->options(function (callable $get) {
                                $recipientType = $get('recipient_type');
                                if (! $recipientType) {
                                    return [];
                                }

                                return $recipientType::pluck('name', 'id');
                            })
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('subject')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('read_at')
                            ->label('Read At')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
