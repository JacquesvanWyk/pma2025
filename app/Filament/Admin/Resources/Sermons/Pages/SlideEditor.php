<?php

namespace App\Filament\Admin\Resources\Sermons\Pages;

use App\Filament\Admin\Resources\Sermons\SermonResource;
use App\Models\Sermon;
use App\Models\SermonSlide;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Cache;

class SlideEditor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SermonResource::class;

    protected static ?string $title = 'Slide Editor';

    protected string $view = 'filament.admin.pages.slide-editor';

    public Sermon $sermon;

    public ?int $activeSlideId = null;

    public ?string $aiPrompt = null;

    public bool $isProcessingAi = false;

    public function mount(Sermon $sermon): void
    {
        $this->sermon = $sermon->load('slides');

        if ($this->sermon->slides->isNotEmpty()) {
            $this->activeSlideId = $this->sermon->slides->first()->id;
        }

        $this->checkGenerationStatus();
    }

    public function checkGenerationStatus(): void
    {
        $cacheKey = "sermon_slides_generation_{$this->sermon->id}";
        $status = Cache::get("{$cacheKey}_status");

        if ($status === 'generating') {
            $progress = Cache::get("{$cacheKey}_progress");

            if ($progress) {
                Notification::make()
                    ->title('Generation in Progress')
                    ->info()
                    ->body($progress['message'] ?? 'Generating slides...')
                    ->send();
            }
        }
    }

    public function selectSlide(int $slideId): void
    {
        $this->activeSlideId = $slideId;
    }

    public function deleteSlide(int $slideId): void
    {
        $slide = SermonSlide::find($slideId);

        if (! $slide || $slide->sermon_id !== $this->sermon->id) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Slide not found.')
                ->send();

            return;
        }

        $slide->delete();

        // Reorder remaining slides
        $this->sermon->slides()->each(function ($slide, $index) {
            $slide->update(['slide_number' => $index + 1]);
        });

        if ($this->activeSlideId === $slideId) {
            $this->activeSlideId = $this->sermon->slides()->first()?->id;
        }

        $this->sermon->refresh();

        Notification::make()
            ->title('Slide Deleted')
            ->success()
            ->send();
    }

    public function duplicateSlide(int $slideId): void
    {
        $slide = SermonSlide::find($slideId);

        if (! $slide || $slide->sermon_id !== $this->sermon->id) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Slide not found.')
                ->send();

            return;
        }

        $duplicated = $slide->duplicate();
        $this->sermon->refresh();
        $this->activeSlideId = $duplicated->id;

        Notification::make()
            ->title('Slide Duplicated')
            ->success()
            ->send();
    }

    public function moveSlideUp(int $slideId): void
    {
        $slide = SermonSlide::find($slideId);

        if (! $slide || $slide->sermon_id !== $this->sermon->id) {
            return;
        }

        if ($slide->moveUp()) {
            $this->sermon->refresh();

            Notification::make()
                ->title('Slide Moved')
                ->success()
                ->send();
        }
    }

    public function moveSlideDown(int $slideId): void
    {
        $slide = SermonSlide::find($slideId);

        if (! $slide || $slide->sermon_id !== $this->sermon->id) {
            return;
        }

        if ($slide->moveDown()) {
            $this->sermon->refresh();

            Notification::make()
                ->title('Slide Moved')
                ->success()
                ->send();
        }
    }

    public function reorderSlides(array $order): void
    {
        foreach ($order as $index => $slideId) {
            SermonSlide::where('id', $slideId)
                ->where('sermon_id', $this->sermon->id)
                ->update(['slide_number' => $index + 1]);
        }

        $this->sermon->refresh();

        Notification::make()
            ->title('Slides Reordered')
            ->success()
            ->send();
    }

    public function processAiEdit(): void
    {
        if (! $this->aiPrompt) {
            Notification::make()
                ->title('No Prompt Provided')
                ->warning()
                ->body('Please enter an AI prompt to edit the slides.')
                ->send();

            return;
        }

        $this->isProcessingAi = true;

        try {
            $slide = SermonSlide::find($this->activeSlideId);

            if (! $slide) {
                throw new \Exception('Active slide not found.');
            }

            // Dispatch AI editing job
            dispatch(new \App\Jobs\ProcessSlideAiEditJob(
                slide: $slide,
                prompt: $this->aiPrompt
            ));

            $this->aiPrompt = null;

            Notification::make()
                ->title('Processing AI Edit')
                ->info()
                ->body('Your AI edit is being processed. This may take a moment.')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('AI Edit Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        } finally {
            $this->isProcessingAi = false;
        }
    }

    public function addNewSlide(): void
    {
        $maxSlideNumber = $this->sermon->slides()->max('slide_number') ?? 0;

        $slide = SermonSlide::create([
            'sermon_id' => $this->sermon->id,
            'slide_number' => $maxSlideNumber + 1,
            'slide_type' => 'content',
            'html_content' => '<h2>New Slide</h2><p>Click to edit this slide.</p>',
            'css_styles' => '',
            'background_type' => 'gradient',
            'background_value' => config('slides.themes.'.config('slides.default_theme').'.background'),
            'metadata' => [
                'theme' => config('slides.default_theme'),
            ],
        ]);

        $this->sermon->refresh();
        $this->activeSlideId = $slide->id;

        Notification::make()
            ->title('Slide Added')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addSlide')
                ->label('Add Slide')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->action(fn () => $this->addNewSlide()),

            Action::make('exportPowerpoint')
                ->label('Export PowerPoint')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    dispatch(new \App\Jobs\ExportSlidesToPowerpointJob($this->sermon));

                    Notification::make()
                        ->title('Exporting to PowerPoint')
                        ->info()
                        ->body('Your presentation is being generated. You will receive a download link shortly.')
                        ->send();
                }),

            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    dispatch(new \App\Jobs\ExportSlidesToPdfJob($this->sermon));

                    Notification::make()
                        ->title('Exporting to PDF')
                        ->info()
                        ->body('Your PDF is being generated. You will receive a download link shortly.')
                        ->send();
                }),
        ];
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
