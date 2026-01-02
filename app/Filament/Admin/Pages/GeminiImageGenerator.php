<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Services\ApiUsageTracker;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
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
class GeminiImageGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-sparkles';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Gemini Image Generator';

    protected static ?string $title = 'Gemini AI Image Generator';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.admin.pages.gemini-image-generator';

    public ?array $data = [];

    public ?string $generatedImageUrl = null;

    public ?string $generatedImagePath = null;

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
                            TextInput::make('topic')
                                ->label('Image Topic/Subject')
                                ->required()
                                ->placeholder('e.g., Noah\'s Ark, The Creation, Second Coming')
                                ->helperText('Describe the biblical topic or scene you want illustrated')
                                ->columnSpanFull(),

                            Select::make('style_type')
                                ->label('Illustration Style')
                                ->options([
                                    'educational' => 'Educational - Clear and instructional',
                                    'inspirational' => 'Inspirational - Uplifting and hopeful',
                                    'historical' => 'Historical - Biblical accuracy focused',
                                    'modern' => 'Modern - Contemporary church materials',
                                ])
                                ->default('educational')
                                ->helperText('Choose the style of illustration'),

                            Select::make('audience')
                                ->label('Target Audience')
                                ->options([
                                    'children' => 'Children - Simple and colorful',
                                    'youth' => 'Youth - Modern and engaging',
                                    'adults' => 'Adults - Detailed and reverent',
                                    'all-ages' => 'All Ages - General appeal',
                                ])
                                ->default('all-ages')
                                ->helperText('Primary audience for the image'),

                            Select::make('size')
                                ->label('Image Size')
                                ->options([
                                    '1024x1024' => 'Square (1024×1024)',
                                    '1024x1792' => 'Portrait (1024×1792)',
                                    '1792x1024' => 'Landscape (1792×1024)',
                                ])
                                ->default('1024x1024')
                                ->helperText('Choose the aspect ratio'),
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
                ->label('Generate Image with Gemini')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate AI Image with Gemini?')
                ->modalDescription('This will use Google\'s Gemini Imagen 3 to create an image based on your topic. This may take a few moments.')
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
                ->title('Starting Gemini Image Generation...')
                ->info()
                ->body('Submitting your request to Google\'s Gemini Imagen 3. This may take 30-60 seconds.')
                ->send();

            // Build the enhanced prompt using the existing configuration
            $imageConfig = config('study-ai.images');
            $prompt = $this->buildEnhancedPrompt($data, $imageConfig['prompt_template']);

            // Track the API request
            ApiUsageTracker::trackRequest(
                provider: $imageConfig['provider'],
                service: 'image-generation',
                action: 'generate',
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'style_type' => $data['style_type'] ?? null,
                    'audience' => $data['audience'] ?? null,
                    'size' => $data['size'] ?? null,
                ],
                model: $imageConfig['model'],
                status: 'pending'
            );

            // Use Prism with Gemini for image generation with proper rate limiting
            try {
                $response = Prism::image()
                    ->using($imageConfig['provider'], $imageConfig['model'])
                    ->withPrompt($prompt)
                    ->withClientOptions([
                        'timeout' => 120,
                        'connect_timeout' => 30,
                    ])
                    ->generate();

                // Track success and log rate limit info if available
                $rateLimitInfo = null;
                if (method_exists($response, 'getRateLimit') && $rateLimit = $response->getRateLimit()) {
                    $rateLimitInfo = [
                        'provider' => $rateLimit->name,
                        'limit' => $rateLimit->limit,
                        'remaining' => $rateLimit->remaining,
                        'resets_at' => $rateLimit->resetsAt?->toISOString(),
                    ];

                    // Show warning if getting close to limit
                    if ($rateLimit->remaining <= 5) {
                        Notification::make()
                            ->title('Getting Close to Rate Limit')
                            ->warning()
                            ->body("Only {$rateLimit->remaining} Gemini requests remaining. Limit resets at: ".
                                   ($rateLimit->resetsAt?->format('H:i:s') ?? 'Unknown'))
                            ->send();
                    }
                }

                ApiUsageTracker::trackSuccess(
                    provider: $imageConfig['provider'],
                    service: 'image-generation',
                    action: 'generate',
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'style_type' => $data['style_type'] ?? null,
                        'audience' => $data['audience'] ?? null,
                        'size' => $data['size'] ?? null,
                    ],
                    model: $imageConfig['model'],
                    rateLimitInfo: $rateLimitInfo,
                    responseData: [
                        'has_images' => isset($response->images) && ! empty($response->images),
                        'image_count' => isset($response->images) ? count($response->images) : 0,
                    ]
                );
            } catch (PrismRateLimitedException $e) {
                // Handle Prism's specific rate limit exception
                $rateLimitInfo = [
                    'provider' => $e->provider?->value,
                    'resets_at' => $e->rateLimit?->resetsAt?->toISOString(),
                    'remaining' => $e->rateLimit?->remaining,
                    'error' => $e->getMessage(),
                ];

                ApiUsageTracker::trackRateLimit(
                    provider: $imageConfig['provider'],
                    service: 'image-generation',
                    action: 'generate',
                    errorMessage: $e->getMessage(),
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'style_type' => $data['style_type'] ?? null,
                        'audience' => $data['audience'] ?? null,
                        'size' => $data['size'] ?? null,
                    ],
                    model: $imageConfig['model'],
                    rateLimitInfo: $rateLimitInfo
                );

                $resetTime = $e->rateLimit?->resetsAt;
                $waitTime = $resetTime ? $resetTime->diffInSeconds(now()) : 60;

                Notification::make()
                    ->title('Gemini Rate Limit Reached')
                    ->danger()
                    ->body('Rate limit reached. Resets at: '.
                           ($resetTime?->format('H:i:s') ?? 'Unknown').
                           '. Please wait before trying again.')
                    ->send();

                throw new \Exception('Gemini rate limit reached. Resets at: '.
                                    ($resetTime?->format('H:i:s') ?? 'Unknown'));
            }

            // Log successful response
            \Log::info('Gemini Image Generation Success', [
                'user_id' => auth()->id(),
                'response_type' => gettype($response),
                'has_images' => isset($response->images) && ! empty($response->images),
                'timestamp' => now()->toISOString(),
            ]);

            // Handle the Prism/Gemini response
            if (isset($response->images[0]['url'])) {
                $imageUrl = $response->images[0]['url'];

                // Download and store the image
                $imageContents = file_get_contents($imageUrl);
                $filename = 'gemini-'.Str::random(10).'.png';
                $path = $imageConfig['storage']['path'].'/'.$filename;

                Storage::disk($imageConfig['storage']['disk'])->put($path, $imageContents);

                $this->generatedImagePath = $path;
                $this->generatedImageUrl = Storage::disk($imageConfig['storage']['disk'])->url($path);

                Notification::make()
                    ->title('Gemini Image Generated Successfully!')
                    ->success()
                    ->body('Your image has been generated using Google\'s Gemini Imagen 3 and saved.')
                    ->send();
            } else {
                throw new \Exception('No image URL received from Gemini API');
            }
        } catch (PrismRateLimitedException $e) {
            // Already handled above, but catch to prevent double handling
        } catch (\Exception $e) {
            $this->generationError = $e->getMessage();

            // Track error
            ApiUsageTracker::trackError(
                provider: $imageConfig['provider'],
                service: 'image-generation',
                action: 'generate',
                errorMessage: $e->getMessage(),
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'style_type' => $data['style_type'] ?? null,
                    'audience' => $data['audience'] ?? null,
                    'size' => $data['size'] ?? null,
                ],
                model: $imageConfig['model']
            );

            Notification::make()
                ->title('Gemini Image Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    private function buildEnhancedPrompt(array $data, string $template): string
    {
        $topic = $data['topic'];
        $style = $data['style_type'];
        $audience = $data['audience'];

        // Replace the topic in the template
        $prompt = str_replace('{topic}', $topic, $template);

        // Add style and audience specific enhancements
        $styleEnhancements = [
            'educational' => 'clear visuals, instructional, labeled elements, diagrams, educational style',
            'inspirational' => 'hopeful, uplifting, warm lighting, encouraging atmosphere, inspirational',
            'historical' => 'biblically accurate, historical context, authentic details, historically informed',
            'modern' => 'contemporary design, clean lines, modern church materials, current style',
        ];

        $audienceEnhancements = [
            'children' => 'bright colors, simple shapes, child-friendly, safe and appropriate',
            'youth' => 'modern aesthetic, engaging, relatable, youth-oriented design',
            'adults' => 'detailed, reverent, sophisticated, mature themes',
            'all-ages' => 'universal appeal, accessible, family-friendly, broad audience',
        ];

        $prompt .= ', '.($styleEnhancements[$style] ?? 'educational style');
        $prompt .= ', '.($audienceEnhancements[$audience] ?? 'appropriate for all ages');

        return $prompt;
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

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
