<?php

namespace App\Services;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Schema\StringSchema;

class SlideBuilderService
{
    protected array $slides = [];

    protected int $currentSlideIndex = -1;

    protected array $themeConfig;

    protected ?SlideImageService $imageService = null;

    protected array $pendingImageTasks = [];

    public function __construct(array $themeConfig, ?SlideImageService $imageService = null)
    {
        $this->themeConfig = $themeConfig;
        $this->imageService = $imageService;
    }

    public function getTools(): array
    {
        return [
            Tool::as('createSlide')
                ->for('Create a new slide with specified type and background')
                ->withEnumParameter('type', 'Type of slide', ['title', 'content', 'scripture', 'quote', 'points'])
                ->withStringParameter('background', 'CSS gradient background (e.g., linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%))')
                ->using(function (string $type, string $background): string {
                    $this->currentSlideIndex++;
                    $this->slides[$this->currentSlideIndex] = [
                        'slide_number' => $this->currentSlideIndex + 1,
                        'type' => $type,
                        'background' => $background,
                        'elements' => [],
                    ];

                    return "Slide {$this->currentSlideIndex} created with type '{$type}'";
                }),

            Tool::as('addHeading')
                ->for('Add a heading (h1 or h2) to the current slide')
                ->withEnumParameter('level', 'Heading level', ['h1', 'h2'])
                ->withStringParameter('text', 'The heading text')
                ->withStringParameter('fontSize', 'Font size in pixels (e.g., "64px")')
                ->withStringParameter('color', 'Text color in hex (e.g., "#FFFFFF")')
                ->using(function (string $level, string $text, string $fontSize, string $color): string {
                    if ($this->currentSlideIndex < 0) {
                        return 'Error: No slide created yet. Call createSlide first.';
                    }

                    $this->slides[$this->currentSlideIndex]['elements'][] = [
                        'type' => 'heading',
                        'level' => $level,
                        'text' => $text,
                        'fontSize' => $fontSize,
                        'color' => $color,
                    ];

                    return "Added {$level} heading to slide {$this->currentSlideIndex}";
                }),

            Tool::as('addText')
                ->for('Add paragraph text to the current slide')
                ->withStringParameter('text', 'The paragraph text content')
                ->withStringParameter('fontSize', 'Font size in pixels (e.g., "28px")')
                ->withStringParameter('color', 'Text color in hex (e.g., "#FFFFFF")')
                ->using(function (string $text, string $fontSize, string $color): string {
                    if ($this->currentSlideIndex < 0) {
                        return 'Error: No slide created yet. Call createSlide first.';
                    }

                    $this->slides[$this->currentSlideIndex]['elements'][] = [
                        'type' => 'text',
                        'text' => $text,
                        'fontSize' => $fontSize,
                        'color' => $color,
                    ];

                    return "Added text to slide {$this->currentSlideIndex}";
                }),

            Tool::as('addBulletPoints')
                ->for('Add a list of bullet points to the current slide')
                ->withArrayParameter('points', 'Array of bullet point strings', new StringSchema('point', 'A single bullet point text'))
                ->withStringParameter('fontSize', 'Font size in pixels (e.g., "28px")')
                ->withStringParameter('color', 'Text color in hex (e.g., "#FFFFFF")')
                ->using(function (array $points, string $fontSize, string $color): string {
                    if ($this->currentSlideIndex < 0) {
                        return 'Error: No slide created yet. Call createSlide first.';
                    }

                    $this->slides[$this->currentSlideIndex]['elements'][] = [
                        'type' => 'bullets',
                        'points' => $points,
                        'fontSize' => $fontSize,
                        'color' => $color,
                    ];

                    return 'Added '.count($points)." bullet points to slide {$this->currentSlideIndex}";
                }),

            Tool::as('addQuote')
                ->for('Add a quote with optional citation to the current slide')
                ->withStringParameter('quote', 'The quote text')
                ->withStringParameter('citation', 'Citation or author (optional)', required: false)
                ->withStringParameter('fontSize', 'Font size in pixels (e.g., "36px")')
                ->withStringParameter('color', 'Text color in hex (e.g., "#FFFFFF")')
                ->using(function (string $quote, ?string $citation, string $fontSize, string $color): string {
                    if ($this->currentSlideIndex < 0) {
                        return 'Error: No slide created yet. Call createSlide first.';
                    }

                    $this->slides[$this->currentSlideIndex]['elements'][] = [
                        'type' => 'quote',
                        'quote' => $quote,
                        'citation' => $citation,
                        'fontSize' => $fontSize,
                        'color' => $color,
                    ];

                    return "Added quote to slide {$this->currentSlideIndex}";
                }),

            Tool::as('generateBackgroundImage')
                ->for('Generate an AI background image for the current slide using biblical imagery style')
                ->withEnumParameter('slideType', 'Type of slide for image style', ['title', 'scripture', 'content', 'diagram', 'two_column', 'quote', 'conclusion'])
                ->withStringParameter('topic', 'Main topic or theme for the image (e.g., "Holy Spirit", "God\'s Love", "Prayer")')
                ->withStringParameter('context', 'Additional context for image generation (optional)', required: false)
                ->using(function (string $slideType, string $topic, ?string $context = null): string {
                    if ($this->currentSlideIndex < 0) {
                        return 'Error: No slide created yet. Call createSlide first.';
                    }

                    if (! $this->imageService) {
                        return 'AI image generation not available. Using gradient background.';
                    }

                    $result = $this->imageService->startImageGeneration($slideType, $topic, $context);

                    if ($result && $result['success']) {
                        $this->slides[$this->currentSlideIndex]['pending_image'] = [
                            'task_id' => $result['task_id'],
                            'slide_type' => $slideType,
                            'topic' => $topic,
                        ];
                        $this->pendingImageTasks[$this->currentSlideIndex] = $result['task_id'];

                        return "AI image generation started for slide {$this->currentSlideIndex} with topic '{$topic}'. Task ID: {$result['task_id']}";
                    }

                    return 'Failed to start image generation: '.($result['error'] ?? 'Unknown error');
                }),
        ];
    }

