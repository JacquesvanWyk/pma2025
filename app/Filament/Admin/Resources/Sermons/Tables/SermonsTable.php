<?php

namespace App\Filament\Admin\Resources\Sermons\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SermonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sermon_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('generateSlides')
                    ->label('Generate Slides')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.pages.slide-generator', ['sermon' => $record->id]))
                    ->visible(fn ($record) => $record->content !== null),
                Action::make('editSlides')
                    ->label('Edit Slides')
                    ->icon('heroicon-o-presentation-chart-bar')
                    ->color('success')
                    ->url(fn ($record) => route('filament.admin.resources.sermons.sermons.slide-editor', ['record' => $record]))
                    ->visible(fn ($record) => $record->slides()->exists()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
