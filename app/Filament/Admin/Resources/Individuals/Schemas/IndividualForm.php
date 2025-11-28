<?php

namespace App\Filament\Admin\Resources\Individuals\Schemas;

use App\Filament\Forms\Components\GoogleMapLocationPicker;
use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IndividualForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Profile Photo')
                            ->image()
                            ->disk('public')
                            ->directory('network-members/photos')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('bio')
                            ->label('About')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Skills & Ministry')
                    ->icon('heroicon-o-sparkles')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TagsInput::make('professional_skills')
                            ->label('Professional Trade / Skills')
                            ->placeholder('e.g., Plumber, Accountant, Web Developer')
                            ->helperText('List your profession or trade to help identify resources within the Body of Christ.')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('ministry_skills')
                            ->label('Ministry Gifts & Service')
                            ->placeholder('e.g., Preaching, Youth Ministry, Music, Hospitality')
                            ->helperText('How do you serve the body?')
                            ->columnSpanFull(),
                    ]),

                Section::make('Household Information')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Forms\Components\TextInput::make('total_believers')
                            ->label('Total Believers in Household')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->helperText('Including yourself, how many believers are in your household?'),
                    ]),

                Section::make('Languages')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Forms\Components\CheckboxList::make('languages')
                            ->relationship('languages', 'name')
                            ->columns(4)
                            ->searchable(),
                    ]),

                Section::make('Location')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        GoogleMapLocationPicker::make('location_picker')
                            ->label('Select Location')
                            ->latitudeField('latitude')
                            ->longitudeField('longitude')
                            ->cityField('city')
                            ->provinceField('province')
                            ->countryField('country')
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('province')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('country')
                                    ->maxLength(255),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->step(0.00000001),
                            ]),
                    ]),

                Section::make('Privacy Settings')
                    ->icon('heroicon-o-eye')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('show_email')
                            ->label('Show email publicly')
                            ->default(true),

                        Forms\Components\Toggle::make('show_phone')
                            ->label('Show phone publicly')
                            ->default(false),
                    ]),

                Section::make('Status & Approval')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->disabled(),

                        Forms\Components\Select::make('approved_by')
                            ->relationship('approver', 'name')
                            ->disabled()
                            ->searchable(),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->rows(2)
                            ->visible(fn ($get) => $get('status') === 'rejected'),
                    ]),

                Forms\Components\Hidden::make('type')
                    ->default('individual'),
            ]);
    }
}
