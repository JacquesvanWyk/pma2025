<?php

namespace App\Filament\Admin\Pages;

use App\Models\Sermon;
use App\Services\SlideGenerationService;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;

/**
 * @property-read Schema $form
 */
class SlideGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationLabel = 'Slide Generator';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Generate Slides';

    protected string $view = 'filament.admin.pages.slide-generator';

    public ?array $data = [];

    public array $generatedSlides = [];

    public bool $isGenerating = false;

    public ?string $currentStatus = null;

    public int $currentSlideNumber = 0;

    public int $totalSlides = 0;

    public ?string $jobId = null;

    public ?int $sermonId = null;

    public array $slideViewModes = [];

    public string $aiPrompt = '';

    public function mount(): void
    {
        $sermonId = request()->query('sermon');

        $this->form->fill([
            'mode' => $sermonId ? 'existing' : 'new',
            'sermon_id' => $sermonId,
            'theme' => config('slides.default_theme'),
            'slide_count' => config('slides.ai.default_slide_count'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Radio::make('mode')
                        ->label('Slide Source')
                        ->options([
                            'existing' => 'From Existing Sermon',
                            'new' => 'Create New Content',
                        ])
                        ->default('existing')
                        ->inline()
                        ->live()
                        ->required(),

                    Select::make('sermon_id')
                        ->label('Select Sermon')
                        ->options(Sermon::query()->pluck('title', 'id'))
                        ->searchable()
                        ->required(fn ($get) => $get('mode') === 'existing')
                        ->visible(fn ($get) => $get('mode') === 'existing')
                        ->helperText('Choose an existing sermon to generate slides from'),

                    TextInput::make('title')
                        ->label('Presentation Title')
                        ->required(fn ($get) => $get('mode') === 'new')
                        ->visible(fn ($get) => $get('mode') === 'new')
                        ->maxLength(255),

                    Textarea::make('content')
                        ->label('Content')
                        ->required(fn ($get) => $get('mode') === 'new')
                        ->visible(fn ($get) => $get('mode') === 'new')
                        ->rows(8)
                        ->helperText('Enter the main content for your presentation'),

                    TextInput::make('scripture')
                        ->label('Primary Scripture (Optional)')
                        ->visible(fn ($get) => $get('mode') === 'new')
                        ->maxLength(255)
                        ->placeholder('e.g., John 3:16'),

                    Select::make('theme')
                        ->label('Slide Theme')
                        ->required()
                        ->options(collect(config('slides.themes'))->mapWithKeys(fn ($theme, $key) => [$key => $theme['name']]))
                        ->default(config('slides.default_theme'))
                        ->helperText('Choose the visual theme for your slides'),

                    TextInput::make('slide_count')
                        ->label('Number of Slides')
                        ->required()
                        ->numeric()
                        ->minValue(config('slides.ai.min_slides'))
                        ->maxValue(config('slides.ai.max_slides'))
                        ->default(config('slides.ai.default_slide_count'))
                        ->helperText('AI will suggest this many slides'),

                    Textarea::make('additional_instructions')
                        ->label('Additional Instructions (Optional)')
                        ->rows(3)
                        ->placeholder('Any specific requirements for the slides...')
                        ->columnSpanFull(),
                ])
                    ->key('form-actions')
                    ->footer([
                        Actions::make($this->getFormActions()),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('generateSlides')
                ->label('Generate Slides')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->disabled(fn () => $this->isGenerating)
                ->action(function () {
                    // Dispatch immediately to trigger UI update
                    $this->dispatch('generation-started');
                    $this->generateSlides();
                }),

            Action::make('reset')
                ->label('Reset')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->visible(fn () => ! $this->isGenerating && count($this->generatedSlides) > 0)
                ->action(function () {
                    $this->reset(['generatedSlides', 'currentStatus', 'currentSlideNumber', 'totalSlides', 'jobId']);
                }),
        ];
    }

    public function generateSlides(): void
    {
        $this->validate();

        $data = $this->form->getState();

        // Set these immediately so UI updates
        $this->isGenerating = true;
        $this->generatedSlides = [];
        $this->currentSlideNumber = 0;
        $this->totalSlides = (int) $data['slide_count'];
        $this->currentStatus = 'Preparing to generate slides...';
        $this->jobId = Str::uuid()->toString();

        try {
            $sermon = null;
            if ($data['mode'] === 'existing') {
                $sermon = Sermon::find($data['sermon_id']);
                if (! $sermon) {
                    throw new \Exception('Sermon not found');
                }
                $this->sermonId = $sermon->id;
            } else {
                $this->sermonId = null;
            }

            // Dispatch job with raw form data - ALL AI work happens in the job
            $job = new \App\Jobs\GenerateSlidesJob(
                sermon: $sermon,
                formData: $data,
                jobId: $this->jobId
            );

            dispatch($job);

            $this->currentStatus = 'AI is creating your slide plan...';

            Notification::make()
                ->title('Generation Started')
                ->info()
                ->body('Your slides are being generated. They will appear below as they are created.')
                ->send();
        } catch (\Exception $e) {
            $this->isGenerating = false;
            $this->currentStatus = null;

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function checkGenerationProgress(): void
    {
        if (! $this->jobId || ! $this->isGenerating) {
            return;
        }

        $service = new SlideGenerationService;
        $progress = $service->getProgress($this->jobId);

        $this->currentStatus = $progress['message'];
        $this->currentSlideNumber = $progress['current_slide'];
        $this->generatedSlides = $progress['slides'];

        if ($progress['status'] === 'completed') {
            $this->isGenerating = false;
            $this->currentStatus = 'All slides generated successfully!';

            Notification::make()
                ->title('Generation Complete')
                ->success()
                ->body("Successfully generated {$this->currentSlideNumber} slides.")
                ->send();

            // Clear the job ID to stop polling
            $service->clearProgress($this->jobId);
            $this->jobId = null;
        } elseif ($progress['status'] === 'failed') {
            $this->isGenerating = false;

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body($progress['message'])
                ->send();

            // Clear the job ID to stop polling
            $service->clearProgress($this->jobId);
            $this->jobId = null;
        }
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (count($this->generatedSlides) > 0 && ! $this->isGenerating && $this->sermonId) {
            $actions[] = Action::make('editSlides')
                ->label('Edit Slides')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->url(fn () => route('filament.admin.resources.sermons.sermons.slide-editor', ['record' => $this->sermonId]));
        }

        return $actions;
    }

    public function exportAsPowerPoint(): void
    {
        if (empty($this->generatedSlides)) {
            Notification::make()
                ->title('No Slides to Export')
                ->warning()
                ->body('Please generate slides first.')
                ->send();

            return;
        }

        try {
            // Store slides in cache for the download route
            $cacheKey = 'slide_export_'.uniqid();
            \Cache::put($cacheKey, $this->generatedSlides, now()->addMinutes(5));

            // Redirect to download route
            $this->redirect(route('slides.export.powerpoint', [
                'cacheKey' => $cacheKey,
                'sermonId' => $this->sermonId,
            ]));
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function exportAsPdf(): void
    {
        if (empty($this->generatedSlides)) {
            Notification::make()
                ->title('No Slides to Export')
                ->warning()
                ->body('Please generate slides first.')
                ->send();

            return;
        }

        try {
            // Store slides in cache for the download route
            $cacheKey = 'slide_export_'.uniqid();
            \Cache::put($cacheKey, $this->generatedSlides, now()->addMinutes(5));

            // Redirect to download route
            $this->redirect(route('slides.export.pdf', [
                'cacheKey' => $cacheKey,
                'sermonId' => $this->sermonId,
            ]));
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function applyAiEdit(): void
    {
        if (empty($this->aiPrompt)) {
            Notification::make()
                ->title('Prompt Required')
                ->warning()
                ->body('Please enter what you would like to change.')
                ->send();

            return;
        }

        // TODO: Implement AI editing logic
        Notification::make()
            ->title('AI Editing')
            ->info()
            ->body('This feature is coming soon!')
            ->send();
    }

    public function toggleSlideView(int $index): void
    {
        $this->slideViewModes[$index] = ($this->slideViewModes[$index] ?? 'image') === 'image' ? 'html' : 'image';
    }

    public function resetToForm(): void
    {
        $this->reset(['generatedSlides', 'currentStatus', 'currentSlideNumber', 'totalSlides', 'jobId', 'aiPrompt', 'slideViewModes']);
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
