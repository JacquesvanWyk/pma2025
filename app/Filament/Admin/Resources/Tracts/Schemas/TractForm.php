<?php

namespace App\Filament\Admin\Resources\Tracts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TractForm
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
                    ])
                    ->columnSpanFull(),

                Select::make('language')
                    ->required()
                    ->options([
                        'english' => 'English',
                        'afrikaans' => 'Afrikaans',
                    ])
                    ->default('english'),

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
                    ->columnSpanFull(),

                DateTimePicker::make('published_at')
                    ->label('Publish Date & Time'),

                TextInput::make('download_count')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
