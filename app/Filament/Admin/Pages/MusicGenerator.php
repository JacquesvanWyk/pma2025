<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Jobs\PollKieMusicTaskJob;
use App\Models\GeneratedMedia;
use App\Models\MusicStylePreset;
use App\Services\KieAiService;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
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
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Prism\Prism\Prism;

/**
 * @property-read Schema $form
 */
class MusicGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-musical-note';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Music Generator';

    protected static ?string $title = 'AI Music Generator';

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.admin.pages.music-generator';

    public ?array $data = [];

    public bool $isGenerating = false;

    public ?string $taskId = null;

    public ?string $taskStatus = null;

    public ?int $currentGenerationId = null;

    public ?array $generatedTracks = null;

    public ?string $generationError = null;

    public ?int $credits = null;

    public string $componentId;

    public bool $showLyricsModal = false;

    public bool $showStyleModal = false;

    public ?string $lyricsTheme = null;

    public ?string $generatedLyrics = null;

    public ?string $editedLyrics = null;

    public bool $isGeneratingLyrics = false;

    public ?string $newStyleName = null;

    public ?string $newStyleDescription = null;

    public ?string $newStyleGenre = null;

    public ?string $newStyleMood = null;

    public ?string $newStyleInstruments = null;

    public ?string $newStyleTempo = null;

    public function mount(): void
    {
        $this->componentId = Str::random(16);

        $this->form->fill([
            'model' => config('kie.defaults.music_model', 'V4_5'),
            'style_preset_id' => null,
            'instrumental' => false,
            'custom_mode' => true,
        ]);

        $this->loadCredits();
    }

    protected function loadCredits(): void
    {
        $service = app(KieAiService::class);
        if ($service->isConfigured()) {
            $result = $service->getCredits();
            if ($result['success']) {
                $this->credits = $result['credits'];
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshCredits')
                ->label('Refresh Credits')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->loadCredits()),

            Action::make('viewLibrary')
                ->label('Music Library')
                ->icon('heroicon-o-folder')
                ->color('info')
                ->url(fn () => route('filament.admin.pages.media-library', ['type' => 'music'])),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Grid::make(2)
                        ->schema([
                            Select::make('model')
                                ->label('AI Model')
                                ->options(collect(config('kie.music.models'))->mapWithKeys(
                                    fn ($model, $key) => [$key => $model['name'].' - '.$model['description']]
                                ))
                                ->default('V4_5')
                                ->helperText('V4.5+ models support up to 8 minutes'),

                            Select::make('style_preset_id')
                                ->label('Style Preset')
                                ->options(fn () => $this->getStylePresetOptions())
                                ->placeholder('Select a style or create new...')
                                ->helperText('Choose a saved style or create your own')
                                ->live()
                                ->afterStateUpdated(function ($state) {
                                    if ($state === 'new') {
                                        $this->showStyleModal = true;
                                        $this->data['style_preset_id'] = null;
                                    }
                                }),

                            TextInput::make('title')
                                ->label('Song Title')
                                ->maxLength(80)
                                ->placeholder('My Worship Song')
                                ->helperText('Max 80 characters')
                                ->columnSpanFull(),

                            Textarea::make('prompt')
                                ->label('Music Description')
                                ->required()
                                ->rows(3)
                                ->placeholder("Describe the music you want to create...\n\nExample: A peaceful worship song about God's grace")
                                ->helperText('Describe the mood, theme, and feel')
                                ->columnSpanFull(),

                            Checkbox::make('instrumental')
                                ->label('Instrumental Only')
                                ->helperText('Generate music without vocals'),

                            Checkbox::make('custom_mode')
                                ->label('Use Custom Lyrics')
                                ->helperText('Add your own lyrics')
                                ->default(true)
                                ->live(),
                        ]),
                ])
                    ->livewireSubmitHandler('generateMusic')
                    ->key('form-actions')
                    ->footer([
                        Actions::make($this->getFormActions()),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getStylePresetOptions(): array
    {
        $presets = MusicStylePreset::forUser(auth()->id())
            ->orderBy('usage_count', 'desc')
            ->get()
            ->mapWithKeys(fn ($preset) => [$preset->id => $preset->name])
            ->toArray();

        return $presets + ['new' => '+ Create New Style...'];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('generateLyrics')
                ->label('Generate Lyrics with AI')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->visible(fn ($get) => $get('custom_mode') && ! $get('instrumental'))
                ->action(fn () => $this->showLyricsModal = true),

            Action::make('editLyrics')
                ->label('Edit Lyrics')
                ->icon('heroicon-o-pencil')
                ->color('gray')
                ->visible(fn () => ! empty($this->editedLyrics))
                ->action(fn () => $this->showLyricsModal = true),

            Action::make('generate')
                ->label('Generate Music')
                ->icon('heroicon-o-musical-note')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate AI Music?')
                ->modalDescription('This will use KIE.ai Suno API to generate music. Generation typically takes 30-120 seconds.')
                ->modalSubmitActionLabel('Generate')
                ->action(fn () => $this->generateMusic())
                ->disabled(fn () => $this->isGenerating),

            Action::make('clear')
                ->label('Clear')
                ->color('gray')
                ->visible(fn () => $this->generatedTracks !== null || $this->generationError !== null)
                ->action(function () {
                    $this->generatedTracks = null;
                    $this->generationError = null;
                    $this->taskId = null;
                    $this->taskStatus = null;
                    $this->currentGenerationId = null;
                    $this->editedLyrics = null;
                    $this->generatedLyrics = null;
                    Notification::make()
                        ->title('Cleared')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function generateLyricsWithAi(): void
    {
        if (empty($this->lyricsTheme)) {
            Notification::make()
                ->title('Please enter a theme')
                ->warning()
                ->send();

            return;
        }

        $this->isGeneratingLyrics = true;

        try {
            $data = $this->form->getState();
            $stylePreset = null;

            if (! empty($data['style_preset_id'])) {
                $stylePreset = MusicStylePreset::find($data['style_preset_id']);
            }

            $styleContext = $stylePreset ? $stylePreset->full_style : 'worship, spiritual, reverent';

            $prompt = "Write song lyrics for a Christian worship song with the following theme: {$this->lyricsTheme}

Style: {$styleContext}

Requirements:
- Structure with [Verse 1], [Chorus], [Verse 2], [Bridge] sections
- Keep it reverent and biblically accurate
- Make it suitable for congregational singing
- Include meaningful, heartfelt lyrics about the theme
- Aim for 3-4 minutes of singing content

Write only the lyrics, no explanations.";

            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withClientOptions(['timeout' => 60])
                ->asText();

            $this->generatedLyrics = $response->text;
            $this->editedLyrics = $response->text;

            Notification::make()
                ->title('Lyrics Generated!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to generate lyrics')
                ->danger()
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isGeneratingLyrics = false;
        }
    }

    public function saveLyrics(): void
    {
        $this->showLyricsModal = false;

        Notification::make()
            ->title('Lyrics saved')
            ->success()
            ->send();
    }

    public function saveStylePreset(): void
    {
        if (empty($this->newStyleName) || empty($this->newStyleDescription)) {
            Notification::make()
                ->title('Name and description are required')
                ->warning()
                ->send();

            return;
        }

        $preset = MusicStylePreset::create([
            'user_id' => auth()->id(),
            'name' => $this->newStyleName,
            'style_description' => $this->newStyleDescription,
            'genre' => $this->newStyleGenre,
            'mood' => $this->newStyleMood,
            'instruments' => $this->newStyleInstruments,
            'tempo' => $this->newStyleTempo,
        ]);

        $this->data['style_preset_id'] = $preset->id;
        $this->showStyleModal = false;

        $this->newStyleName = null;
        $this->newStyleDescription = null;
        $this->newStyleGenre = null;
        $this->newStyleMood = null;
        $this->newStyleInstruments = null;
        $this->newStyleTempo = null;

        Notification::make()
            ->title('Style preset saved!')
            ->success()
            ->send();
    }

    public function generateMusic(): void
    {
        $this->validate();

        $data = $this->form->getState();

        $service = app(KieAiService::class);

        if (! $service->isConfigured()) {
            Notification::make()
                ->title('KIE.ai Not Configured')
                ->danger()
                ->body('Please add KIE_API_KEY to your .env file')
                ->send();

            return;
        }

        Cache::forget("music-generation-{$this->componentId}-status");
        Cache::forget("music-generation-{$this->componentId}-complete");
        Cache::forget("music-generation-{$this->componentId}-error");

        $this->isGenerating = true;
        $this->generatedTracks = null;
        $this->generationError = null;

        try {
            $stylePreset = null;
            $styleDescription = '';

            if (! empty($data['style_preset_id'])) {
                $stylePreset = MusicStylePreset::find($data['style_preset_id']);
                if ($stylePreset) {
                    $styleDescription = $stylePreset->full_style;
                    $stylePreset->incrementUsage();
                }
            }

            if (empty($styleDescription)) {
                $styleDescription = config('kie.music.styles.worship', 'Worship, spiritual, reverent');
            }

            $fullPrompt = $data['prompt'].'. Style: '.$styleDescription;

            $lyrics = $this->editedLyrics;
            $customMode = $data['custom_mode'] && ! $data['instrumental'] && ! empty($lyrics);

            $media = GeneratedMedia::create([
                'user_id' => auth()->id(),
                'type' => 'music',
                'provider' => 'kie',
                'model' => $data['model'],
                'status' => 'pending',
                'prompt' => $data['prompt'],
                'title' => $data['title'] ?? null,
                'lyrics' => $lyrics,
                'style_preset_id' => $stylePreset?->id,
                'settings' => [
                    'style' => $styleDescription,
                    'instrumental' => $data['instrumental'],
                    'custom_mode' => $customMode,
                ],
                'expires_at' => now()->addDays(config('kie.storage.remote_expiry_days', 14)),
            ]);

            $this->currentGenerationId = $media->id;

            $result = $service->generateMusic(
                prompt: $customMode ? $lyrics : $fullPrompt,
                model: $data['model'],
                instrumental: $data['instrumental'] ?? false,
                customMode: $customMode,
                style: $styleDescription,
                title: $data['title'] ?? null,
                lyrics: $customMode ? $lyrics : null
            );

            if ($result['success']) {
                $this->taskId = $result['task_id'];
                $this->taskStatus = 'PENDING';

                $media->update([
                    'task_id' => $this->taskId,
                    'status' => 'processing',
                ]);

                PollKieMusicTaskJob::dispatch($media->id, $this->taskId, $this->componentId);

                Notification::make()
                    ->title('Generation Started')
                    ->success()
                    ->body('Your music is being generated. Click "Check Status" to see progress.')
                    ->send();
            } else {
                throw new \Exception($result['error'] ?? 'Failed to start music generation');
            }
        } catch (\Exception $e) {
            $this->generationError = $e->getMessage();
            $this->isGenerating = false;

            if (isset($media)) {
                $media->update([
                    'status' => 'failed',
                    'metadata' => ['error' => $e->getMessage()],
                ]);
            }

            Log::error('Music generation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            Notification::make()
                ->title('Music Generation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    public function checkStatus(): void
    {
        $complete = Cache::get("music-generation-{$this->componentId}-complete");
        if ($complete) {
            $this->generatedTracks = $complete['tracks'] ?? [];
            $this->isGenerating = false;
            $this->taskStatus = 'SUCCESS';
            $this->loadCredits();

            if ($this->currentGenerationId) {
                $media = GeneratedMedia::find($this->currentGenerationId);
                if ($media) {
                    $this->generatedTracks = $media->metadata['tracks'] ?? $this->generatedTracks;
                }
            }

            Notification::make()
                ->title('Music Generated Successfully!')
                ->success()
                ->body('Your music is ready to play and download.')
                ->send();

            Cache::forget("music-generation-{$this->componentId}-complete");

            return;
        }

        $error = Cache::get("music-generation-{$this->componentId}-error");
        if ($error) {
            $this->generationError = $error;
            $this->isGenerating = false;
            $this->taskStatus = 'FAILED';

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body($error)
                ->send();

            Cache::forget("music-generation-{$this->componentId}-error");

            return;
        }

        $status = Cache::get("music-generation-{$this->componentId}-status");
        if ($status) {
            Notification::make()
                ->title('Status Update')
                ->info()
                ->body($status)
                ->send();
        } else {
            Notification::make()
                ->title('Still Processing')
                ->info()
                ->body('Music generation is still in progress...')
                ->send();
        }
    }

    public function getRecentMusicProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return GeneratedMedia::music()
            ->where('user_id', auth()->id())
            ->completed()
            ->latest()
            ->take(10)
            ->get();
    }

    public function playTrack(int $mediaId): void
    {
        $this->currentGenerationId = $mediaId;
        $media = GeneratedMedia::find($mediaId);

        if ($media) {
            $this->generatedTracks = $media->metadata['tracks'] ?? [];
            $this->editedLyrics = $media->lyrics;
        }
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
