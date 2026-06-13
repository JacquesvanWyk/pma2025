<?php

namespace App\Filament\Admin\Resources\AccommodationTypes\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AccommodationTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Details')
                ->icon('heroicon-o-home')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->disk('public_images')
                        ->directory('camp')
                        ->columnSpanFull(),
                ]),

            Section::make('Pricing')
                ->icon('heroicon-o-currency-dollar')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('base_price')
                        ->required()
                        ->numeric()
                        ->prefix('R')
                        ->label('Base price / night'),

                    Forms\Components\TextInput::make('base_adults')
                        ->required()
                        ->numeric()
                        ->default(2)
                        ->label('Base adults included'),

                    Forms\Components\TextInput::make('extra_adult_price')
                        ->numeric()
                        ->prefix('R')
                        ->label('Extra adult / night')
                        ->helperText('Leave blank if no extra adults allowed'),

                    Forms\Components\TextInput::make('extra_child_price')
                        ->numeric()
                        ->prefix('R')
                        ->label('Extra child (2–11) / night')
                        ->helperText('Leave blank if no children allowed'),
                ]),

            Section::make('Occupancy & Availability')
                ->icon('heroicon-o-users')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('max_adults')
                        ->required()
                        ->numeric()
                        ->default(2)
                        ->label('Max adults'),

                    Forms\Components\TextInput::make('max_children')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->label('Max children'),

                    Forms\Components\TextInput::make('total_units')
                        ->numeric()
                        ->label('Total rooms / sites')
                        ->helperText('Leave blank for unlimited')
                        ->placeholder('Unlimited'),
                ]),

            Section::make('Status')
                ->icon('heroicon-o-check-circle')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on booking page')
                        ->default(true),
                ]),
        ]);
    }
}
