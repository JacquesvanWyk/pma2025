<?php

namespace App\Filament\Admin\Resources\EmailSubscribers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmailSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('emailList.title')
                    ->label('List')
                    ->sortable(),

                TextColumn::make('language')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en' => 'English',
                        'af' => 'Afrikaans',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'en' => 'info',
                        'af' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('subscribed_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Active')
                    ->color('danger'),
            ])
            ->defaultSort('subscribed_at', 'desc')
            ->filters([
                SelectFilter::make('email_list_id')
                    ->label('List')
                    ->relationship('emailList', 'title'),
                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'af' => 'Afrikaans',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
