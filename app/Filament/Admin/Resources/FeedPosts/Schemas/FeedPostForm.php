<?php

namespace App\Filament\Admin\Resources\FeedPosts\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('author_type')
                            ->label('Author Type')
                            ->options([
                                'App\Models\Individual' => 'Individual',
                                'App\Models\Fellowship' => 'Fellowship',
                                'App\Models\Ministry' => 'Ministry',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('author_id')
                            ->label('Author')
                            ->options(function (callable $get) {
                                $authorType = $get('author_type');
                                if (! $authorType) {
                                    return [];
                                }

                                return $authorType::pluck('name', 'id');
                            })
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('type')
                            ->options([
                                'update' => 'Update',
                                'prayer' => 'Prayer Request',
                                'testimony' => 'Testimony',
                                'resource' => 'Resource',
                                'event' => 'Event',
                            ])
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Attachments')
                    ->icon('heroicon-o-photo')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->multiple()
                            ->directory('feed/images')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('attachments')
                            ->multiple()
                            ->directory('feed/attachments')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->label('Video URL')
                            ->placeholder('https://youtube.com/...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Post Status')
                    ->icon('heroicon-o-check-badge')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_pinned')
                            ->label('Pinned')
                            ->default(false),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved')
                            ->default(false),

                        Forms\Components\DateTimePicker::make('answered_at')
                            ->label('Answered At (Prayer Requests)')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
