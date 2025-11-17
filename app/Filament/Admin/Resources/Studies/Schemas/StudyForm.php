<?php

namespace App\Filament\Admin\Resources\Studies\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'h2',
                        'h3',
                        'bulletList',
                        'orderedList',
                        'link',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->columnSpanFull(),

                Textarea::make('excerpt')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('featured_image')
                    ->label('Featured Image')
                    ->image()
                    ->imageEditor()
                    ->directory('studies/images')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->helperText('Upload a featured image for this study (max 5MB). This will override any AI-generated image.')
                    ->columnSpanFull(),

                Select::make('language')
                    ->required()
                    ->options([
                        'english' => 'English',
                        'afrikaans' => 'Afrikaans',
                    ])
                    ->default('english'),

                Select::make('type')
                    ->options([
                        'beginner' => 'Beginner Level',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        'youth' => 'Youth Friendly',
                        'sermon' => 'Sermon Outline',
                        'tract' => 'Tract Format',
                    ]),

                Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft'),

                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        Textarea::make('description'),
                    ]),

                Textarea::make('meta_description')
                    ->rows(2)
                    ->columnSpanFull(),

                DateTimePicker::make('published_at')
                    ->label('Publish Date & Time'),
            ]);
    }
}
