<?php

namespace App\Jobs;

use App\Services\ApiUsageTracker;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Prism;

class GenerateStudyGuideJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $formData,
        public string $componentId,
        public ?string $sessionId = null
    ) {}

    public function handle(): void
    {
        try {
            $this->dispatchStatus('generating_text', 'Generating study guide content...');

            $prompt = $this->buildPrompt($this->formData);
            $systemPrompt = $this->buildSystemPrompt();

            $modelConfig = config('study-ai.models.text');

            // Track the API request
            ApiUsageTracker::trackRequest(
                provider: $modelConfig['provider'],
                service: 'text-generation',
                action: 'generate-study',
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'max_tokens' => $modelConfig['max_tokens'],
                    'temperature' => $modelConfig['temperature'],
                    'topic' => $this->formData['topic'] ?? null,
                    'study_type' => $this->formData['study_type'] ?? null,
                ],
                model: $modelConfig['model'],
                status: 'pending'
            );

            try {
                $response = Prism::text()
                    ->using($modelConfig['provider'], $modelConfig['model'])
                    ->withSystemPrompt($systemPrompt)
                    ->withPrompt($prompt)
                    ->withMaxTokens($modelConfig['max_tokens'])
                    ->usingTemperature($modelConfig['temperature'])
                    ->withClientOptions([
                        'timeout' => 120,
                        'connect_timeout' => 30,
                    ])
                    ->generate();

                $content = $response->text;

                // Track success
                ApiUsageTracker::trackSuccess(
                    provider: $modelConfig['provider'],
                    service: 'text-generation',
                    action: 'generate-study',
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'max_tokens' => $modelConfig['max_tokens'],
                        'temperature' => $modelConfig['temperature'],
                        'topic' => $this->formData['topic'] ?? null,
                        'study_type' => $this->formData['study_type'] ?? null,
                    ],
                    model: $modelConfig['model'],
                    responseData: [
                        'response_length' => strlen($content),
                        'study_generated' => true,
                    ]
                );
            } catch (PrismRateLimitedException $e) {
                // Track rate limit
                ApiUsageTracker::trackRateLimit(
                    provider: $modelConfig['provider'],
                    service: 'text-generation',
                    action: 'generate-study',
                    errorMessage: $e->getMessage(),
                    requestData: [
                        'prompt_length' => strlen($prompt),
                        'max_tokens' => $modelConfig['max_tokens'],
                        'temperature' => $modelConfig['temperature'],
                        'topic' => $this->formData['topic'] ?? null,
                        'study_type' => $this->formData['study_type'] ?? null,
                    ],
                    model: $modelConfig['model'],
                    rateLimitInfo: [
                        'resets_at' => $e->rateLimit?->resetsAt?->toISOString(),
                        'remaining' => $e->rateLimit?->remaining,
                    ]
                );

                throw $e;
            }

            $this->dispatchContentUpdate($content);

            $imagePath = null;
            if ($this->formData['generate_image'] ?? false) {
                $this->dispatchStatus('generating_image', 'Creating featured image...');
                $imagePath = $this->generateFeaturedImage($this->formData['topic']);
            }

            $this->dispatchComplete($content, $imagePath);

            Notification::make()
                ->title('Study Guide Generated Successfully!')
                ->success()
                ->body('Review the content in the preview panel.')
                ->send();
        } catch (PrismRateLimitedException $e) {
            // Already handled above, but catch to prevent double handling
        } catch (\Exception $e) {
            // Track error
            ApiUsageTracker::trackError(
                provider: $modelConfig['provider'],
                service: 'text-generation',
                action: 'generate-study',
                errorMessage: $e->getMessage(),
                requestData: [
                    'prompt_length' => strlen($prompt),
                    'max_tokens' => $modelConfig['max_tokens'],
                    'temperature' => $modelConfig['temperature'],
                    'topic' => $this->formData['topic'] ?? null,
                    'study_type' => $this->formData['study_type'] ?? null,
                ],
                model: $modelConfig['model']
            );

            $this->dispatchError($e->getMessage());

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    protected function generateFeaturedImage(string $topic): ?string
    {
        try {
            $imageConfig = config('study-ai.images');

            if (! $imageConfig['enabled']) {
                return null;
            }

            $prompt = str_replace('{topic}', $topic, $imageConfig['prompt_template']);

            $response = Prism::image()
                ->using($imageConfig['provider'], $imageConfig['model'])
                ->withPrompt($prompt)
                ->withClientOptions([
                    'timeout' => 120,
                    'connect_timeout' => 30,
                ])
                ->generate();

            $imageData = base64_decode($response->image);
            $filename = \Illuminate\Support\Str::slug($topic).'-'.time().'.png';
            $path = $imageConfig['storage']['path'].'/'.$filename;

            Storage::disk($imageConfig['storage']['disk'])
                ->put($path, $imageData);

            $this->dispatchImageUpdate($path);

            return $path;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Image Generation Failed')
                ->warning()
                ->body('Could not generate image: '.$e->getMessage())
                ->send();

            return null;
        }
    }

    protected function buildSystemPrompt(): string
    {
        $systemPrompt = view('ai-prompts.study-system')->render();
        $formattingRules = view('ai-prompts.formatting-rules')->render();

        return $systemPrompt."\n\n".$formattingRules;
    }

    protected function buildPrompt(array $data): string
    {
        $type = $data['type'];
        $structure = config("study-ai.study_structure.{$type}", config('study-ai.study_structure.intermediate'));

        $prompt = "Create a comprehensive Bible study guide on the topic: \"{$data['topic']}\"\n\n";

        $prompt .= "FORMAT TYPE: {$type}\n";
        $prompt .= "TARGET LENGTH: {$data['length']}\n";
        $prompt .= "TONE: {$structure['tone']}\n";
        $prompt .= "PARAGRAPH STYLE: {$structure['length']}\n\n";

        if (! empty($data['additional_notes'])) {
            $prompt .= "ADDITIONAL INSTRUCTIONS: {$data['additional_notes']}\n\n";
        }

        $prompt .= "RECOMMENDED SECTIONS:\n";
        foreach ($structure['sections'] as $index => $section) {
            $prompt .= ($index + 1).". {$section}\n";
        }
        $prompt .= "\n";

        $prompt .= "THEOLOGICAL REQUIREMENTS:\n";
        foreach (config('study-ai.quality.theological_accuracy') as $requirement) {
            $prompt .= "- {$requirement}\n";
        }
        $prompt .= "\n";

        $prompt .= "SCRIPTURE USAGE:\n";
        foreach (config('study-ai.quality.scripture_usage') as $guideline) {
            $prompt .= "- {$guideline}\n";
        }

        return $prompt;
    }

    protected function dispatchStatus(string $status, string $message): void
    {
        // Store status in cache for polling
        \Illuminate\Support\Facades\Cache::put(
            "study-generation-{$this->componentId}-status",
            $message,
            now()->addMinutes(10)
        );
    }

    protected function dispatchContentUpdate(string $content): void
    {
        // Store content in cache for polling
        \Illuminate\Support\Facades\Cache::put(
            "study-generation-{$this->componentId}-content",
            $content,
            now()->addMinutes(10)
        );
    }

    protected function dispatchImageUpdate(string $path): void
    {
        // Store image path in cache for polling
        \Illuminate\Support\Facades\Cache::put(
            "study-generation-{$this->componentId}-image",
            $path,
            now()->addMinutes(10)
        );
    }

    protected function dispatchComplete(string $content, ?string $imagePath): void
    {
        // Store final result in cache
        \Illuminate\Support\Facades\Cache::put(
            "study-generation-{$this->componentId}-complete",
            [
                'content' => $content,
                'imagePath' => $imagePath,
            ],
            now()->addMinutes(10)
        );
    }

    protected function dispatchError(string $error): void
    {
        // Store error in cache
        \Illuminate\Support\Facades\Cache::put(
            "study-generation-{$this->componentId}-error",
            $error,
            now()->addMinutes(10)
        );
    }
}
