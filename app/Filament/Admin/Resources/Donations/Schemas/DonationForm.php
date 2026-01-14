<?php

namespace App\Filament\Admin\Resources\Donations\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Donation Details')
                    ->schema([
                        Select::make('gateway')
                            ->label('Payment Gateway')
                            ->options([
                                'payfast' => 'PayFast',
                                'paystack' => 'Paystack',
                                'paypal' => 'PayPal',
                            ])
                            ->required(),

                        TextInput::make('transaction_reference')
                            ->label('Transaction Reference')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('R')
                            ->required(),

                        Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'ZAR' => 'ZAR (South African Rand)',
                                'USD' => 'USD (US Dollar)',
                            ])
                            ->default('ZAR')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'successful' => 'Successful',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('successful'),

                        Toggle::make('is_recurring')
                            ->label('Recurring Donation')
                            ->default(false),

                        TextInput::make('item_name')
                            ->label('Item/Purpose')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Donor Information')
                    ->schema([
                        TextInput::make('donor_name')
                            ->label('Donor Name')
                            ->maxLength(255),

                        TextInput::make('donor_email')
                            ->label('Donor Email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Raw Data')
                    ->schema([
                        Textarea::make('metadata')
                            ->label('Metadata (JSON)')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
