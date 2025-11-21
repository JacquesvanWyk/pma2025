<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->icon('heroicon-o-calendar')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('organizer_type')
                            ->label('Organizer Type')
                            ->options([
                                'App\Models\Individual' => 'Individual',
                                'App\Models\Fellowship' => 'Fellowship',
                                'App\Models\Ministry' => 'Ministry',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('organizer_id')
                            ->label('Organizer')
                            ->options(function (callable $get) {
                                $organizerType = $get('organizer_type');
                                if (! $organizerType) {
                                    return [];
                                }

                                return $organizerType::pluck('name', 'id');
                            })
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('feed_post_id')
                            ->label('Related Feed Post')
                            ->relationship('feedPost', 'title')
                            ->searchable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('event_type')
                            ->label('Event Type')
                            ->placeholder('e.g., Conference, Workshop, Outreach')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('max_attendees')
                            ->numeric()
                            ->label('Max Attendees')
                            ->placeholder('Leave blank for unlimited'),
                    ]),

                Section::make('Date & Time')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_date'),
                    ]),

                Section::make('Location')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_online')
                            ->label('Online Event')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('online_url')
                            ->url()
                            ->label('Online Event URL')
                            ->placeholder('https://zoom.us/...')
                            ->visible(fn (callable $get) => $get('is_online'))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('location')
                            ->label('Physical Location')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => ! $get('is_online'))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('country')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => ! $get('is_online')),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => ! $get('is_online')),

                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->visible(fn (callable $get) => ! $get('is_online')),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->visible(fn (callable $get) => ! $get('is_online')),
                    ]),
            ]);
    }
}
