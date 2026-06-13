<?php

namespace App\Filament\Admin\Resources\AccommodationTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccommodationTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('base_price')
                    ->label('Base/night')
                    ->money('ZAR')
                    ->sortable(),

                TextColumn::make('base_adults')
                    ->label('Base adults'),

                TextColumn::make('extra_adult_price')
                    ->label('+Adult')
                    ->money('ZAR')
                    ->placeholder('—'),

                TextColumn::make('extra_child_price')
                    ->label('+Child')
                    ->money('ZAR')
                    ->placeholder('—'),

                TextColumn::make('total_units')
                    ->label('Rooms/Sites')
                    ->placeholder('Unlimited'),

                TextColumn::make('bookings_count')
                    ->label('Booked')
                    ->counts('bookings')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
