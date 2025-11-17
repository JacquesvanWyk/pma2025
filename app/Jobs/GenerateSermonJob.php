<?php

namespace App\Jobs;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Prism\Prism\Prism;

class GenerateSermonJob implements ShouldQueue
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
            $this->dispatchStatus('generating_sermon', 'Generating sermon content...');

            $prompt = $this->buildPrompt($this->formData);
            $systemPrompt = $this->buildSystemPrompt();

            $modelConfig = config('study-ai.models.text');

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

            $this->dispatchContentUpdate($content);
            $this->dispatchComplete($content);

            Notification::make()
                ->title('Sermon Generated Successfully!')
                ->success()
                ->body('Review the content in the preview panel.')
                ->send();
        } catch (\Exception $e) {
            $this->dispatchError($e->getMessage());

            Notification::make()
                ->title('Generation Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();
        }
    }

    protected function buildSystemPrompt(): string
    {
        // Get core theological beliefs
        $beliefs = config('study-ai.beliefs');
        $formattingRules = config('study-ai.formatting');

        $systemPrompt = "You are an AI assistant specialized in creating Seventh-day Adventist sermons based on present truth and biblical theology.\n\n";

        $systemPrompt .= "THEOLOGICAL FOUNDATION:\n";
        foreach ($beliefs as $key => $belief) {
            $systemPrompt .= strtoupper(str_replace('_', ' ', $key)).":\n";
            if (isset($belief['position'])) {
                $systemPrompt .= "Position: {$belief['position']}\n";
            }
            if (isset($belief['description'])) {
                $systemPrompt .= "{$belief['description']}\n";
            }
            $systemPrompt .= "\n";
        }

        $systemPrompt .= "\nFORMATTING REQUIREMENTS:\n";
        $systemPrompt .= "- Write in clear Markdown format\n";
        $systemPrompt .= "- Use proper headings (##, ###) for structure\n";
        $systemPrompt .= "- Include Scripture references in bold\n";
        $systemPrompt .= "- Use bullet points for key points\n";
        $systemPrompt .= "- Include practical applications\n\n";

        return $systemPrompt;
    }

    protected function buildPrompt(array $data): string
    {
        $sermonType = $data['sermon_type'];
        $duration = (int) $data['duration'];

        $prompt = "Create a complete sermon with the following details:\n\n";

        $prompt .= "TITLE: {$data['title']}\n";
        $prompt .= "PRIMARY SCRIPTURE: {$data['primary_scripture']}\n";
        $prompt .= "SERMON TYPE: {$sermonType}\n";
        $prompt .= "TARGET AUDIENCE: {$data['target_audience']}\n";
        $prompt .= "DURATION: {$duration} minutes\n\n";

        if (! empty($data['key_points'])) {
            $prompt .= "KEY POINTS TO COVER:\n{$data['key_points']}\n\n";
        }

        if (! empty($data['additional_notes'])) {
            $prompt .= "ADDITIONAL INSTRUCTIONS: {$data['additional_notes']}\n\n";
        }

        $prompt .= "REQUIRED SERMON STRUCTURE:\n";
        $prompt .= "1. **Introduction** (Hook the audience, establish context)\n";
        $prompt .= "2. **Main Points** (2-4 major points with Scripture support)\n";
        $prompt .= "   - Each point should have sub-points and illustrations\n";
        $prompt .= "   - Include relevant Bible verses with references\n";
        $prompt .= "3. **Application** (How to apply these truths to daily life)\n";
        $prompt .= "4. **Conclusion** (Summary and call to action)\n";
        $prompt .= "5. **Speaker Notes** (Timing suggestions, illustration ideas, emphasis points)\n\n";

        // Sermon type specific instructions
        $prompt .= $this->getSermonTypeInstructions($sermonType);

        $prompt .= "\nQUALITY REQUIREMENTS:\n";
        foreach (config('study-ai.quality.theological_accuracy', []) as $requirement) {
            $prompt .= "- {$requirement}\n";
        }
        $prompt .= "\n";

        $prompt .= "SCRIPTURE USAGE:\n";
        foreach (config('study-ai.quality.scripture_usage', []) as $guideline) {
            $prompt .= "- {$guideline}\n";
        }

        $prompt .= "\n\nGenerate the complete sermon now:";

        return $prompt;
    }

    protected function getSermonTypeInstructions(string $type): string
    {
        return match ($type) {
            'evangelistic' => "\nEVANGELISTIC FOCUS:\n- Clearly present the gospel and plan of salvation\n- Address common objections lovingly\n- Include an altar call or decision opportunity\n- Use relatable illustrations for non-believers\n\n",

            'doctrinal' => "\nDOCTRINAL FOCUS:\n- Thoroughly explain the biblical doctrine\n- Address common misconceptions\n- Show how this truth fits into Adventist theology\n- Provide clear Scripture support for each point\n\n",

            'expository' => "\nEXPOSITORY FOCUS:\n- Work through the passage verse by verse\n- Explain original context and meaning\n- Show how it applies today\n- Stay true to the text without adding outside ideas\n\n",

            'topical' => "\nTOPICAL FOCUS:\n- Gather Scriptures from multiple books on this theme\n- Show the biblical consistency of this topic\n- Organize around logical main points\n- Make practical connections\n\n",

            'devotional' => "\nDEVOTIONAL FOCUS:\n- Inspire personal spiritual growth\n- Focus on relationship with God\n- Include practical spiritual disciplines\n- Use personal and relatable examples\n\n",

            default => '',
        };
    }

    protected function dispatchStatus(string $status, string $message): void
    {
        \Illuminate\Support\Facades\Cache::put(
            "sermon-generation-{$this->componentId}-status",
            $message,
            now()->addMinutes(10)
        );
    }

    protected function dispatchContentUpdate(string $content): void
    {
        \Illuminate\Support\Facades\Cache::put(
            "sermon-generation-{$this->componentId}-content",
            $content,
            now()->addMinutes(10)
        );
    }

    protected function dispatchComplete(string $content): void
    {
        \Illuminate\Support\Facades\Cache::put(
            "sermon-generation-{$this->componentId}-complete",
            ['content' => $content],
            now()->addMinutes(10)
        );
    }

    protected function dispatchError(string $error): void
    {
        \Illuminate\Support\Facades\Cache::put(
            "sermon-generation-{$this->componentId}-error",
            $error,
            now()->addMinutes(10)
        );
    }
}
