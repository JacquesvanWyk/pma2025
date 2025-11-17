<?php

namespace App\Filament\Admin\Pages;

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
use Illuminate\Support\Facades\Http;

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

    public bool $isGenerating = false;

    public ?string $generationError = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
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
                            TextInput::make('prompt')
                                ->label('Image Description')
                                ->required()
                                ->placeholder('e.g., A peaceful sunrise over mountains with a cross in the foreground')
                                ->helperText('Describe the main subject and scene in detail. Be specific about objects, colors, and mood.')
                                ->columnSpanFull(),

                            Select::make('style')
                                ->label('Art Style')
                                ->options([
                                    'photorealistic' => 'Photorealistic - High Quality Photo',
                                    'digital-art' => 'Digital Art - Modern & Clean',
                                    'artistic' => 'Artistic - Painterly Style',
                                    'illustration' => 'Illustration - Clean & Simple',
                                    'anime' => 'Anime/Manga Style',
                                    'watercolor' => 'Watercolor Painting',
                                    'oil-painting' => 'Oil Painting',
                                    '3d-render' => '3D Render',
                                ])
                                ->default('photorealistic')
                                ->helperText('Choose the artistic style'),

                            Select::make('size')
                                ->label('Image Size')
                                ->options([
                                    '1024x1024' => 'Square (1024×1024)',
                                    '1024x1792' => 'Portrait (1024×1792)',
                                    '1792x1024' => 'Landscape (1792×1024)',
                                ])
                                ->default('1024x1024')
                                ->helperText('Choose the aspect ratio'),

                            Select::make('quality')
                                ->label('Quality Level')
                                ->options([
                                    'standard' => 'Standard',
                                    'high' => 'High Quality',
                                    'ultra' => 'Ultra HD',
                                ])
                                ->default('high')
                                ->helperText('Higher quality takes longer but looks better'),

                            Textarea::make('details')
                                ->label('Additional Details (Optional)')
                                ->rows(2)
                                ->placeholder('Lighting: soft golden hour light&#10;Colors: warm oranges and deep blues&#10;Mood: peaceful and serene')
                                ->helperText('Add specific details about lighting, colors, mood, or composition')
                                ->columnSpanFull(),

                            TextInput::make('negative_prompt')
                                ->label('What to Avoid (Optional)')
                                ->placeholder('e.g., text, words, people, blurry, low quality, distorted')
                                ->helperText('Describe what you do NOT want in the image. Separate with commas.')
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
                ->modalDescription('This will use AI to create an image based on your description. This may take a few moments.')
                ->modalSubmitActionLabel('Generate')
                ->action(function () {
                    $this->generateImage();
                })
                ->disabled(fn () => $this->isGenerating),

            Action::make('clear')
                ->label('Clear')
                ->color('gray')
                ->visible(fn () => $this->generatedImageUrl !== null)
                ->action(function () {
                    $this->generatedImageUrl = null;
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
        $this->generationError = null;

        try {
            Notification::make()
                ->title('Starting Image Generation...')
                ->info()
                ->body('Submitting your request to the AI. This may take 30-60 seconds.')
                ->send();

            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer '.config('services.nanobanana.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->post(config('services.nanobanana.base_url').'/generate', [
                    'prompt' => $this->buildEnhancedPrompt($data),
                    'type' => 'TEXTTOIAMGE',
                    'callBackUrl' => url('/api/nanobanana/webhook'), // We'll need to create this endpoint
                    'numImages' => 1,
                    'image_size' => $this->mapSizeToFormat($data['size'] ?? '1024x1024'),
                ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['data']['taskId'])) {
                    // Store the task ID
                    $taskId = $result['data']['taskId'];

                    // Check the status and get the actual generated image
                    $this->checkImageStatus($taskId);
                } else {
                    throw new \Exception('Invalid response format from API - no task ID received');
                }
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['msg'] ?? $response->body() ?? 'API request failed';
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            $this->generationError = $e->getMessage();

            Notification::make()
                ->title('Image Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    private function mapSizeToFormat(string $size): string
    {
        return match ($size) {
            '1024x1024' => '1:1',
            '1024x1792' => '9:16',
            '1792x1024' => '16:9',
            default => '1:1',
        };
    }

    private function buildEnhancedPrompt(array $data): string
    {
        $prompt = $data['prompt'];

        // Add style modifiers
        $styleModifiers = [
            'photorealistic' => 'photorealistic, high detail, professional photography, 8k, ultra realistic',
            'digital-art' => 'digital art, clean lines, modern, vibrant colors, detailed illustration',
            'artistic' => 'artistic painting, expressive brushstrokes, gallery quality, fine art',
            'illustration' => 'clean illustration, modern design, minimalist, professional artwork',
            'anime' => 'anime style, manga art, clean lines, vibrant anime colors',
            'watercolor' => 'watercolor painting, soft edges, traditional art, paper texture',
            'oil-painting' => 'oil painting, rich colors, textured canvas, classical art',
            '3d-render' => '3d render, cgi, detailed 3d, cinematic lighting',
        ];

        $qualityModifiers = [
            'standard' => 'good quality',
            'high' => 'high quality, detailed, sharp focus',
            'ultra' => 'ultra high quality, 8k resolution, professional grade, masterpiece',
        ];

        // Build the enhanced prompt
        $enhancedPrompt = $prompt;

        // Add style
        if (isset($data['style']) && isset($styleModifiers[$data['style']])) {
            $enhancedPrompt .= ', '.$styleModifiers[$data['style']];
        }

        // Add quality
        if (isset($data['quality']) && isset($qualityModifiers[$data['quality']])) {
            $enhancedPrompt .= ', '.$qualityModifiers[$data['quality']];
        }

        // Add additional details
        if (! empty($data['details'])) {
            $enhancedPrompt .= ', '.$data['details'];
        }

        // Add lighting and composition enhancements
        $enhancedPrompt .= ', professional lighting, perfect composition, award-winning photography';

        return $enhancedPrompt;
    }

    private function checkImageStatus(string $taskId): void
    {
        try {
            $maxAttempts = 20; // Try up to 20 times
            $attempts = 0;

            while ($attempts < $maxAttempts) {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer '.config('services.nanobanana.api_key'),
                        'Content-Type' => 'application/json',
                    ])
                    ->get(config('services.nanobanana.base_url').'/record-info', [
                        'taskId' => $taskId,
                    ]);

                if ($response->successful()) {
                    $result = $response->json();

                    \Log::info('Image status check:', [
                        'taskId' => $taskId,
                        'attempt' => $attempts,
                        'response' => $result,
                    ]);

                    if (isset($result['data']['successFlag']) && $result['data']['successFlag'] === 1) {
                        // Image is ready, get the result URL
                        if (isset($result['data']['response']['resultImageUrl'])) {
                            $this->generatedImageUrl = $result['data']['response']['resultImageUrl'];

                            Notification::make()
                                ->title('Image Generated Successfully!')
                                ->success()
                                ->body('Your AI image has been generated and is ready to view.')
                                ->send();

                            return;
                        }
                    } elseif (isset($result['data']['successFlag']) && $result['data']['successFlag'] === 0) {
                        // Still processing or failed
                        if (isset($result['data']['errorCode']) && $result['data']['errorCode'] !== 0) {
                            $errorMsg = $result['data']['errorMessage'] ?? 'Unknown error occurred';
                            throw new \Exception("API Error: {$errorMsg}");
                        }
                    }
                }

                // Still processing, wait and try again
                sleep(3); // Wait 3 seconds between checks
                $attempts++;
            }

            throw new \Exception('Image generation timed out. Please try again.');
        } catch (\Exception $e) {
            $this->generationError = $e->getMessage();

            Notification::make()
                ->title('Image Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }
}
