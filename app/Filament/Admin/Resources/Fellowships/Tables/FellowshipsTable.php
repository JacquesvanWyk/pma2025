<?php

namespace App\Filament\Admin\Resources\Fellowships\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FellowshipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-fellowship.png')),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('User Account')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('country')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('member_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('focus_areas')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                TextColumn::make('privacy_level')
                    ->badge()
                    ->colors([
                        'success' => 'public',
                        'warning' => 'network_only',
                    ])
                    ->sortable(),

                IconColumn::make('is_approved')
                    ->boolean()
                    ->sortable()
                    ->label('Approved'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),

                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('approvedBy.name')
                    ->label('Approved By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),

                SelectFilter::make('privacy_level')
                    ->options([
                        'public' => 'Public',
                        'network_only' => 'Network Only',
                    ]),

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
            ->defaultSort('created_at', 'desc');
    }
}
