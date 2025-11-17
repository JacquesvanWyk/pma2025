<?php

namespace App\Jobs;

use App\Models\SermonSlide;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Prism\Prism\Prism;

class ProcessSlideAiEditJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public function __construct(
        public SermonSlide $slide,
        public string $prompt
    ) {}

    public function handle(): void
    {
        try {
            $currentContent = $this->slide->html_content;
            $currentStyles = $this->slide->css_styles;
            $backgroundType = $this->slide->background_type;
            $backgroundValue = $this->slide->background_value;

            // Parse the prompt to determine what needs to be changed
            $aiPrompt = $this->buildEditPrompt($currentContent, $currentStyles, $backgroundType, $backgroundValue);
            $systemPrompt = $this->buildEditSystemPrompt();

            $modelConfig = config('study-ai.models.text');

            $response = Prism::text()
                ->using($modelConfig['provider'], $modelConfig['model'])
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($aiPrompt)
                ->withMaxTokens(3000)
                ->usingTemperature(0.7)
                ->withClientOptions([
                    'timeout' => 90,
                    'connect_timeout' => 30,
                ])
                ->generate();

            // Parse the AI response to extract the updated content
            $result = $this->parseAiResponse($response->text);

            // Update the slide
            $updates = [];

            if (isset($result['html_content'])) {
                $updates['html_content'] = $result['html_content'];
            }

            if (isset($result['css_styles'])) {
                $updates['css_styles'] = $result['css_styles'];
            }

            if (isset($result['background_type'])) {
                $updates['background_type'] = $result['background_type'];
            }

            if (isset($result['background_value'])) {
                $updates['background_value'] = $result['background_value'];
            }

            if (! empty($updates)) {
                // Add to prompt history
                $promptHistory = $this->slide->ai_prompt_history ?? [];
                $promptHistory[] = [
                    'timestamp' => now()->toIso8601String(),
                    'action' => 'ai_edit',
                    'prompt' => $this->prompt,
                ];

                $updates['ai_prompt_history'] = $promptHistory;

                $this->slide->update($updates);

                Notification::make()
                    ->title('AI Edit Applied')
                    ->success()
                    ->body('Your slide has been updated based on your prompt.')
                    ->send();
            } else {
                Notification::make()
                    ->title('No Changes Made')
                    ->warning()
                    ->body('The AI could not determine what changes to make based on your prompt.')
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('AI Edit Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();

            throw $e;
        }
    }

    protected function buildEditPrompt(string $currentContent, ?string $currentStyles, string $backgroundType, ?string $backgroundValue): string
    {
        $prompt = "You are editing a presentation slide based on a user's request.\n\n";
        $prompt .= "USER REQUEST: {$this->prompt}\n\n";
        $prompt .= "CURRENT SLIDE HTML CONTENT:\n{$currentContent}\n\n";

        if ($currentStyles) {
            $prompt .= "CURRENT CSS STYLES:\n{$currentStyles}\n\n";
        }

        $prompt .= "CURRENT BACKGROUND:\n";
        $prompt .= "Type: {$backgroundType}\n";
        $prompt .= 'Value: '.($backgroundValue ?? 'none')."\n\n";

        $prompt .= "Based on the user's request, update the slide content and/or styling.\n\n";
        $prompt .= "Return your response in this JSON format:\n";
        $prompt .= "{\n";
        $prompt .= "  \"html_content\": \"updated HTML content (if changed)\",\n";
        $prompt .= "  \"css_styles\": \"updated CSS styles (if changed)\",\n";
        $prompt .= "  \"background_type\": \"color|gradient|image (if changed)\",\n";
        $prompt .= "  \"background_value\": \"hex color, gradient CSS, or image URL (if changed)\"\n";
        $prompt .= "}\n\n";
        $prompt .= 'Only include fields that need to be changed. Return ONLY valid JSON, no other text.';

        return $prompt;
    }

    protected function buildEditSystemPrompt(): string
    {
        return "You are an expert presentation slide editor.\n\n".
            "Your task is to interpret user requests and modify slide content accordingly.\n\n".
            "Common requests include:\n".
            "- Changing text content, size, or formatting\n".
            "- Modifying backgrounds (colors, gradients, images)\n".
            "- Adding or removing bullet points\n".
            "- Converting between slide types (content, scripture, outline, etc.)\n".
            "- Adjusting layout and spacing\n\n".
            "Return ONLY a valid JSON object with the fields that need to be updated.\n".
            'If you cannot interpret the request, return an empty JSON object: {}';
    }

    protected function parseAiResponse(string $response): array
    {
        // Try to extract JSON from response
        if (preg_match('/\{[\s\S]*\}/', $response, $matches)) {
            $json = json_decode($matches[0], true);

            if ($json) {
                return $json;
            }
        }

        return [];
    }
}
