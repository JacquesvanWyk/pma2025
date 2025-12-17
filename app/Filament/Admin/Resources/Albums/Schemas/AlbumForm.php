<?php

namespace App\Filament\Admin\Resources\Albums\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Album Details')
                    ->icon('heroicon-o-musical-note')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                        TextInput::make('artist')
                            ->required()
                            ->default('PMA Worship')
                            ->columnSpan(1),

                        DatePicker::make('release_date')
                            ->label('Release Date')
                            ->columnSpan(1),

                        TimePicker::make('release_time')
                            ->label('Release Time')
                            ->seconds(false)
                            ->helperText('SA timezone (Africa/Johannesburg)')
                            ->columnSpan(1),

                        TextInput::make('suggested_donation')
                            ->label('Suggested Donation')
                            ->numeric()
                            ->prefix('R')
                            ->default(0)
                            ->helperText('Suggested donation amount for downloads')
                            ->columnSpan(1),

                        FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('albums/covers')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing')
                    ->icon('heroicon-o-globe-alt')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Make this album visible on the website'),

                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Feature this album on the homepage'),
                    ]),
            ]);
    }
}
