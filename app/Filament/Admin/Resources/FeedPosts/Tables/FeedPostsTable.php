<?php

namespace App\Filament\Admin\Resources\FeedPosts\Tables;

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

class FeedPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'update',
                        'warning' => 'prayer',
                        'success' => 'testimony',
                        'info' => 'resource',
                        'danger' => 'event',
                    ])
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('content')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('author_type')
                    ->label('Author Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_pinned')
                    ->boolean()
                    ->sortable()
                    ->label('Pinned'),

                IconColumn::make('is_approved')
                    ->boolean()
                    ->sortable()
                    ->label('Approved'),

                TextColumn::make('answered_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->label('Answered'),

                TextColumn::make('reactions_count')
                    ->counts('reactions')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('comments_count')
                    ->counts('comments')
                    ->sortable()
                    ->toggleable(),

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

                SelectFilter::make('type')
                    ->options([
                        'update' => 'Update',
                        'prayer' => 'Prayer Request',
                        'testimony' => 'Testimony',
                        'resource' => 'Resource',
                        'event' => 'Event',
                    ]),

                SelectFilter::make('author_type')
                    ->label('Author Type')
                    ->options([
                        'App\Models\Individual' => 'Individual',
                        'App\Models\Fellowship' => 'Fellowship',
                        'App\Models\Ministry' => 'Ministry',
                    ]),

                TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),

                TernaryFilter::make('is_pinned')
                    ->label('Pinned')
                    ->placeholder('All')
                    ->trueLabel('Pinned')
                    ->falseLabel('Not Pinned'),

                TernaryFilter::make('answered_at')
                    ->label('Prayer Status')
                    ->placeholder('All')
                    ->trueLabel('Answered')
                    ->falseLabel('Not Answered')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('answered_at'),
                        false: fn ($query) => $query->whereNull('answered_at'),
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
