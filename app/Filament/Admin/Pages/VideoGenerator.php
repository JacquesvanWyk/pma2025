<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Jobs\GenerateLyricVideoJob;
use App\Models\GeneratedMedia;
use App\Models\LyricTimestamp;
use App\Models\VideoProject;
use App\Services\LyricVideoService;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property-read Schema $form
 */
class VideoGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-video-camera';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Video Generator';

    protected static ?string $title = 'Lyric Video Generator';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.admin.pages.video-generator';

    public ?array $data = [];

    public bool $isGenerating = false;

    public ?int $currentProjectId = null;

    public ?string $generationError = null;

    public string $componentId;

    public bool $showLyricsEditor = false;

    public ?string $rawLyrics = null;

    /** @var array<int, array{order: int, text: string, start_ms: int, end_ms: int, section: ?string}> */
    public array $lyricLines = [];

    public ?int $audioDurationMs = null;

    public ?string $currentAudioUrl = null;

    public function mount(): void
    {
        $this->componentId = Str::random(16);

        $this->form->fill([
            'name' => 'My Lyric Video',
            'background_type' => 'color',
            'background_color' => '#000000',
            'resolution' => '1080p',
            'aspect_ratio' => '16:9',
            'fps' => 30,
            'font_size' => 48,
            'font_color' => '#FFFFFF',
            'text_position' => 'center',
            'text_animation' => 'fade',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewProjects')
                ->label('My Projects')
                ->icon('heroicon-o-folder')
                ->color('gray')
                ->url(fn () => route('filament.admin.pages.media-library', ['type' => 'video'])),

            Action::make('viewLibrary')
                ->label('Media Library')
                ->icon('heroicon-o-photo')
                ->color('info')
                ->url(fn () => route('filament.admin.pages.media-library')),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Grid::make(2)
                        ->schema([
                            Section::make('Project Details')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Project Name')
                                        ->required()
                                        ->maxLength(255),

                                    Textarea::make('description')
                                        ->label('Description')
                                        ->rows(2),
                                ])
                                ->columnSpan(1),

                            Section::make('Audio Source')
                                ->schema([
                                    Select::make('audio_source')
                                        ->label('Audio Source')
                                        ->options([
                                            'library' => 'From Music Library',
                                            'upload' => 'Upload File',
                                            'url' => 'External URL',
                                        ])
                                        ->default('library')
                                        ->live()
                                        ->afterStateUpdated(fn () => $this->resetAudio()),

                                    Select::make('audio_media_id')
                                        ->label('Select from Library')
                                        ->options(fn () => $this->getMusicLibraryOptions())
                                        ->searchable()
                                        ->visible(fn ($get) => $get('audio_source') === 'library')
                                        ->live()
                                        ->afterStateUpdated(fn ($state) => $this->loadAudioFromLibrary($state)),

                                    FileUpload::make('audio_file')
                                        ->label('Upload Audio File')
                                        ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'])
                                        ->maxSize(51200)
                                        ->directory('temp-audio')
                                        ->visible(fn ($get) => $get('audio_source') === 'upload')
                                        ->live()
                                        ->afterStateUpdated(fn ($state) => $this->processUploadedAudio($state)),

                                    TextInput::make('audio_url')
                                        ->label('Audio URL')
                                        ->url()
                                        ->visible(fn ($get) => $get('audio_source') === 'url')
                                        ->live()
                                        ->afterStateUpdated(fn ($state) => $this->loadAudioFromUrl($state)),
                                ])
                                ->columnSpan(1),
                        ]),

                    Grid::make(3)
                        ->schema([
                            Section::make('Background')
                                ->schema([
                                    Select::make('background_type')
                                        ->label('Background Type')
                                        ->options([
                                            'color' => 'Solid Color',
                                            'image' => 'Image',
                                        ])
                                        ->default('color')
                                        ->live(),

                                    ColorPicker::make('background_color')
                                        ->label('Background Color')
                                        ->visible(fn ($get) => $get('background_type') === 'color'),

                                    FileUpload::make('background_image')
                                        ->label('Background Image')
                                        ->image()
                                        ->directory('video-backgrounds')
                                        ->visible(fn ($get) => $get('background_type') === 'image'),
                                ])
                                ->columnSpan(1),

                            Section::make('Text Style')
                                ->schema([
                                    TextInput::make('font_size')
                                        ->label('Font Size')
                                        ->numeric()
                                        ->default(48)
                                        ->minValue(12)
                                        ->maxValue(120),

                                    ColorPicker::make('font_color')
                                        ->label('Font Color')
                                        ->default('#FFFFFF'),

                                    Select::make('text_position')
                                        ->label('Text Position')
                                        ->options([
                                            'top' => 'Top',
                                            'center' => 'Center',
                                            'bottom' => 'Bottom',
                                        ])
                                        ->default('center'),

                                    Select::make('text_animation')
                                        ->label('Animation')
                                        ->options([
                                            'fade' => 'Fade In/Out',
                                            'none' => 'No Animation',
                                        ])
                                        ->default('fade'),
                                ])
                                ->columnSpan(1),

                            Section::make('Output Settings')
                                ->schema([
                                    Select::make('resolution')
                                        ->label('Resolution')
                                        ->options([
                                            '720p' => '720p (1280x720)',
                                            '1080p' => '1080p (1920x1080)',
                                            '4k' => '4K (3840x2160)',
                                        ])
                                        ->default('1080p'),

                                    Select::make('aspect_ratio')
                                        ->label('Aspect Ratio')
                                        ->options([
                                            '16:9' => '16:9 (Landscape)',
                                            '9:16' => '9:16 (Portrait)',
                                            '1:1' => '1:1 (Square)',
                                        ])
                                        ->default('16:9'),

                                    Select::make('fps')
                                        ->label('Frame Rate')
                                        ->options([
                                            24 => '24 fps',
                                            30 => '30 fps',
                                            60 => '60 fps',
                                        ])
                                        ->default(30),
                                ])
                                ->columnSpan(1),
                        ]),
                ])
                    ->livewireSubmitHandler('createProject')
                    ->key('form-actions')
                    ->footer([
                        Actions::make($this->getFormActions()),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getMusicLibraryOptions(): array
    {
        return GeneratedMedia::music()
            ->where('user_id', auth()->id())
            ->completed()
            ->latest()
            ->get()
            ->mapWithKeys(fn ($media) => [
                $media->id => ($media->title ?? 'Untitled').' - '.($media->formatted_duration ?? 'Unknown'),
            ])
            ->toArray();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('editLyrics')
                ->label('Edit Lyrics & Timestamps')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->action(fn () => $this->showLyricsEditor = true)
                ->visible(fn () => $this->audioDurationMs !== null),

            Action::make('generate')
                ->label('Generate Video')
                ->icon('heroicon-o-video-camera')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate Lyric Video?')
                ->modalDescription('This will create a video with your lyrics synced to the audio. Processing may take several minutes depending on the video length.')
                ->modalSubmitActionLabel('Generate')
                ->action(fn () => $this->generateVideo())
                ->disabled(fn () => $this->isGenerating || empty($this->lyricLines) || ! $this->audioDurationMs),

            Action::make('checkStatus')
                ->label('Check Status')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->visible(fn () => $this->isGenerating)
                ->action(fn () => $this->checkStatus()),
        ];
    }

    protected function resetAudio(): void
    {
        $this->audioDurationMs = null;
        $this->currentAudioUrl = null;
        $this->lyricLines = [];
    }

    public function loadAudioFromLibrary(?int $mediaId): void
    {
        if (! $mediaId) {
            $this->resetAudio();

            return;
        }

        $media = GeneratedMedia::find($mediaId);
        if ($media) {
            $this->currentAudioUrl = $media->file_url;
            $this->audioDurationMs = $media->duration_seconds ? $media->duration_seconds * 1000 : null;

            if ($media->lyrics) {
                $this->rawLyrics = $media->lyrics;
                $this->autoGenerateTimestamps();
            }
        }
    }

    public function processUploadedAudio(mixed $files): void
    {
        if (empty($files)) {
            $this->resetAudio();

            return;
        }

        $file = $files;

        if (is_array($files)) {
            $file = reset($files);
        }

        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $path = $file->getRealPath();
            $service = app(LyricVideoService::class);
            $this->audioDurationMs = $service->getAudioDuration($path);
            $this->currentAudioUrl = $file->temporaryUrl();
        } elseif (is_string($file)) {
            $path = Storage::disk('public')->path($file);
            $service = app(LyricVideoService::class);
            $this->audioDurationMs = $service->getAudioDuration($path);
            $this->currentAudioUrl = Storage::disk('public')->url($file);
        }
    }

    public function loadAudioFromUrl(?string $url): void
    {
        if (empty($url)) {
            $this->resetAudio();

            return;
        }

        $this->currentAudioUrl = $url;
        $this->audioDurationMs = 180000;
    }

    public function saveLyrics(): void
    {
        $this->showLyricsEditor = false;

        Notification::make()
            ->title('Lyrics saved')
            ->success()
            ->send();
    }

    public function autoGenerateTimestamps(): void
    {
        if (empty($this->rawLyrics) || ! $this->audioDurationMs) {
            return;
        }

        $service = app(LyricVideoService::class);
        $this->lyricLines = $service->generateLrcFromLyrics($this->rawLyrics, $this->audioDurationMs);

        Notification::make()
            ->title('Timestamps Generated')
            ->body('Lyrics have been automatically timestamped. You can adjust them manually.')
            ->success()
            ->send();
    }

    public function updateLyricLine(int $index, string $field, $value): void
    {
        if (isset($this->lyricLines[$index])) {
            $this->lyricLines[$index][$field] = $value;
        }
    }

    public function removeLyricLine(int $index): void
    {
        unset($this->lyricLines[$index]);
        $this->lyricLines = array_values($this->lyricLines);

        foreach ($this->lyricLines as $i => $line) {
            $this->lyricLines[$i]['order'] = $i;
        }
    }

    public function addLyricLine(): void
    {
        $lastLine = end($this->lyricLines);
        $startMs = $lastLine ? $lastLine['end_ms'] : 0;

        $this->lyricLines[] = [
            'order' => count($this->lyricLines),
            'text' => '',
            'section' => null,
            'start_ms' => $startMs,
            'end_ms' => $startMs + 3000,
        ];
    }

    public function generateVideo(): void
    {
        $data = $this->form->getState();

        if (empty($this->lyricLines)) {
            Notification::make()
                ->title('No Lyrics')
                ->danger()
                ->body('Please add lyrics before generating the video.')
                ->send();

            return;
        }

        if (! $this->audioDurationMs) {
            Notification::make()
                ->title('No Audio')
                ->danger()
                ->body('Please select an audio source before generating.')
                ->send();

            return;
        }

        $this->isGenerating = true;
        $this->generationError = null;

        Cache::forget("video-generation-{$this->componentId}-status");
        Cache::forget("video-generation-{$this->componentId}-complete");
        Cache::forget("video-generation-{$this->componentId}-error");

        try {
            $project = VideoProject::create([
                'user_id' => auth()->id(),
                'name' => $data['name'],
                'type' => 'lyric_video',
                'status' => 'draft',
                'description' => $data['description'] ?? null,
                'audio_media_id' => $data['audio_source'] === 'library' ? $data['audio_media_id'] : null,
                'audio_path' => $data['audio_source'] === 'upload' && ! empty($data['audio_file'])
                    ? (is_array($data['audio_file']) ? reset($data['audio_file']) : $data['audio_file'])
                    : null,
                'audio_url' => $data['audio_source'] === 'url' ? $data['audio_url'] : null,
                'audio_duration_ms' => $this->audioDurationMs,
                'background_type' => $data['background_type'],
                'background_value' => $data['background_type'] === 'color'
                    ? $data['background_color']
                    : (is_array($data['background_image'] ?? null) ? reset($data['background_image']) : ($data['background_image'] ?? null)),
                'text_style' => [
                    'font_size' => $data['font_size'],
                    'font_color' => $data['font_color'],
                    'position' => $data['text_position'],
                    'animation' => $data['text_animation'],
                    'shadow' => true,
                ],
                'resolution' => $data['resolution'],
                'aspect_ratio' => $data['aspect_ratio'],
                'fps' => $data['fps'],
            ]);

            $this->currentProjectId = $project->id;

            foreach ($this->lyricLines as $line) {
                LyricTimestamp::create([
                    'video_project_id' => $project->id,
                    'order' => $line['order'],
                    'text' => $line['text'],
                    'section' => $line['section'] ?? null,
                    'start_ms' => $line['start_ms'],
                    'end_ms' => $line['end_ms'],
                    'animation' => $data['text_animation'],
                ]);
            }

            GenerateLyricVideoJob::dispatch($project->id, $this->componentId);

            Notification::make()
                ->title('Video Generation Started')
                ->success()
                ->body('Your lyric video is being generated. This may take several minutes.')
                ->send();
        } catch (\Exception $e) {
            $this->isGenerating = false;
            $this->generationError = $e->getMessage();

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    public function checkStatus(): void
    {
        $complete = Cache::get("video-generation-{$this->componentId}-complete");
        if ($complete) {
            $this->isGenerating = false;

            Notification::make()
                ->title('Video Generated Successfully!')
                ->success()
                ->body('Your lyric video is ready to download.')
                ->send();

            Cache::forget("video-generation-{$this->componentId}-complete");

            return;
        }

        $error = Cache::get("video-generation-{$this->componentId}-error");
        if ($error) {
            $this->isGenerating = false;
            $this->generationError = $error;

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body($error)
                ->send();

            Cache::forget("video-generation-{$this->componentId}-error");

            return;
        }

        $status = Cache::get("video-generation-{$this->componentId}-status");
        Notification::make()
            ->title('Still Processing')
            ->info()
            ->body($status ?? 'Video generation is in progress...')
            ->send();
    }

    public function getRecentProjectsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return VideoProject::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();
    }

    public function loadProject(int $projectId): void
    {
        $project = VideoProject::with('lyricTimestamps')->find($projectId);

        if (! $project || $project->user_id !== auth()->id()) {
            return;
        }

        $this->currentProjectId = $project->id;

        $this->form->fill([
            'name' => $project->name,
            'description' => $project->description,
            'audio_source' => $project->audio_media_id ? 'library' : ($project->audio_path ? 'upload' : 'url'),
            'audio_media_id' => $project->audio_media_id,
            'audio_url' => $project->audio_url,
            'background_type' => $project->background_type,
            'background_color' => $project->background_type === 'color' ? $project->background_value : '#000000',
            'resolution' => $project->resolution,
            'aspect_ratio' => $project->aspect_ratio,
            'fps' => $project->fps,
            'font_size' => $project->text_style['font_size'] ?? 48,
            'font_color' => $project->text_style['font_color'] ?? '#FFFFFF',
            'text_position' => $project->text_style['position'] ?? 'center',
            'text_animation' => $project->text_style['animation'] ?? 'fade',
        ]);

        $this->audioDurationMs = $project->audio_duration_ms;
        $this->currentAudioUrl = $project->audio_source_url;

        $this->lyricLines = $project->lyricTimestamps->map(fn ($ts) => [
            'order' => $ts->order,
            'text' => $ts->text,
            'section' => $ts->section,
            'start_ms' => $ts->start_ms,
            'end_ms' => $ts->end_ms,
        ])->toArray();

        Notification::make()
            ->title('Project Loaded')
            ->success()
            ->send();
    }

    public function deleteProject(int $projectId): void
    {
        $project = VideoProject::where('user_id', auth()->id())->find($projectId);

        if ($project) {
            if ($project->output_path) {
                Storage::disk(config('kie.storage.disk', 'public'))->delete($project->output_path);
            }
            if ($project->thumbnail_path) {
                Storage::disk(config('kie.storage.disk', 'public'))->delete($project->thumbnail_path);
            }

            $project->delete();

            Notification::make()
                ->title('Project Deleted')
                ->success()
                ->send();
        }
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
