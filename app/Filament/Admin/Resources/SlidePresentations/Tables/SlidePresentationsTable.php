<?php

namespace App\Filament\Admin\Resources\SlidePresentations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SlidePresentationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('first_slide_image')
                    ->label('')
                    ->state(function ($record) {
                        $slides = $record->slides ?? [];
                        if (! empty($slides) && isset($slides[0]['image_path'])) {
                            return asset('storage/'.$slides[0]['image_path']);
                        }

                        return null;
                    })
                    ->width(120)
                    ->height(68),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('total_slides')
                    ->label('Slides')
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete' => 'success',
                        'generating' => 'warning',
                        'outline_ready' => 'info',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'outline_ready' => 'Outline Ready',
                        'generating' => 'Generating',
                        'complete' => 'Complete',
                        'failed' => 'Failed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
