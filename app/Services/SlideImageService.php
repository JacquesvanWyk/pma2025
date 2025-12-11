<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SlideImageService
{
    protected NanoBananaService $nanoBanana;

    protected array $styleConfig;

    public function __construct(NanoBananaService $nanoBanana)
    {
        $this->nanoBanana = $nanoBanana;
        $this->styleConfig = config('slides.biblical_image_style', []);
    }

    public function generateSlideImage(
        string $slideType,
        string $topic,
        ?string $additionalContext = null
    ): ?array {
        if (! $this->nanoBanana->isConfigured()) {
            Log::warning('NanoBanana not configured for slide image generation');

            return null;
        }

        $prompt = $this->buildPrompt($slideType, $topic, $additionalContext);

        Log::info('Generating slide image', [
            'type' => $slideType,
            'topic' => $topic,
            'prompt_length' => strlen($prompt),
        ]);

        $result = $this->nanoBanana->generateImageSync(
            prompt: $prompt,
            aspectRatio: '16:9',
            resolution: '2K',
            maxAttempts: 60,
            pollInterval: 3
        );

        if ($result['success']) {
            $localPath = $this->nanoBanana->downloadAndStore($result['image_url']);

            return [
                'success' => true,
                'remote_url' => $result['image_url'],
                'local_path' => $localPath,
                'task_id' => $result['task_id'] ?? null,
            ];
        }

        Log::error('Slide image generation failed', [
            'type' => $slideType,
            'topic' => $topic,
            'error' => $result['error'] ?? 'Unknown error',
        ]);

        return null;
    }

    public function startImageGeneration(string $slideType, string $topic, ?string $additionalContext = null): ?array
    {
        if (! $this->nanoBanana->isConfigured()) {
            return null;
        }

        $prompt = $this->buildPrompt($slideType, $topic, $additionalContext);

        return $this->nanoBanana->generateImage(
            prompt: $prompt,
            aspectRatio: '16:9',
            resolution: '2K'
        );
    }

    public function checkImageStatus(string $taskId): array
    {
        return $this->nanoBanana->getTaskStatus($taskId);
    }

    protected function buildPrompt(string $slideType, string $topic, ?string $additionalContext = null): string
    {
        $baseStyle = $this->styleConfig['base_style'] ?? $this->getDefaultBaseStyle();
        $typePrompt = $this->getTypePrompt($slideType);

        $typePrompt = str_replace('[TOPIC]', $topic, $typePrompt);

        $visualElements = $this->selectVisualElements($slideType, $topic);

        $prompt = $baseStyle."\n\n";
        $prompt .= $typePrompt."\n\n";
        $prompt .= 'Key visual elements: '.$visualElements."\n\n";

        if ($additionalContext) {
            $prompt .= "Context: {$additionalContext}\n\n";
        }

        $prompt .= 'Style: Warm sepia and gold tones, soft cream background, elegant serif typography placeholder areas, sacred Christian imagery, professional presentation quality. No text in the image.';

        return $prompt;
    }

    protected function getTypePrompt(string $slideType): string
    {
        $typePrompts = $this->styleConfig['slide_type_prompts'] ?? [];

        return $typePrompts[$slideType] ?? $typePrompts['content'] ?? $this->getDefaultTypePrompt($slideType);
    }

    protected function getDefaultTypePrompt(string $slideType): string
    {
        return match ($slideType) {
            'title' => 'Elegant title slide composition with central symbolic imagery. Sacred geometry frame with golden accents. Open Bible with glowing dove above. Professional presentation aesthetic with space for title text.',
            'scripture' => 'Scripture display composition. Open ancient Bible with glowing pages. Soft radiant light emanating upward. Olive branches framing. Reverent atmosphere with text space.',
            'diagram' => 'Theological concept visualization. Connected circles with golden sacred geometry. Clean layout with soft shadows. Cream background with sepia accents.',
            'two_column' => 'Split composition with balanced left and right areas. Decorative divider with olive branch motif. Warm sepia tones.',
            'quote' => 'Elegant quote frame design. Dove and Bible motif in corner. Decorative golden borders. Sophisticated layout with central text area.',
            'conclusion' => 'Call to action design. Magnifying glass over Bible symbolizing discovery. Golden light illumination. Inspiring composition.',
            default => 'Professional biblical presentation slide. Sacred imagery with elegant composition. Warm gold and cream tones. Clean layout with text space.',
        };
    }

    protected function selectVisualElements(string $slideType, string $topic): string
    {
        $elements = $this->styleConfig['visual_elements'] ?? $this->getDefaultVisualElements();
        $topicLower = strtolower($topic);

        $selected = [];

        if (str_contains($topicLower, 'spirit') || str_contains($topicLower, 'holy')) {
            $selected[] = $elements['dove'] ?? 'glowing white dove';
        }

        if (str_contains($topicLower, 'scripture') || str_contains($topicLower, 'word') || str_contains($topicLower, 'bible')) {
            $selected[] = $elements['bible'] ?? 'open Bible with golden glow';
        }

        if (str_contains($topicLower, 'law') || str_contains($topicLower, 'commandment') || str_contains($topicLower, 'covenant')) {
            $selected[] = $elements['tablets'] ?? 'stone tablets';
            $selected[] = $elements['scrolls'] ?? 'Hebrew scrolls';
        }

        if (str_contains($topicLower, 'king') || str_contains($topicLower, 'lord') || str_contains($topicLower, 'majesty')) {
            $selected[] = $elements['crown'] ?? 'golden crown';
        }

        if (str_contains($topicLower, 'peace') || str_contains($topicLower, 'blessing')) {
            $selected[] = $elements['olive_branch'] ?? 'olive branch';
        }

        if (str_contains($topicLower, 'cross') || str_contains($topicLower, 'christ') || str_contains($topicLower, 'salvation')) {
            $selected[] = $elements['cross'] ?? 'elegant cross with light';
        }

        if (str_contains($topicLower, 'light') || str_contains($topicLower, 'glory') || str_contains($topicLower, 'divine')) {
            $selected[] = $elements['light_rays'] ?? 'golden light rays';
        }

        if (empty($selected)) {
            $selected[] = $elements['bible'] ?? 'open Bible';
            $selected[] = $elements['sacred_geometry'] ?? 'sacred geometry patterns';
        }

        $selected[] = $elements['sacred_geometry'] ?? 'subtle geometric patterns';

        return implode(', ', array_unique($selected));
    }

    protected function getDefaultBaseStyle(): string
    {
        return 'Elegant theological illustration in warm sepia and gold tones. Soft cream background (#F5F0E8). Classical Christian art style with sacred geometry elements. Soft glowing light effects. Professional presentation quality. Clean composition with ample negative space.';
    }

    protected function getDefaultVisualElements(): array
    {
        return [
            'dove' => 'Glowing white dove with soft radiant light, symbol of Holy Spirit',
            'bible' => 'Open leather-bound Bible with golden glow from pages',
            'scrolls' => 'Ancient Hebrew parchment scrolls with calligraphy',
            'cross' => 'Elegant wooden cross with golden light rays',
            'sacred_geometry' => 'Subtle geometric patterns in gold',
            'light_rays' => 'Soft golden light rays from center',
            'olive_branch' => 'Delicate olive branch with leaves',
            'tablets' => 'Stone tablets with Hebrew inscriptions',
            'crown' => 'Golden crown with subtle glow',
        ];
    }

    public function getAvailableVisualElements(): array
    {
        return array_keys($this->styleConfig['visual_elements'] ?? $this->getDefaultVisualElements());
    }
}
