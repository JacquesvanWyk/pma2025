<?php

namespace App\Filament\Admin\Resources\Shorts\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ShortForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video Details')
                    ->icon('heroicon-o-video-camera')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add tags...')
                            ->suggestions(['Faith', 'Encouragement', 'Bible', 'Prayer', 'Testimony', 'Teaching', 'Worship'])
                            ->columnSpanFull(),
                    ]),

                Section::make('Video Source')
                    ->icon('heroicon-o-play')
                    ->description('Upload a video file OR provide a YouTube URL')
                    ->columns(1)
                    ->schema([
                        Forms\Components\FileUpload::make('video_path')
                            ->label('Upload Video')
                            ->disk('r2')
                            ->directory('shorts')
                            ->visibility('public')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(512000)
                            ->helperText('Max 500MB. MP4, WebM, or OGG format. Uploads to Cloudflare R2.'),

                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=... or https://youtu.be/...')
                            ->helperText('Paste a YouTube video URL (regular or shorts)'),

                        Forms\Components\FileUpload::make('thumbnail_path')
                            ->label('Thumbnail')
                            ->image()
                            ->disk('r2')
                            ->directory('shorts/thumbnails')
                            ->visibility('public')
                            ->imageEditor()
                            ->helperText('Optional. If not provided, YouTube thumbnail will be used for YouTube videos.'),
                    ]),

                Section::make('Publishing')
                    ->icon('heroicon-o-eye')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now()),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Placeholder::make('view_count')
                            ->label('Views')
                            ->content(fn ($record) => $record?->view_count ?? 0)
                            ->visibleOn('edit'),
                    ]),
            ]);
    }
}
