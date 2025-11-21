<?php

namespace App\Filament\Admin\Resources\PrayerRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class PrayerRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('request')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'prayed',
                        'primary' => 'answered',
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_private')
                    ->label('Private')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('emailed')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('prayer_room_date')
                    ->label('Session')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'prayed' => 'Prayed',
                        'answered' => 'Answered',
                    ]),

                Tables\Filters\TernaryFilter::make('is_private')
                    ->label('Private Requests'),

                Tables\Filters\TernaryFilter::make('emailed')
                    ->label('Emailed'),
            ])
            ->recordActions([
                ViewAction::make(),
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
