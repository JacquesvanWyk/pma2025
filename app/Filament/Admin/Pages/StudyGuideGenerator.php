<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Study;
use App\Models\Tag;
use App\Models\Tract;
use App\Services\ApiUsageTracker;
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
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Prism;

/**
 * @property-read Schema $form
 */
class StudyGuideGenerator extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static \UnitEnum|string|null $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'Study Guide Generator';

    protected static ?string $title = 'AI Study Guide Generator';

    protected string $view = 'filament.admin.pages.study-guide-generator';

    public ?array $data = [];

    public ?string $generatedContent = null;

    public bool $isGenerating = false;

    public ?string $editableContent = null;

    public ?string $aiEditInstructions = null;

    public ?string $generatedImagePath = null;

    public ?string $generationStatusMessage = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function checkGenerationStatus(): void
    {
        if (! $this->isGenerating) {
            return;
        }

        $componentId = $this->getId();

        // Check for completion
        $complete = \Illuminate\Support\Facades\Cache::get("study-generation-{$componentId}-complete");
        if ($complete) {
            $this->generatedContent = $complete['content'];
            $this->generatedImagePath = $complete['imagePath'] ?? null;
            $this->isGenerating = false;
            $this->generationStatusMessage = null;

            \Illuminate\Support\Facades\Cache::forget("study-generation-{$componentId}-complete");
            \Illuminate\Support\Facades\Cache::forget("study-generation-{$componentId}-status");
            \Illuminate\Support\Facades\Cache::forget("study-generation-{$componentId}-content");
            \Illuminate\Support\Facades\Cache::forget("study-generation-{$componentId}-image");

            return;
        }

        // Check for error
        $error = \Illuminate\Support\Facades\Cache::get("study-generation-{$componentId}-error");
        if ($error) {
            $this->isGenerating = false;
            $this->generationStatusMessage = null;

            \Illuminate\Support\Facades\Cache::forget("study-generation-{$componentId}-error");

            return;
        }

        // Check for status update
        $status = \Illuminate\Support\Facades\Cache::get("study-generation-{$componentId}-status");
        if ($status) {
            $this->generationStatusMessage = $status;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveAsStudy')
                ->label('Save as Study')
                ->icon('heroicon-o-bookmark')
                ->color('success')
                ->visible(fn () => $this->generatedContent !== null || $this->editableContent !== null)
                ->form([
                    TextInput::make('title')
                        ->required()
                        ->default(fn () => $this->data['topic'] ?? '')
                        ->columnSpanFull(),

                    Select::make('language')
                        ->required()
                        ->options([
                            'english' => 'English',
                            'afrikaans' => 'Afrikaans',
                        ])
                        ->default('english'),

                    Select::make('tags')
                        ->multiple()
                        ->options(Tag::pluck('name', 'id'))
                        ->preload()
                        ->columnSpanFull(),

                    Select::make('status')
                        ->required()
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                ])
                ->action(function (array $data) {
                    $this->saveAsStudy($data);
                }),

            Action::make('generateTract')
                ->label('Generate Tract')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->visible(fn () => $this->generatedContent !== null || $this->editableContent !== null)
                ->form([
                    TextInput::make('title')
                        ->required()
                        ->default(fn () => ($this->data['topic'] ?? '').' - Tract')
                        ->helperText('Tract title (will be condensed version)')
                        ->columnSpanFull(),

                    Select::make('language')
                        ->required()
                        ->options([
                            'english' => 'English',
                            'afrikaans' => 'Afrikaans',
                        ])
                        ->default('english'),

                    Select::make('tags')
                        ->multiple()
                        ->options(Tag::pluck('name', 'id'))
                        ->preload()
                        ->columnSpanFull(),

                    Select::make('status')
                        ->required()
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                ])
                ->action(function (array $data) {
                    $this->generateTract($data);
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('topic')
                        ->label('Bible Topic or Passage')
                        ->required()
                        ->placeholder('e.g., The Sabbath Truth, Daniel 2, The Sanctuary')
                        ->helperText('Enter the topic or Bible passage you want to create a study guide for')
                        ->columnSpanFull(),

                    Select::make('type')
                        ->label('Study Guide Type')
                        ->required()
                        ->options([
                            'beginner' => 'Beginner Level - Simple and Easy to Understand',
                            'intermediate' => 'Intermediate - Detailed Bible Study',
                            'advanced' => 'Advanced - Deep Theological Analysis',
                            'youth' => 'Youth Friendly - For Young People',
                            'sermon' => 'Sermon Outline - For Preachers',
                            'tract' => 'Tract Format - For Distribution',
                        ])
                        ->default('intermediate')
                        ->helperText('Choose the format and depth of the study guide'),

                    Select::make('length')
                        ->label('Length')
                        ->required()
                        ->options([
                            'short' => 'Short (1-2 pages)',
                            'medium' => 'Medium (3-5 pages)',
                            'long' => 'Long (5-10 pages)',
                        ])
                        ->default('medium')
                        ->helperText('How long should the study guide be?'),

                    Textarea::make('additional_notes')
                        ->label('Additional Instructions (Optional)')
                        ->rows(3)
                        ->placeholder('Any specific points to cover or emphasis areas...')
                        ->columnSpanFull(),

                    Checkbox::make('generate_image')
                        ->label('Generate AI Featured Image')
                        ->helperText('Uses AI to create a reverent biblical illustration for this study')
                        ->default(false),
                ])
                    ->livewireSubmitHandler('generateStudyGuide')
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
                ->label('Generate Study Guide')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->size('lg')
                ->requiresConfirmation()
                ->modalHeading('Generate AI Study Guide?')
                ->modalDescription('This will use AI to create a Bible study guide based on your inputs. Please review the content for theological accuracy.')
                ->modalSubmitActionLabel('Generate')
                ->action(function () {
                    $this->generateStudyGuide();
                })
                ->disabled(fn () => $this->isGenerating),

            Action::make('clear')
                ->label('Clear')
                ->color('gray')
                ->visible(fn () => $this->generatedContent !== null)
                ->action(function () {
                    $this->generatedContent = null;
                    $this->generatedImagePath = null;
                    Notification::make()
                        ->title('Content cleared')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function generateStudyGuide(): void
    {
        $this->validate();

        $data = $this->form->getState();

        $this->isGenerating = true;
        $this->generatedContent = null;
        $this->generatedImagePath = null;
        $this->generationStatusMessage = 'Queuing generation job...';

        try {
            $job = new \App\Jobs\GenerateStudyGuideJob(
                formData: $data,
                componentId: $this->getId()
            );

            dispatch($job);

            $this->generationStatusMessage = 'Generation started...';

            Notification::make()
                ->title('Generation Started')
                ->info()
                ->body('Your study guide is being generated. Please wait...')
                ->send();
        } catch (\Exception $e) {
            $this->isGenerating = false;

            Notification::make()
                ->title('Failed to Start Generation')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    public function downloadStudyGuide(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $this->form->getState();
        $topic = $data['topic'] ?? 'study-guide';
        $filename = \Illuminate\Support\Str::slug($topic).'-'.now()->format('Y-m-d').'.md';

        return response()->streamDownload(function () {
            echo $this->generatedContent;
        }, $filename, [
            'Content-Type' => 'text/markdown',
        ]);
    }

    public function saveAsStudy(array $data): void
    {
        $content = $this->editableContent ?? $this->generatedContent;

        if (! $content) {
            Notification::make()
                ->title('No Content')
                ->danger()
                ->body('There is no content to save. Please generate a study guide first.')
                ->send();

            return;
        }

        $study = Study::create([
            'title' => $data['title'],
            'content' => $content,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($content), 200),
            'featured_image' => $this->generatedImagePath,
            'language' => $data['language'],
            'type' => $this->data['type'] ?? null,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        if (! empty($data['tags'])) {
            $study->tags()->attach($data['tags']);
        }

        Notification::make()
            ->title('Study Saved Successfully!')
            ->success()
            ->body("The study \"{$study->title}\" has been saved.")
            ->send();

        $this->redirect(route('filament.admin.resources.studies.edit', ['record' => $study]));

        // Clear the generated content after saving
        $this->generatedContent = null;
        $this->editableContent = null;
        $this->generatedImagePath = null;
    }

    public function generateTract(array $data): void
    {
        // Increase execution time for AI generation
        set_time_limit(300); // 5 minutes

        $content = $this->editableContent ?? $this->generatedContent;

        if (! $content) {
            Notification::make()
                ->title('No Content')
                ->danger()
                ->body('There is no content to generate a tract from. Please generate a study guide first.')
                ->send();

            return;
        }

        $this->isGenerating = true;

        try {
            $systemPrompt = view('ai-prompts.tract-system')->render();

            $prompt = "Convert the following Bible study into a condensed evangelistic tract:\n\n";
            $prompt .= "ORIGINAL STUDY:\n{$content}\n\n";
            $prompt .= "TARGET: 300-500 words (1-2 printed pages)\n";
            $prompt .= "FOCUS: Extract the ONE most important truth/message\n";
            $prompt .= "AUDIENCE: General public, seekers of truth\n\n";
            $prompt .= 'Remember: This is for distribution - make it memorable, impactful, and invite further study.';

            $modelConfig = config('study-ai.models.text');

            // Track the API request
            ApiUsageTracker::trackRequest(
                provider: $modelConfig['provider'],
                service: 'text-generation',
                action: 'generate-tract',
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'max_tokens' => 2000,
                    'temperature' => $modelConfig['temperature'],
                ],
                model: $modelConfig['model'],
                status: 'pending'
            );

            try {
                $response = Prism::text()
                    ->using($modelConfig['provider'], $modelConfig['model'])
                    ->withSystemPrompt($systemPrompt)
                    ->withPrompt($prompt)
                    ->withMaxTokens(2000) // Shorter for tracts
                    ->usingTemperature($modelConfig['temperature'])
                    ->withClientOptions([
                        'timeout' => 120, // 2 minutes timeout for HTTP requests
                        'connect_timeout' => 30,
                    ])
                    ->generate();

                $tractContent = $response->text;

                // Track success
                ApiUsageTracker::trackSuccess(
                    provider: $modelConfig['provider'],
                    service: 'text-generation',
                    action: 'generate-tract',
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'max_tokens' => 2000,
                        'temperature' => $modelConfig['temperature'],
                    ],
                    model: $modelConfig['model'],
                    responseData: [
                        'response_length' => strlen($tractContent),
                        'tract_generated' => true,
                    ]
                );
            } catch (PrismRateLimitedException $e) {
                // Track rate limit
                ApiUsageTracker::trackRateLimit(
                    provider: $modelConfig['provider'],
                    service: 'text-generation',
                    action: 'generate-tract',
                    errorMessage: $e->getMessage(),
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'max_tokens' => 2000,
                        'temperature' => $modelConfig['temperature'],
                    ],
                    model: $modelConfig['model'],
                    rateLimitInfo: [
                        'resets_at' => $e->rateLimit?->resetsAt?->toISOString(),
                        'remaining' => $e->rateLimit?->remaining,
                    ]
                );

                throw $e;
            }

            $tract = Tract::create([
                'title' => $data['title'],
                'content' => $tractContent,
                'language' => $data['language'],
                'status' => $data['status'],
                'published_at' => $data['status'] === 'published' ? now() : null,
            ]);

            if (! empty($data['tags'])) {
                $tract->tags()->attach($data['tags']);
            }

            Notification::make()
                ->title('Tract Generated Successfully!')
                ->success()
                ->body("The tract \"{$tract->title}\" has been created and saved.")
                ->send();

            $this->redirect(route('filament.admin.resources.tracts.edit', ['record' => $tract]));

            // Clear the generated content after creating tract
            $this->generatedContent = null;
            $this->editableContent = null;
            $this->generatedImagePath = null;
        } catch (PrismRateLimitedException $e) {
            // Already handled above, but catch to prevent double handling
        } catch (\Exception $e) {
            // Track error
            ApiUsageTracker::trackError(
                provider: $modelConfig['provider'],
                service: 'text-generation',
                action: 'generate-tract',
                errorMessage: $e->getMessage(),
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'max_tokens' => 2000,
                    'temperature' => $modelConfig['temperature'],
                ],
                model: $modelConfig['model']
            );

            Notification::make()
                ->title('Tract Generation Failed')
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
}
