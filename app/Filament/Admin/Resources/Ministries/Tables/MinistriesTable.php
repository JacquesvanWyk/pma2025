<?php

namespace App\Filament\Admin\Resources\Ministries\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MinistriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-ministry.png')),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('No owner'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->copyable(),

                TextColumn::make('country')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('focus_areas')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state ?? 'pending'))
                    ->sortable(),

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

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),

                SelectFilter::make('country')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),

                // Approve Action
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                            'is_active' => true,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Ministry')
                    ->modalDescription('Are you sure you want to approve this ministry? It will be visible on the public network map.')
                    ->modalSubmitActionLabel('Yes, Approve'),

                // Reject Action
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->helperText('Please provide a reason for rejection. This will be stored for reference.')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'rejected_at' => now(),
                            'rejected_by' => Auth::id(),
                            'is_active' => false,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reject Ministry')
                    ->modalDescription('Are you sure you want to reject this ministry?')
                    ->modalSubmitActionLabel('Yes, Reject'),

                // Revert to Pending Action
                Action::make('revert')
                    ->label('Revert to Pending')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn ($record) => in_array($record->status, ['approved', 'rejected']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'pending',
                            'approved_at' => null,
                            'approved_by' => null,
                            'rejected_at' => null,
                            'rejected_by' => null,
                            'rejection_reason' => null,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Revert to Pending')
                    ->modalDescription('This will reset the status to pending and remove approval/rejection details.')
                    ->modalSubmitActionLabel('Yes, Revert'),
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
