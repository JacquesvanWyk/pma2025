<?php

namespace App\Filament\Admin\Pages;

use App\Models\GeneratedMedia;
use App\Services\ApiUsageTracker;
use App\Services\KieAiService;
use Filament\Actions\Action;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Prism;

/**
 * @property-read Schema $form
 */
class ImageGenerator extends Page implements HasForms
{
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

    public function mount(): void
    {
        $this->form->fill([
            'provider' => 'kie_seedream',
            'style' => 'photorealistic',
            'size' => '1024x1024',
            'quality' => 'high',
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
                    Grid::make(2)
                        ->schema([
                            Select::make('provider')
                                ->label('AI Provider')
                                ->options([
                                    'kie_seedream' => 'KIE Seedream 4.0 ($0.0175/image) - Cheapest',
                                    'kie_nanobanana' => 'KIE NanoBanana ($0.02/image)',
                                    'gemini' => 'Google Gemini Imagen 3 ($0.03/image)',
                                ])
                                ->default('kie_seedream')
                                ->helperText('Choose the AI provider for image generation')
                                ->live(),

                            Select::make('size')
                                ->label('Image Size')
                                ->options([
                                    '1024x1024' => 'Square (1024×1024)',
                                    '1024x1792' => 'Portrait (1024×1792)',
                                    '1792x1024' => 'Landscape (1792×1024)',
                                ])
                                ->default('1024x1024')
                                ->helperText('Choose the aspect ratio'),

                            TextInput::make('prompt')
                                ->label('Image Description')
                                ->required()
                                ->placeholder('e.g., A peaceful sunrise over mountains with a cross in the foreground')
                                ->helperText('Describe the main subject and scene in detail')
                                ->columnSpanFull(),

                            Select::make('style')
                                ->label('Art Style')
                                ->options([
                                    'photorealistic' => 'Photorealistic - High Quality Photo',
                                    'digital-art' => 'Digital Art - Modern & Clean',
                                    'artistic' => 'Artistic - Painterly Style',
                                    'illustration' => 'Illustration - Clean & Simple',
                                    'watercolor' => 'Watercolor Painting',
                                    'oil-painting' => 'Oil Painting',
                                    'biblical' => 'Biblical - Reverent & Educational',
                                ])
                                ->default('photorealistic')
                                ->helperText('Choose the artistic style'),

                            Select::make('quality')
                                ->label('Quality Level')
                                ->options([
                                    'standard' => 'Standard',
                                    'high' => 'High Quality',
                                    'ultra' => 'Ultra HD',
                                ])
                                ->default('high')
                                ->helperText('Higher quality for better results'),

                            Textarea::make('details')
                                ->label('Additional Details (Optional)')
                                ->rows(2)
                                ->placeholder('Lighting: soft golden hour light, Colors: warm oranges')
                                ->helperText('Add specific details about lighting, colors, mood')
                                ->columnSpanFull(),
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
                ->modalDescription('This will use AI to create an image based on your description.')
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

        try {
            Notification::make()
                ->title('Starting Image Generation...')
                ->info()
                ->body('This may take 30-60 seconds.')
                ->send();

            $prompt = $this->buildEnhancedPrompt($data);
            $provider = $data['provider'];

            if (str_starts_with($provider, 'kie_')) {
                $this->generateWithKie($data, $prompt);
            } else {
                $this->generateWithGemini($data, $prompt);
            }

            $this->loadCredits();
        } catch (PrismRateLimitedException $e) {
            $this->generationError = 'Rate limit reached. Please wait and try again.';
            Notification::make()
                ->title('Rate Limit Reached')
                ->danger()
                ->body($this->generationError)
                ->send();
        } catch (\Exception $e) {
            $this->generationError = $e->getMessage();
            Notification::make()
                ->title('Image Generation Failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    protected function generateWithKie(array $data, string $prompt): void
    {
        $service = app(KieAiService::class);

        if (! $service->isConfigured()) {
            throw new \Exception('KIE.ai not configured. Add KIE_API_KEY to .env');
        }

        $model = match ($data['provider']) {
            'kie_seedream' => 'seedream',
            'kie_nanobanana' => 'nanobanana',
            default => 'seedream',
        };

        $result = $service->generateImage($prompt, $model, $data['size']);

        if (! $result['success']) {
            throw new \Exception($result['error'] ?? 'Failed to generate image');
        }

        // If immediate result
        if ($result['is_complete'] && ! empty($result['image_url'])) {
            $this->handleImageResult($result['image_url'], $data, $model);

            return;
        }

        // If async task, poll for result
        if (! empty($result['task_id'])) {
            $this->pollKieImageTask($result['task_id'], $data, $model);
        }
    }

    protected function pollKieImageTask(string $taskId, array $data, string $model): void
    {
        $service = app(KieAiService::class);
        $maxAttempts = 40;

        for ($i = 0; $i < $maxAttempts; $i++) {
            sleep(3);

            // For images, we'd need a specific endpoint - for now assume immediate result
            // This is a placeholder for async image handling
        }

        throw new \Exception('Image generation timed out');
    }

    protected function generateWithGemini(array $data, string $prompt): void
    {
        $imageConfig = config('study-ai.images');

        ApiUsageTracker::trackRequest(
            provider: 'gemini',
            service: 'image-generation',
            action: 'generate',
            requestData: ['prompt_length' => strlen($prompt), 'size' => $data['size']],
            model: $imageConfig['model'],
            status: 'pending'
        );

        $response = Prism::image()
            ->using($imageConfig['provider'], $imageConfig['model'])
            ->withPrompt($prompt)
            ->withClientOptions(['timeout' => 120, 'connect_timeout' => 30])
            ->generate();

        ApiUsageTracker::trackSuccess(
            provider: 'gemini',
            service: 'image-generation',
            action: 'generate',
            requestData: ['prompt_length' => strlen($prompt)],
            model: $imageConfig['model']
        );

        if (isset($response->images[0]['url'])) {
            $this->handleImageResult($response->images[0]['url'], $data, 'gemini');
        } else {
            throw new \Exception('No image URL received from Gemini');
        }
    }

    protected function handleImageResult(string $imageUrl, array $data, string $model): void
    {
        // Download and store locally
        $imageContents = file_get_contents($imageUrl);
        $filename = 'image-'.Str::random(10).'.png';
        $path = config('kie.storage.paths.images', 'generated/images').'/'.$filename;

        Storage::disk('public')->put($path, $imageContents);

        $this->generatedImagePath = $path;
        $this->generatedImageUrl = Storage::disk('public')->url($path);

        // Save to database
        $provider = str_starts_with($data['provider'], 'kie_') ? 'kie' : 'gemini';
        $costUsd = match ($data['provider']) {
            'kie_seedream' => 0.0175,
            'kie_nanobanana' => 0.02,
            'gemini' => 0.03,
            default => 0,
        };

        GeneratedMedia::create([
            'user_id' => auth()->id(),
            'type' => 'image',
            'provider' => $provider,
            'model' => $model,
            'status' => 'completed',
            'prompt' => $data['prompt'],
            'settings' => [
                'style' => $data['style'],
                'size' => $data['size'],
                'quality' => $data['quality'],
            ],
            'file_path' => $path,
            'remote_url' => $imageUrl,
            'cost_usd' => $costUsd,
        ]);

        Notification::make()
            ->title('Image Generated Successfully!')
            ->success()
            ->body('Your image is ready to view and download.')
            ->send();
    }

    private function buildEnhancedPrompt(array $data): string
    {
        $prompt = $data['prompt'];

        $styleModifiers = [
            'photorealistic' => 'photorealistic, high detail, professional photography, 8k',
            'digital-art' => 'digital art, clean lines, modern, vibrant colors',
            'artistic' => 'artistic painting, expressive brushstrokes, gallery quality',
            'illustration' => 'clean illustration, modern design, professional',
            'watercolor' => 'watercolor painting, soft edges, traditional art',
            'oil-painting' => 'oil painting, rich colors, textured canvas',
            'biblical' => 'reverent, educational, Seventh-day Adventist style, biblical accuracy, hopeful',
        ];

        $qualityModifiers = [
            'standard' => 'good quality',
            'high' => 'high quality, detailed, sharp focus',
            'ultra' => 'ultra high quality, 8k resolution, masterpiece',
        ];

        $enhancedPrompt = $prompt;

        if (isset($styleModifiers[$data['style']])) {
            $enhancedPrompt .= ', '.$styleModifiers[$data['style']];
        }

        if (isset($qualityModifiers[$data['quality']])) {
            $enhancedPrompt .= ', '.$qualityModifiers[$data['quality']];
        }

        if (! empty($data['details'])) {
            $enhancedPrompt .= ', '.$data['details'];
        }

        return $enhancedPrompt;
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
