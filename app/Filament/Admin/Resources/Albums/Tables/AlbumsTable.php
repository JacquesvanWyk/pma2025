<?php

namespace App\Filament\Admin\Resources\Albums\Tables;

use App\Jobs\GenerateAlbumDownloads;
use App\Models\Album;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AlbumsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->disk('public')
                    ->label('Cover')
                    ->square()
                    ->size(60),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('artist')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('songs_count')
                    ->label('Songs')
                    ->counts('songs')
                    ->sortable(),

                TextColumn::make('release_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('suggested_donation')
                    ->label('Donation')
                    ->money('ZAR')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('download_count')
                    ->label('Downloads')
                    ->sortable(),

                IconColumn::make('bundles_generated_at')
                    ->label('Bundles')
                    ->boolean()
                    ->getStateUsing(fn (Album $record): bool => $record->hasBundles())
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('regenerate_downloads')
                    ->label('Regenerate Downloads')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate Downloads')
                    ->modalDescription('This will regenerate all download bundles for this album. This may take a few minutes.')
                    ->action(function (Album $record): void {
                        GenerateAlbumDownloads::dispatch($record);

                        Notification::make()
                            ->title('Download regeneration started')
                            ->body('The download bundles are being regenerated in the background.')
                            ->success()
                            ->send();
                    }),
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
