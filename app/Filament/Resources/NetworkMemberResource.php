<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NetworkMemberResource\Pages;
use App\Models\NetworkMember;
use App\Notifications\NetworkMemberApprovalNotification;
use App\Notifications\NetworkMemberRejectionNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NetworkMemberResource extends Resource
{
    protected static ?string $model = NetworkMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Network Members';

    protected static ?string $navigationGroup = 'Network';

    protected static ?string $modelLabel = 'Network Member';

    protected static ?string $pluralModelLabel = 'Network Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'individual' => 'Individual Believer',
                        'group' => 'Fellowship Group',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Name / Group Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\Textarea::make('bio')
                    ->label('Bio / Description')
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\Section::make('Household Information')
                    ->description('This information helps PMA understand the reach of our network. Names can be kept private if desired.')
                    ->visible(fn ($get) => $get('type') === 'individual')
                    ->schema([
                        Forms\Components\TextInput::make('total_believers')
                            ->label('Total Believers in Household')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->helperText('Including yourself, how many believers are in your household?'),

                        Forms\Components\Repeater::make('household_members')
                            ->label('Household Member Names (Optional)')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->helperText('You can optionally add the names of other believers in your household. This helps PMA but can be kept private.')
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->collapsible(),

                        Forms\Components\Toggle::make('show_household_members')
                            ->label('Show household member names publicly')
                            ->default(false)
                            ->helperText('If disabled, names will only be visible to PMA administrators'),
                    ]),

                Forms\Components\Section::make('Location Information')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->required()
                            ->helperText('Example: -26.2041'),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->required()
                            ->helperText('Example: 28.0473'),

                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->helperText('Full address for display purposes'),
                    ]),

                Forms\Components\Textarea::make('meeting_times')
                    ->label('Meeting Times (Groups Only)')
                    ->rows(2)
                    ->visible(fn ($get) => $get('type') === 'group')
                    ->helperText('e.g., Sundays 10:00 AM'),

                Forms\Components\Section::make('Privacy Settings')
                    ->schema([
                        Forms\Components\Toggle::make('show_email')
                            ->label('Show email publicly')
                            ->default(true),

                        Forms\Components\Toggle::make('show_phone')
                            ->label('Show phone publicly')
                            ->default(false),
                    ]),

                Forms\Components\Select::make('languages')
                    ->label('Languages')
                    ->relationship('languages', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),

                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Approved At')
                    ->visible(fn ($get) => $get('status') === 'approved'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'individual' => 'info',
                        'group' => 'success',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'individual' => 'Individual',
                        'group' => 'Group',
                    }),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('total_believers')
                    ->label('Believers')
                    ->default(1)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('languages.name')
                    ->label('Languages')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'individual' => 'Individual',
                        'group' => 'Group',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),

                Tables\Filters\SelectFilter::make('languages')
                    ->label('Languages')
                    ->relationship('languages', 'name')
                    ->searchable()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Approve Action
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                        ]);

                        // Send approval notification email
                        if ($record->user) {
                            $record->user->notify(new NetworkMemberApprovalNotification($record));
                        } else {
                            Notification::route('mail', $record->email)
                                ->notify(new NetworkMemberApprovalNotification($record));
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Network Member')
                    ->modalDescription('Are you sure you want to approve this network member? They will be visible on the public map.')
                    ->modalSubmitActionLabel('Yes, Approve'),

                // Reject Action
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->helperText('Please provide a reason for rejection. This will not be shown to the applicant.')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'rejected_at' => now(),
                            'rejected_by' => Auth::id(),
                        ]);

                        // Send rejection notification email
                        $record->user->notify(new NetworkMemberRejectionNotification($record));
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reject Network Member')
                    ->modalDescription('Are you sure you want to reject this network member?')
                    ->modalSubmitActionLabel('Yes, Reject'),

                // Revert to Pending Action
                Tables\Actions\Action::make('revert')
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNetworkMembers::route('/'),
            'create' => Pages\CreateNetworkMember::route('/create'),
            'edit' => Pages\EditNetworkMember::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['languages']);
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Type' => $record->type,
            'Email' => $record->email,
        ];
    }
}
