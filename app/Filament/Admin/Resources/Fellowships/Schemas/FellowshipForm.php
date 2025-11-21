<?php

namespace App\Filament\Admin\Resources\Fellowships\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FellowshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->icon('heroicon-o-user-group')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->directory('fellowships/logos')
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
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('show_phone')
                            ->label('Show phone publicly')
                            ->default(false),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('show_email')
                            ->label('Show email publicly')
                            ->default(false),

                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Location')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001),
                    ]),

                Section::make('Fellowship Details')
                    ->icon('heroicon-o-sparkles')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('member_count')
                            ->numeric()
                            ->label('Member Count'),

                        Forms\Components\TextInput::make('meeting_time')
                            ->label('Meeting Time')
                            ->placeholder('e.g., Sundays at 10:00 AM')
                            ->maxLength(255),

                        Forms\Components\TagsInput::make('focus_areas')
                            ->placeholder('e.g., Evangelism, Teaching')
                            ->suggestions(['Evangelism', 'Teaching', 'Medical', 'Agriculture', 'Children', 'Youth', 'Bible Translation'])
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('languages')
                            ->placeholder('e.g., English, Afrikaans, Zulu')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('resources')
                            ->placeholder('e.g., Building, Sound System')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Additional tags')
                            ->columnSpanFull(),
                    ]),

                Section::make('Privacy & Status')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('privacy_level')
                            ->options([
                                'public' => 'Public',
                                'network_only' => 'Network Only',
                            ])
                            ->default('network_only')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved')
                            ->default(false),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->disabled(),

                        Forms\Components\Select::make('approved_by')
                            ->relationship('approvedBy', 'name')
                            ->disabled()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
