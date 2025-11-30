<?php

namespace App\Filament\Admin\Resources\PictureStudies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PictureStudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
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

                        Select::make('language')
                            ->required()
                            ->options([
                                'en' => 'English',
                                'af' => 'Afrikaans',
                            ])
                            ->default('en'),

                        Select::make('status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published'),
                    ]),

                Section::make('Image')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Picture Study Image')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('picture-studies')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->required()
                            ->helperText('Upload the infographic image (max 10MB)')
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

                Section::make('Social Sharing')
                    ->description('Share this picture study on social media')
                    ->schema([
                        Toggle::make('publish_to_facebook')
                            ->label('Publish to Facebook Page')
                            ->helperText('Automatically post this image to the PMA Facebook Page')
                            ->default(false)
                            ->dehydrated(false),
                    ])
                    ->collapsible(),
            ]);
    }
}
