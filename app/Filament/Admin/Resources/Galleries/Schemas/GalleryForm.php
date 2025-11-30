<?php

namespace App\Filament\Admin\Resources\Galleries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gallery Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft'),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date & Time'),
                    ]),

                Section::make('Event Link')
                    ->description('Link to an existing event or enter a manual date and location')
                    ->columns(2)
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('Linked Event')
                            ->helperText('Select an event or create a new one')
                            ->createOptionForm([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->rows(3)
                                    ->default(''),
                                DateTimePicker::make('start_date')
                                    ->label('Start Date')
                                    ->required(),
                                DateTimePicker::make('end_date')
                                    ->label('End Date'),
                                TextInput::make('location')
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->maxLength(255),
                                Select::make('event_type')
                                    ->options([
                                        'camp_meeting' => 'Camp Meeting',
                                        'conference' => 'Conference',
                                        'seminar' => 'Seminar',
                                        'outreach' => 'Outreach',
                                        'other' => 'Other',
                                    ]),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $data['description'] = $data['description'] ?? '';
                                $data['organizer_type'] = \App\Models\User::class;
                                $data['organizer_id'] = auth()->id();

                                return \App\Models\Event::create($data)->id;
                            })
                            ->columnSpanFull(),

                        DatePicker::make('event_date')
                            ->label('Event Date')
                            ->helperText('Only if not linked to an event')
                            ->hidden(fn ($get) => filled($get('event_id'))),

                        TextInput::make('location')
                            ->label('Location')
                            ->placeholder('e.g., Gauteng')
                            ->helperText('Only if not linked to an event')
                            ->hidden(fn ($get) => filled($get('event_id'))),
                    ]),

                Section::make('Cover Image')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('galleries/covers')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->helperText('Upload a cover image for this gallery (max 5MB)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tags')
                    ->schema([
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('description'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
