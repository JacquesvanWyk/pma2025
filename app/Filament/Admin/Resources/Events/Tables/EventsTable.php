<?php

namespace App\Filament\Admin\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('organizer_type')
                    ->label('Organizer Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('organizer.name')
                    ->label('Organizer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event_type')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->start_date->isPast() ? 'danger' : 'success'),

                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_online')
                    ->boolean()
                    ->sortable()
                    ->label('Online'),

                TextColumn::make('location')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('country')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('max_attendees')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('rsvps_count')
                    ->counts('rsvps')
                    ->sortable()
                    ->label('RSVPs'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('organizer_type')
                    ->label('Organizer Type')
                    ->options([
                        'App\Models\Individual' => 'Individual',
                        'App\Models\Fellowship' => 'Fellowship',
                        'App\Models\Ministry' => 'Ministry',
                    ]),

                TernaryFilter::make('is_online')
                    ->label('Event Type')
                    ->placeholder('All')
                    ->trueLabel('Online')
                    ->falseLabel('In-Person'),

                SelectFilter::make('country')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }
}
