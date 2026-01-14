<?php

namespace App\Filament\Admin\Resources\Donations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DonationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Donation Details')
                    ->schema([
                        TextEntry::make('gateway')
                            ->label('Payment Gateway')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'payfast' => 'info',
                                'paystack' => 'success',
                                'paypal' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'payfast' => 'PayFast',
                                'paystack' => 'Paystack',
                                'paypal' => 'PayPal',
                                default => $state,
                            }),

                        TextEntry::make('transaction_reference')
                            ->label('Transaction Reference')
                            ->copyable(),

                        TextEntry::make('amount')
                            ->label('Amount')
                            ->money(fn ($record) => $record->currency ?? 'ZAR'),

                        TextEntry::make('currency')
                            ->label('Currency'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'successful' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'cancelled' => 'gray',
                                default => 'gray',
                            }),

                        IconEntry::make('is_recurring')
                            ->label('Recurring')
                            ->boolean(),

                        TextEntry::make('item_name')
                            ->label('Item/Purpose'),

                        TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime('d M Y H:i:s'),
                    ])
                    ->columns(2),

                Section::make('Donor Information')
                    ->schema([
                        TextEntry::make('donor_name')
                            ->label('Donor Name')
                            ->default('Anonymous'),

                        TextEntry::make('donor_email')
                            ->label('Donor Email')
                            ->copyable(),
                    ])
                    ->columns(2),

                Section::make('Raw Metadata')
                    ->schema([
                        TextEntry::make('metadata')
                            ->label('Webhook Data')
                            ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
