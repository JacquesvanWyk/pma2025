<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\GeneratedMedia;
use App\Services\KieAiService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property-read Schema $form
 */
class ImageGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Image Generator';

    protected static ?string $title = 'AI Image Generator';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.admin.pages.image-generator';

    public ?array $data = [];

    public ?string $generatedImageUrl = null;

    public ?string $generatedImagePath = null;

    public bool $isGenerating = false;

    public ?string $generationError = null;

    public ?int $kieCredits = null;

    // For async polling
    public ?string $pendingTaskId = null;

    public ?array $pendingTaskData = null;

    public int $pollAttempts = 0;

    public function mount(): void
    {
        $this->form->fill([
            'aspect_ratio' => '1:1',
            'resolution' => '1K',
            'reference_images' => [],
        ]);

        $this->loadCredits();
    }

    protected function loadCredits(): void
    {
        $service = app(KieAiService::class);
        if ($service->isConfigured()) {
            $result = $service->getCredits();
            if ($result['success']) {
                $this->kieCredits = $result['credits'];
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

            Action::make('downloadImage')
                ->label('Download Image')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn () => $this->generatedImageUrl !== null)
                ->url(fn () => $this->generatedImageUrl)
                ->openUrlInNewTab(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('NanoBanana Pro')
                        ->description('Generate high-quality images with AI. Optionally add reference images for style guidance or image editing.')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Textarea::make('prompt')
                                        ->label('Image Description')
                                        ->required()
                                        ->rows(3)
                                        ->placeholder('Describe what you want to create. Be specific about style, colors, lighting, and composition.')
                                        ->helperText('The more detailed your description, the better the result.')
                                        ->columnSpanFull(),

                                    Select::make('aspect_ratio')
                                        ->label('Aspect Ratio')
                                        ->options([
                                            '1:1' => 'Square (1:1)',
                                            '2:3' => 'Portrait (2:3)',
                                            '3:2' => 'Landscape (3:2)',
                                            '3:4' => 'Portrait (3:4)',
                                            '4:3' => 'Landscape (4:3)',
                                            '4:5' => 'Portrait (4:5)',
                                            '5:4' => 'Landscape (5:4)',
                                            '9:16' => 'Vertical/Story (9:16)',
                                            '16:9' => 'Widescreen (16:9)',
                                            '21:9' => 'Ultra-wide (21:9)',
                                            'auto' => 'Auto (AI decides)',
                                        ])
                                        ->default('1:1')
                                        ->helperText('Choose the image dimensions'),

                                    ToggleButtons::make('resolution')
                                        ->label('Resolution')
                                        ->options([
                                            '1K' => '1K',
                                            '2K' => '2K',
                                            '4K' => '4K',
                                        ])
                                        ->default('1K')
                                        ->inline()
                                        ->helperText('Higher resolution = more credits'),

                                    FileUpload::make('reference_images')
                                        ->label('Reference Images (Optional)')
                                        ->helperText('Add up to 8 images for style reference or editing. Leave empty to create from scratch.')
                                        ->multiple()
                                        ->maxFiles(8)
                                        ->image()
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio(null)
                                        ->imageResizeTargetWidth(1024)
                                        ->imageResizeTargetHeight(1024)
                                        ->directory('temp/reference-images')
                                        ->visibility('public')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                    ->livewireSubmitHandler('generateImage')
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
            Action::make('generate')
                ->label('Generate Image')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate AI Image?')
                ->modalDescription('This will use NanoBanana Pro to create an image based on your description.')
                ->modalSubmitActionLabel('Generate')
                ->action(fn () => $this->generateImage())
                ->disabled(fn () => $this->isGenerating),

            Action::make('clear')
                ->label('Clear')
                ->color('gray')
                ->visible(fn () => $this->generatedImageUrl !== null)
                ->action(function () {
                    $this->generatedImageUrl = null;
                    $this->generatedImagePath = null;
                    $this->generationError = null;
                    Notification::make()
                        ->title('Image cleared')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function generateImage(): void
    {
        $this->validate();

        $data = $this->form->getState();

        $this->isGenerating = true;
        $this->generatedImageUrl = null;
        $this->generatedImagePath = null;
        $this->generationError = null;
        $this->pendingTaskId = null;
        $this->pendingTaskData = null;
        $this->pollAttempts = 0;

        try {
            $service = app(KieAiService::class);

            if (! $service->isConfigured()) {
                throw new \Exception('KIE.ai not configured. Add KIE_API_KEY to .env');
            }

            // Get image URLs from uploaded files
            $imageUrls = [];
            if (! empty($data['reference_images'])) {
                foreach ($data['reference_images'] as $path) {
                    $imageUrls[] = Storage::disk('public')->url($path);
                }
            }

            $result = $service->generateImagePro(
                prompt: $data['prompt'],
                aspectRatio: $data['aspect_ratio'],
                resolution: $data['resolution'],
                imageUrls: $imageUrls
            );

            if (! $result['success']) {
                throw new \Exception($result['error'] ?? 'Failed to generate image');
            }

            // If immediate result (unlikely but handle it)
            if ($result['is_complete'] && ! empty($result['image_url'])) {
                $this->handleImageResult($result['image_url'], $data);
                $this->isGenerating = false;

                return;
            }

            // Store task for polling
            if (! empty($result['task_id'])) {
                $this->pendingTaskId = $result['task_id'];
                $this->pendingTaskData = $data;

                Notification::make()
                    ->title('Image Generation Started')
                    ->info()
                    ->body('Processing... This usually takes 30-60 seconds.')
                    ->send();
            }

        } catch (\Exception $e) {
            $this->isGenerating = false;
            $this->generationError = $e->getMessage();
            Notification::make()
                ->title('Image Generation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Poll for task completion - called by Livewire wire:poll
     */
    public function checkTaskStatus(): void
    {
        if (! $this->pendingTaskId || ! $this->isGenerating) {
            return;
        }

        $this->pollAttempts++;

        // Timeout after 40 attempts (2 minutes at 3 second intervals)
        if ($this->pollAttempts > 40) {
            $this->isGenerating = false;
            $this->pendingTaskId = null;
            $this->generationError = 'Image generation timed out after 2 minutes';
            Notification::make()
                ->title('Generation Timed Out')
                ->danger()
                ->body('The image took too long to generate. Please try again.')
                ->send();

            return;
        }

        try {
            $service = app(KieAiService::class);
            $result = $service->getJobTaskStatus($this->pendingTaskId);

            if (! $result['success']) {
                throw new \Exception($result['error'] ?? 'Failed to check task status');
            }

            if ($result['is_complete'] && ! empty($result['image_url'])) {
                $this->handleImageResult($result['image_url'], $this->pendingTaskData);
                $this->isGenerating = false;
                $this->pendingTaskId = null;
                $this->pendingTaskData = null;
                $this->loadCredits();

                return;
            }

            if ($result['is_failed']) {
                throw new \Exception('Image generation failed: '.$result['status']);
            }

        } catch (\Exception $e) {
            $this->isGenerating = false;
            $this->pendingTaskId = null;
            $this->generationError = $e->getMessage();
            Notification::make()
                ->title('Image Generation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function handleImageResult(string $imageUrl, array $data): void
    {
        // Download and store locally
        $imageContents = file_get_contents($imageUrl);
        $filename = 'image-'.Str::random(10).'.png';
        $path = config('kie.storage.paths.images', 'generated/images').'/'.$filename;

        Storage::disk('public')->put($path, $imageContents);

        $this->generatedImagePath = $path;
        $this->generatedImageUrl = Storage::disk('public')->url($path);

        // Save to database
        GeneratedMedia::create([
            'user_id' => auth()->id(),
            'type' => 'image',
            'provider' => 'kie',
            'model' => 'nanobanana_pro',
            'status' => 'completed',
            'prompt' => $data['prompt'],
            'settings' => [
                'aspect_ratio' => $data['aspect_ratio'],
                'resolution' => $data['resolution'],
                'has_reference_images' => ! empty($data['reference_images']),
            ],
            'file_path' => $path,
            'remote_url' => $imageUrl,
            'cost_usd' => 0.02,
        ]);

        // Clean up reference images
        if (! empty($data['reference_images'])) {
            foreach ($data['reference_images'] as $refPath) {
                Storage::disk('public')->delete($refPath);
            }
        }

        Notification::make()
            ->title('Image Generated Successfully!')
            ->success()
            ->body('Your image is ready to view and download.')
            ->send();
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
