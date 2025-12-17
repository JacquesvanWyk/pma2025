<?php

namespace App\Filament\Admin\Resources\Albums\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SongsRelationManager extends RelationManager
{
    protected static string $relationship = 'songs';

    protected static ?string $title = 'Songs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                    ->columnSpan(1),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpan(1),

                TextInput::make('track_number')
                    ->label('Track #')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->columnSpan(1),

                TextInput::make('duration')
                    ->placeholder('3:45')
                    ->helperText('Format: minutes:seconds')
                    ->columnSpan(1),

                FileUpload::make('wav_file')
                    ->label('Audio File (WAV/MP3)')
                    ->disk('public')
                    ->directory('albums/songs')
                    ->acceptedFileTypes(['audio/*', '.wav', '.mp3'])
                    ->maxSize(204800)
                    ->helperText('Upload audio file (max 200MB)')
                    ->columnSpanFull(),

                FileUpload::make('mp4_video')
                    ->label('Video File (MP4) - Optional')
                    ->disk('public')
                    ->directory('albums/videos')
                    ->acceptedFileTypes(['video/mp4'])
                    ->maxSize(512000)
                    ->helperText('Upload MP4 video file if available (max 500MB)')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->rows(2)
                    ->columnSpanFull(),

                Textarea::make('lyrics')
                    ->rows(8)
                    ->helperText('Song lyrics (optional)')
                    ->columnSpanFull(),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),

                Toggle::make('is_preview')
                    ->label('Preview Song')
                    ->helperText('Show this song before album release'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->reorderable('track_number')
            ->columns([
                TextColumn::make('track_number')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('duration')
                    ->placeholder('—'),

                IconColumn::make('wav_file')
                    ->label('Audio')
                    ->icon(fn ($state) => $state ? 'heroicon-o-musical-note' : 'heroicon-o-x-mark')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                IconColumn::make('mp4_video')
                    ->label('Video')
                    ->icon(fn ($state) => $state ? 'heroicon-o-video-camera' : 'heroicon-o-x-mark')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('play_count')
                    ->label('Plays')
                    ->sortable()
                    ->color('danger'),

                TextColumn::make('download_count')
                    ->label('Downloads')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('track_number');
    }
}
