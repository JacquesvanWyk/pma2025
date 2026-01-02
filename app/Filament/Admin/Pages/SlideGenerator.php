<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Jobs\GenerateKimiSlidesJob;
use App\Models\SlidePresentation;
use App\Services\SlideOutlineService;
use Filament\Actions\Action;
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
use Illuminate\Support\Facades\Auth;

class SlideGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationLabel = 'Slide Generator';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Kimi-Style Slide Generator';

    protected string $view = 'filament.admin.pages.slide-generator';

    public ?array $data = [];

    public string $step = 'input';

    public ?SlidePresentation $presentation = null;

    public ?array $outline = null;

    public bool $isGeneratingOutline = false;

    public bool $isGeneratingImages = false;

    public ?string $currentStatus = null;

    public function mount(): void
    {
        $presentationId = request()->query('presentation');

        if ($presentationId) {
            $this->presentation = SlidePresentation::find($presentationId);
            if ($this->presentation) {
                $this->loadPresentationState();

                return;
            }
        }

        $this->form->fill([]);
    }

    protected function loadPresentationState(): void
    {
        if (! $this->presentation) {
            return;
        }

        $this->outline = $this->presentation->outline;

        if ($this->presentation->status === 'complete') {
            $this->step = 'complete';
        } elseif ($this->presentation->status === 'generating') {
            $this->step = 'generating';
            $this->isGeneratingImages = true;
        } elseif ($this->presentation->status === 'outline_ready') {
            $this->step = 'outline';
        } else {
            $this->step = 'input';
            $this->form->fill([
                'title' => $this->presentation->title,
                'source_text' => $this->presentation->source_text,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('title')
                        ->label('Presentation Title')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., The Holy Spirit: Not a Third Person'),

                    Textarea::make('source_text')
                        ->label('Source Content')
                        ->required()
                        ->rows(12)
                        ->placeholder('Paste your sermon notes, study material, or any text you want to turn into slides...')
                        ->helperText('The AI will analyze this content and create illustrated slides with text baked into the images. Visual style will be chosen automatically based on content.'),
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
            Action::make('generateOutline')
                ->label('Generate Outline')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->disabled(fn () => $this->isGeneratingOutline)
                ->action(fn () => $this->generateOutline()),
        ];
    }

    public function generateOutline(): void
    {
        $this->validate();

        $data = $this->form->getState();

        $this->isGeneratingOutline = true;
        $this->currentStatus = 'AI is analyzing your content and creating slide outline...';

        try {
            $this->presentation = SlidePresentation::create([
                'user_id' => Auth::id(),
                'title' => $data['title'],
                'source_text' => $data['source_text'],
                'style' => 'auto',
                'status' => 'draft',
            ]);

            $outlineService = new SlideOutlineService;
            $outline = $outlineService->generateOutline($data['source_text']);

            $this->presentation->update([
                'title' => $outline['title'] ?? $data['title'],
                'outline' => $outline,
                'status' => 'outline_ready',
                'total_slides' => count($outline['slides'] ?? []),
            ]);

            $this->outline = $outline;
            $this->step = 'outline';
            $this->isGeneratingOutline = false;
            $this->currentStatus = null;

            Notification::make()
                ->title('Outline Generated')
                ->success()
                ->body('Review the outline below and click "Generate Slides" when ready.')
                ->send();

        } catch (\Exception $e) {
            $this->isGeneratingOutline = false;
            $this->currentStatus = null;

            if ($this->presentation) {
                $this->presentation->update(['status' => 'failed']);
            }

            Notification::make()
                ->title('Outline Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function approveAndGenerate(): void
    {
        if (! $this->presentation || ! $this->outline) {
            return;
        }

        $this->step = 'generating';
        $this->isGeneratingImages = true;
        $this->currentStatus = 'Starting image generation...';

        $this->presentation->update([
            'status' => 'generating',
            'current_slide' => 0,
        ]);

        dispatch(new GenerateKimiSlidesJob($this->presentation->id));

        Notification::make()
            ->title('Image Generation Started')
            ->info()
            ->body('Your slides are being generated. This may take a few minutes.')
            ->send();
    }

    public function checkProgress(): void
    {
        if (! $this->presentation || $this->step !== 'generating') {
            return;
        }

        $this->presentation->refresh();

        $progress = $this->presentation->getProgressPercentage();
        $current = $this->presentation->current_slide;
        $total = $this->presentation->total_slides;

        $this->currentStatus = "Generating slide {$current} of {$total}...";

        if ($this->presentation->status === 'complete') {
            $this->step = 'complete';
            $this->isGeneratingImages = false;
            $this->currentStatus = null;

            Notification::make()
                ->title('Slides Generated!')
                ->success()
                ->body('Your presentation is ready. Download or view your slides below.')
                ->send();
        } elseif ($this->presentation->status === 'failed') {
            $this->isGeneratingImages = false;
            $this->currentStatus = 'Generation failed. Please try again.';

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body('Something went wrong. Please try again.')
                ->send();
        }
    }

    public function editOutline(): void
    {
        $this->step = 'outline';
    }

    public function backToInput(): void
    {
        $this->step = 'input';
        $this->outline = null;

        if ($this->presentation) {
            $this->form->fill([
                'title' => $this->presentation->title,
                'source_text' => $this->presentation->source_text,
            ]);
        }
    }

    public function startNew(): void
    {
        $this->presentation = null;
        $this->outline = null;
        $this->step = 'input';
        $this->currentStatus = null;
        $this->isGeneratingOutline = false;
        $this->isGeneratingImages = false;

        $this->form->fill([]);
    }

    public function downloadSlide(int $index): void
    {
        if (! $this->presentation || ! $this->presentation->slides) {
            return;
        }

        $slides = $this->presentation->slides;

        if (! isset($slides[$index]) || ! $slides[$index]['image_path']) {
            Notification::make()
                ->title('Download Failed')
                ->warning()
                ->body('This slide image is not available.')
                ->send();

            return;
        }

        $path = $slides[$index]['image_path'];
        $this->redirect(asset('storage/'.$path));
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
