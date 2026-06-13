<?php

namespace App\Filament\Admin\Resources\CampTshirtOrders\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampTshirtOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Details')
                ->icon('heroicon-o-shopping-bag')
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

                    Forms\Components\TextInput::make('size')
                        ->required()
                        ->maxLength(10),

                    Forms\Components\TextInput::make('quantity')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                        ])
                        ->required(),
                ]),

            Section::make('Payment')
                ->icon('heroicon-o-credit-card')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('unit_price')
                        ->numeric()
                        ->prefix('R')
                        ->required(),

                    Forms\Components\TextInput::make('donation_amount')
                        ->numeric()
                        ->prefix('R')
                        ->default(0),

                    Forms\Components\TextInput::make('total')
                        ->numeric()
                        ->prefix('R')
                        ->required(),

                    Forms\Components\TextInput::make('transaction_reference')
                        ->maxLength(255),
                ]),
        ]);
    }
}
