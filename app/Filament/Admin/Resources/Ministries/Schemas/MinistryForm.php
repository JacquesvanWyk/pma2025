<?php

namespace App\Filament\Admin\Resources\Ministries\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MinistryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('user_id')
                            ->label('Owner')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('No owner (legacy ministry)'),

                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->directory('ministries/logos')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Contact Information')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('show_email')
                            ->label('Show email publicly')
                            ->default(true),

                        Forms\Components\Toggle::make('show_phone')
                            ->label('Show phone publicly')
                            ->default(false),
                    ]),

                Section::make('Location')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('province')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001),
                    ]),

                Section::make('Social Media & Links')
                    ->icon('heroicon-o-link')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('youtube')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('facebook')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('instagram')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('twitter')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Ministry Details')
                    ->icon('heroicon-o-tag')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TagsInput::make('focus_areas')
                            ->placeholder('e.g., Evangelism, Teaching, Medical')
                            ->suggestions(['Evangelism', 'Teaching', 'Medical', 'Agriculture', 'Children', 'Youth', 'Bible Translation', 'Church Planting', 'Discipleship', 'Mercy Ministry'])
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('languages')
                            ->placeholder('e.g., English, Afrikaans, Zulu')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Additional tags')
                            ->columnSpanFull(),
                    ]),

                Section::make('Approval Status')
                    ->icon('heroicon-o-check-badge')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->disabled()
                            ->visible(fn ($get) => $get('status') === 'approved'),

                        Forms\Components\Select::make('approved_by')
                            ->relationship('approvedBy', 'name')
                            ->disabled()
                            ->searchable()
                            ->visible(fn ($get) => $get('status') === 'approved'),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(2)
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('status') === 'rejected'),
                    ]),
            ]);
    }
}
