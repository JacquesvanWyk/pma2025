<?php

namespace App\Filament\Admin\Resources\VideoProjects;

use App\Filament\Admin\Resources\VideoProjects\Pages\CreateVideoProject;
use App\Filament\Admin\Resources\VideoProjects\Pages\EditVideoProject;
use App\Filament\Admin\Resources\VideoProjects\Pages\ManageVideoProjects;
use App\Models\VideoProject;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VideoProjectResource extends Resource
{
    protected static ?string $model = VideoProject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFilm;

    protected static ?string $navigationLabel = 'Lyric Videos';

    protected static ?string $modelLabel = 'Lyric Video';

    protected static ?string $pluralModelLabel = 'Lyric Videos';

    protected static \UnitEnum|string|null $navigationGroup = 'Media';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('type', 'lyric_video')
            ->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Project')
                ->tabs([
                    Tabs\Tab::make('Details')
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            TextInput::make('name')
                                ->label('Project Name')
                                ->required()
                                ->maxLength(255)
                                ->default('Untitled Lyric Video'),
                            Textarea::make('description')
                                ->label('Description')
                                ->rows(2)
                                ->maxLength(500),
                            FileUpload::make('audio_path')
                                ->label('Audio File')
                                ->disk('public')
                                ->directory('video-editor/audio')
                                ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/ogg', 'audio/webm'])
                                ->maxSize(51200)
                                ->helperText('Upload MP3, WAV, M4A, or OGG (max 50MB)'),
                            Select::make('resolution')
                                ->label('Resolution')
                                ->options([
                                    '1920x1080' => 'YouTube (1920x1080)',
                                    '1080x1920' => 'Shorts/Reels (1080x1920)',
                                    '1080x1080' => 'Square (1080x1080)',
                                ])
                                ->default('1920x1080'),
                        ]),

                    Tabs\Tab::make('Background')
                        ->icon(Heroicon::OutlinedPhoto)
                        ->schema([
                            Select::make('background_type')
                                ->label('Background Type')
                                ->options([
                                    'color' => 'Solid Color',
                                    'image' => 'Image',
                                    'video' => 'Video',
                                ])
                                ->default('color')
                                ->live(),
                            ColorPicker::make('background_color')
                                ->label('Background Color')
                                ->default('#000000')
                                ->visible(fn ($get) => $get('background_type') === 'color')
                                ->afterStateHydrated(function ($component, $record) {
                                    if ($record && $record->background_type === 'color') {
                                        $component->state($record->background_value);
                                    }
                                })
                                ->dehydrated(fn ($get) => $get('background_type') === 'color'),
                            FileUpload::make('background_image_file')
                                ->label('Background Image')
                                ->disk('public')
                                ->directory('video-editor/backgrounds')
                                ->image()
                                ->imageResizeMode('cover')
                                ->visible(fn ($get) => $get('background_type') === 'image')
                                ->afterStateHydrated(function ($component, $record) {
                                    if ($record && $record->background_type === 'image' && $record->background_value) {
                                        $value = $record->background_value;
                                        if (str_contains($value, '/storage/')) {
                                            $value = substr($value, strpos($value, '/storage/') + 9);
                                        }
                                        $component->state($value);
                                    }
                                })
                                ->dehydrated(fn ($get) => $get('background_type') === 'image'),
                            FileUpload::make('background_video_file')
                                ->label('Background Video')
                                ->disk('public')
                                ->directory('video-editor/backgrounds')
                                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                                ->maxSize(102400)
                                ->helperText('Upload MP4, WebM, or MOV (max 100MB)')
                                ->visible(fn ($get) => $get('background_type') === 'video')
                                ->afterStateHydrated(function ($component, $record) {
                                    if ($record && $record->background_type === 'video' && $record->background_value) {
                                        $value = $record->background_value;
                                        // Convert full URL to relative path
                                        if (str_contains($value, '/storage/')) {
                                            $value = substr($value, strpos($value, '/storage/') + 9);
                                        }
                                        $component->state($value);
                                    }
                                })
                                ->dehydrated(fn ($get) => $get('background_type') === 'video'),
                            FileUpload::make('logo_path')
                                ->label('Logo/Watermark')
                                ->disk('public')
                                ->directory('video-editor/logos')
                                ->image()
                                ->helperText('Optional logo to display on the video'),
                            Select::make('logo_position')
                                ->label('Logo Position')
                                ->options([
                                    'top-left' => 'Top Left',
                                    'top-right' => 'Top Right',
                                    'bottom-left' => 'Bottom Left',
                                    'bottom-right' => 'Bottom Right',
                                ])
                                ->default('bottom-right'),
                        ]),

                    Tabs\Tab::make('Lyrics')
                        ->icon(Heroicon::OutlinedMusicalNote)
                        ->schema([
                            Textarea::make('reference_lyrics')
                                ->label('Reference Lyrics')
                                ->rows(10)
                                ->helperText('Paste the correct lyrics here. These will be used by AI to correct auto-detected timestamps.'),
                            Repeater::make('lyricTimestamps')
                                ->relationship()
                                ->label('Lyric Timestamps')
                                ->schema([
                                    TextInput::make('text')
                                        ->label('Lyric Line')
                                        ->required(),
                                    TextInput::make('start_ms')
                                        ->label('Start (ms)')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('end_ms')
                                        ->label('End (ms)')
                                        ->numeric()
                                        ->required(),
                                    Select::make('animation')
                                        ->label('Animation')
                                        ->options([
                                            'fade' => 'Fade',
                                            'slide' => 'Slide',
                                            'bounce' => 'Bounce',
                                            'typewriter' => 'Typewriter',
                                        ])
                                        ->default('fade'),
                                ])
                                ->columns(4)
                                ->orderColumn('order')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['text'] ?? null),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->description(fn (VideoProject $record): ?string => $record->description),
                TextColumn::make('background_type')
                    ->label('Background')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'color' => 'gray',
                        'image' => 'info',
                        'video' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('lyric_timestamps_count')
                    ->label('Lyrics')
                    ->counts('lyricTimestamps')
                    ->sortable(),
                TextColumn::make('formatted_duration')
                    ->label('Duration'),
                TextColumn::make('resolution')
                    ->label('Resolution')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('exports_count')
                    ->label('Exports')
                    ->counts('exports')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->label('Modified')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->recordActions([
                Action::make('open_editor')
                    ->label('Open Editor')
                    ->icon(Heroicon::OutlinedPlay)
                    ->color('success')
                    ->url(fn (VideoProject $record): string => route('filament.admin.pages.video-editor', ['project' => $record->id])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No lyric videos yet')
            ->emptyStateDescription('Create your first lyric video project.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageVideoProjects::route('/'),
            'create' => CreateVideoProject::route('/create'),
            'edit' => EditVideoProject::route('/{record}/edit'),
        ];
    }
}