    public function getPendingImageTasks(): array
    {
        return $this->pendingImageTasks;
    }

    public function updateSlideWithImage(int $slideIndex, string $imageUrl, ?string $localPath = null): void
    {
        if (isset($this->slides[$slideIndex])) {
            $this->slides[$slideIndex]['background_image'] = $imageUrl;
            $this->slides[$slideIndex]['background_image_local'] = $localPath;
            unset($this->slides[$slideIndex]['pending_image']);
        }
    }

    public function buildHtml(): array
    {
        $htmlSlides = [];

        foreach ($this->slides as $slide) {
            $html = $this->generateSlideHtml($slide);

            $backgroundType = 'gradient';
            $backgroundValue = $slide['background'];

            if (! empty($slide['background_image'])) {
                $backgroundType = 'image';
                $backgroundValue = $slide['background_image'];
            }

            $htmlSlides[] = [
                'slide_number' => $slide['slide_number'],
                'type' => $slide['type'],
                'html_content' => $html,
                'background_type' => $backgroundType,
                'background_value' => $backgroundValue,
                'background_image_local' => $slide['background_image_local'] ?? null,
                'pending_image' => $slide['pending_image'] ?? null,
            ];
        }

        return $htmlSlides;
    }

    protected function generateSlideHtml(array $slide): string
    {
        $background = $slide['background'];
        $hasImage = ! empty($slide['background_image']);
        $imageUrl = $slide['background_image'] ?? '';

        $useBiblicalTheme = ($this->themeConfig['use_ai_images'] ?? false) || $hasImage;
        $fontFamily = $useBiblicalTheme ? "'Playfair Display', 'Georgia', serif" : "'Source Sans Pro', sans-serif";
        $textColor = $useBiblicalTheme ? '#4A3C2A' : '#FFFFFF';
        $fontUrl = $useBiblicalTheme
            ? 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap'
            : 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap';

        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang=\"en\">\n";
        $html .= "<head>\n";
        $html .= "    <meta charset=\"UTF-8\">\n";
        $html .= "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        $html .= "    <title>Slide {$slide['slide_number']}</title>\n";
        $html .= "    <link href=\"{$fontUrl}\" rel=\"stylesheet\">\n";
        $html .= "    <style>\n";
        $html .= "        * { margin: 0; padding: 0; box-sizing: border-box; }\n";
        $html .= "        html { font-size: calc(100vw / 120); }\n";
        $html .= "        html, body { width: 100%; height: 100%; overflow: hidden; }\n";
        $html .= "        body { font-family: {$fontFamily}; }\n";
        $html .= "        .slide { width: 100%; height: 100%; position: relative; overflow: hidden; }\n";

        if ($hasImage) {
            $html .= "        .background { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('{$imageUrl}'); background-size: cover; background-position: center; }\n";
        } else {
            $html .= "        .background { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: {$background}; }\n";
        }

        $html .= "        .content { position: relative; z-index: 10; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 4rem 6rem; text-align: center; word-wrap: break-word; }\n";

        if ($useBiblicalTheme) {
            $html .= "        h1, h2 { font-weight: 700; margin-bottom: 1rem; text-shadow: 0 1px 2px rgba(255,255,255,0.5); line-height: 1.2; color: {$textColor}; }\n";
            $html .= "        p { line-height: 1.6; max-width: 90%; margin: 0 auto 1rem; word-wrap: break-word; color: {$textColor}; }\n";
            $html .= "        ul { list-style: none; max-width: 80%; margin: 0 auto; text-align: left; color: {$textColor}; }\n";
        } else {
            $html .= "        h1, h2 { font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3); line-height: 1.2; }\n";
            $html .= "        p { line-height: 1.6; max-width: 90%; margin: 0 auto 1rem; word-wrap: break-word; }\n";
            $html .= "        ul { list-style: none; max-width: 80%; margin: 0 auto; text-align: left; }\n";
        }

        $html .= "        li { margin-bottom: 0.8rem; padding-left: 2rem; position: relative; line-height: 1.5; }\n";
        $html .= "        li:before { content: '•'; position: absolute; left: 0; }\n";
        $html .= "        .quote { font-style: italic; margin-bottom: 1rem; }\n";
        $html .= "        .citation { font-size: 0.8em; opacity: 0.9; }\n";
        $html .= "    </style>\n";
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= "    <div class=\"slide\">\n";
        $html .= "        <div class=\"background\"></div>\n";
        $html .= "        <div class=\"content\">\n";

        foreach ($slide['elements'] as $element) {
            $html .= $this->generateElementHtml($element);
        }

        $html .= "        </div>\n";
        $html .= "    </div>\n";
        $html .= "</body>\n";
        $html .= '</html>';

        return $html;
    }

