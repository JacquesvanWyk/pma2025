<?php

namespace App\Filament\Admin\Resources\EmailSubscribers\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmailSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscriber Details')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('email_list_id')
                            ->label('Email List')
                            ->relationship('emailList', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('language')
                            ->options([
                                'en' => 'English',
                                'af' => 'Afrikaans',
                            ])
                            ->default('en')
                            ->required(),

                        Forms\Components\DateTimePicker::make('subscribed_at')
                            ->label('Subscribed At')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('unsubscribed_at')
                            ->label('Unsubscribed At')
                            ->nullable(),
                    ]),
            ]);
    }
}
