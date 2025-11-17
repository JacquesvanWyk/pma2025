<?php

namespace App\Filament\Admin\Resources\PledgeProgress\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PledgeProgressTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')
                    ->label('Month')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('current_amount')
                    ->label('Current Amount')
                    ->money('ZAR')
                    ->sortable(),
                TextColumn::make('goal_amount')
                    ->label('Goal')
                    ->money('ZAR')
                    ->sortable(),
                TextColumn::make('percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($record) => number_format($record->percentage, 2).'%')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->percentage >= 100 => 'success',
                        $record->percentage >= 75 => 'primary',
                        $record->percentage >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
