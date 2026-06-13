<?php

namespace App\Filament\Admin\Resources\CampTshirtOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CampTshirtOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('size')
                    ->badge()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('Unit price')
                    ->money('ZAR'),

                TextColumn::make('donation_amount')
                    ->label('Donation')
                    ->money('ZAR'),

                TextColumn::make('total')
                    ->money('ZAR')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ])
                    ->sortable(),

                TextColumn::make('transaction_reference')
                    ->label('Reference')
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
