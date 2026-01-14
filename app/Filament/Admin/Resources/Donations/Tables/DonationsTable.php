<?php

namespace App\Filament\Admin\Resources\Donations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('gateway')
                    ->label('Gateway')
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

                TextColumn::make('donor_name')
                    ->label('Donor')
                    ->searchable()
                    ->default('Anonymous'),

                TextColumn::make('donor_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money(fn ($record) => $record->currency ?? 'ZAR')
                    ->sortable(),

                TextColumn::make('item_name')
                    ->label('Purpose')
                    ->default('Donation')
                    ->toggleable(),

                IconColumn::make('is_recurring')
                    ->label('Recurring')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'successful' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('transaction_reference')
                    ->label('Reference')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('gateway')
                    ->label('Gateway')
                    ->options([
                        'payfast' => 'PayFast',
                        'paystack' => 'Paystack',
                        'paypal' => 'PayPal',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'successful' => 'Successful',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('is_recurring')
                    ->label('Type')
                    ->options([
                        '1' => 'Recurring',
                        '0' => 'One-time',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
