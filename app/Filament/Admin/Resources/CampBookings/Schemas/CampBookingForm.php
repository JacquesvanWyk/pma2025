<?php

namespace App\Filament\Admin\Resources\CampBookings\Schemas;

use App\Models\AccommodationType;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Accommodation')
                ->icon('heroicon-o-home')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('accommodation_type_id')
                        ->label('Accommodation Type')
                        ->options(AccommodationType::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),

                    Forms\Components\TextInput::make('adults')
                        ->numeric()
                        ->default(2)
                        ->required(),

                    Forms\Components\TextInput::make('children')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    Forms\Components\TextInput::make('nights')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Forms\Components\DatePicker::make('arrival_date'),

                    Forms\Components\DatePicker::make('departure_date'),
                ]),

            Section::make('Guest Details')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\Textarea::make('notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Section::make('Financial')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('estimated_total')
                        ->numeric()
                        ->prefix('R')
                        ->label('Estimated total'),

                    Forms\Components\TextInput::make('deposit_amount')
                        ->numeric()
                        ->prefix('R')
                        ->label('Deposit (50%)'),

                    Forms\Components\Toggle::make('deposit_paid')
                        ->label('Deposit paid')
                        ->live(),

                    Forms\Components\DateTimePicker::make('deposit_paid_at')
                        ->label('Deposit paid at')
                        ->visible(fn ($get) => $get('deposit_paid')),

                    Forms\Components\FileUpload::make('proof_of_payment')
                        ->label('Proof of payment')
                        ->disk('public')
                        ->directory('camp/proofs')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