    protected function generateElementHtml(array $element): string
    {
        $html = '';

        switch ($element['type']) {
            case 'heading':
                $level = $element['level'];
                $text = htmlspecialchars($element['text']);
                $fontSize = $this->convertToResponsiveSize($element['fontSize']);
                $color = $element['color'];
                $html .= "            <{$level} style=\"font-size: {$fontSize}; color: {$color};\">{$text}</{$level}>\n";
                break;

            case 'text':
                $text = htmlspecialchars($element['text']);
                $fontSize = $this->convertToResponsiveSize($element['fontSize']);
                $color = $element['color'];
                $html .= "            <p style=\"font-size: {$fontSize}; color: {$color};\">{$text}</p>\n";
                break;

            case 'bullets':
                $fontSize = $this->convertToResponsiveSize($element['fontSize']);
                $color = $element['color'];
                $html .= "            <ul style=\"font-size: {$fontSize}; color: {$color};\">\n";
                foreach ($element['points'] as $point) {
                    $pointText = htmlspecialchars($point);
                    $html .= "                <li>{$pointText}</li>\n";
                }
                $html .= "            </ul>\n";
                break;

            case 'quote':
                $quote = htmlspecialchars($element['quote']);
                $citation = $element['citation'] ? htmlspecialchars($element['citation']) : null;
                $fontSize = $this->convertToResponsiveSize($element['fontSize']);
                $color = $element['color'];
                $html .= "            <p class=\"quote\" style=\"font-size: {$fontSize}; color: {$color};\">\"{$quote}\"</p>\n";
                if ($citation) {
                    $html .= "            <p class=\"citation\" style=\"color: {$color};\">— {$citation}</p>\n";
                }
                break;
        }

        return $html;
    }

    protected function convertToResponsiveSize(string $size): string
    {
        // Convert pixel values to rem (base is 1280px / 120 = ~10.67px per rem at full size)
        // This scales proportionally with viewport width via calc(100vw / 120)
        if (preg_match('/(\d+)px/', $size, $matches)) {
            $pixels = (int) $matches[1];
            $rem = round($pixels / 10.67, 2);

            return "{$rem}rem";
        }

        return $size;
    }

    public function getSlides(): array
    {
        return $this->slides;
    }
}
