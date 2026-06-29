<?php

namespace App\Filament\Admin\Resources\CampBookings\Tables;

use App\Mail\CampBookingConfirmationMail;
use App\Models\AccommodationType;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class CampBookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('accommodationType.name')
                    ->label('Accommodation')
                    ->sortable(),

                TextColumn::make('adults')
                    ->sortable(),

                TextColumn::make('children')
                    ->sortable(),

                TextColumn::make('nights')
                    ->sortable(),

                TextColumn::make('estimated_total')
                    ->label('Est. Total')
                    ->money('ZAR')
                    ->sortable(),

                TextColumn::make('deposit_amount')
                    ->label('Deposit')
                    ->money('ZAR')
                    ->sortable(),

                IconColumn::make('deposit_paid')
                    ->label('Deposit Paid')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('accommodation_type_id')
                    ->label('Accommodation')
                    ->options(AccommodationType::pluck('name', 'id'))
                    ->searchable(),

                TernaryFilter::make('deposit_paid')
                    ->label('Deposit paid'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('copy_banking_details')
                    ->label('Copy Banking Details')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('gray')
                    ->extraAttributes(fn ($record) => [
                        'x-data' => '',
                        'x-on:click.stop' => 'navigator.clipboard.writeText('.json_encode(
                            implode("\n", array_filter([
                                'CAMP MEETING 2026 — BANKING DETAILS',
                                '',
                                'Account Name: '.config('camp.eft.account_name'),
                                'Bank: '.config('camp.eft.bank'),
                                config('camp.eft.account_number') ? 'Account Number: '.config('camp.eft.account_number') : null,
                                config('camp.eft.branch_code') ? 'Branch Code: '.config('camp.eft.branch_code') : null,
                                'Reference: '.$record->eftReference(),
                                'Amount: R '.number_format($record->deposit_amount, 2),
                            ]))
                        ).')',
                    ])
                    ->action(function ($record) {
                        Notification::make()
                            ->title('Banking details copied to clipboard')
                            ->success()
                            ->send();
                    }),
                Action::make('send_reminder')
                    ->label('Send Reminder')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription(fn ($record) => 'Resend booking confirmation & EFT details to '.$record->email.'?')
                    ->action(function ($record) {
                        Mail::to($record->email)->send(
                            new CampBookingConfirmationMail($record, $record->eftReference())
                        );
                        Notification::make()
                            ->title('Reminder sent to '.$record->email)
                            ->success()
                            ->send();
                    }),
                Action::make('mark_deposit_paid')
                    ->label('Mark Deposit Paid')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->deposit_paid)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'deposit_paid' => true,
                        'deposit_paid_at' => now(),
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('send_reminders')
                        ->label('Send Reminder')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalDescription('Resend booking confirmation & EFT details to all selected bookings?')
                        ->action(function (Collection $records) {
                            $sent = 0;
                            foreach ($records as $record) {
                                Mail::to($record->email)->send(
                                    new CampBookingConfirmationMail($record, $record->eftReference())
                                );
                                $sent++;
                            }
                            Notification::make()
                                ->title("Reminder sent to {$sent} booking(s)")
                                ->success()
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
