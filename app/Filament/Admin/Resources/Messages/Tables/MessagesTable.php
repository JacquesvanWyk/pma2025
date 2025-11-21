<?php

namespace App\Filament\Admin\Resources\Messages\Tables;

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

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('recipient_type')
                    ->label('Recipient Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->sortable(),

                TextColumn::make('recipient.name')
                    ->label('To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('message')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),

                TextColumn::make('read_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('recipient_type')
                    ->label('Recipient Type')
                    ->options([
                        'App\Models\Individual' => 'Individual',
                        'App\Models\Fellowship' => 'Fellowship',
                        'App\Models\Ministry' => 'Ministry',
                    ]),

                TernaryFilter::make('read_at')
                    ->label('Read Status')
                    ->placeholder('All')
                    ->trueLabel('Read')
                    ->falseLabel('Unread')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('read_at'),
                        false: fn ($query) => $query->whereNull('read_at'),
                    ),
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
            ->defaultSort('created_at', 'desc');
    }
}
