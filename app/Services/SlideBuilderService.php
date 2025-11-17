<?php

namespace App\Services;

use Prism\Prism\Facades\Tool;
use Prism\Prism\Schema\StringSchema;

class SlideBuilderService
{
    protected array $slides = [];

    protected int $currentSlideIndex = -1;

    protected array $themeConfig;

    public function __construct(array $themeConfig)
    {
        $this->themeConfig = $themeConfig;
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
        ];
    }

    public function buildHtml(): array
    {
        $htmlSlides = [];

        foreach ($this->slides as $slide) {
            $html = $this->generateSlideHtml($slide);
            $htmlSlides[] = [
                'slide_number' => $slide['slide_number'],
                'type' => $slide['type'],
                'html_content' => $html,
                'background_type' => 'gradient',
                'background_value' => $slide['background'],
            ];
        }

        return $htmlSlides;
    }

    protected function generateSlideHtml(array $slide): string
    {
        $background = $slide['background'];

        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang=\"en\">\n";
        $html .= "<head>\n";
        $html .= "    <meta charset=\"UTF-8\">\n";
        $html .= "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        $html .= "    <title>Slide {$slide['slide_number']}</title>\n";
        $html .= "    <link href=\"https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap\" rel=\"stylesheet\">\n";
        $html .= "    <style>\n";
        $html .= "        * { margin: 0; padding: 0; box-sizing: border-box; }\n";
        $html .= "        html { font-size: calc(100vw / 120); }\n"; // Base font size scales with viewport
        $html .= "        html, body { width: 100%; height: 100%; overflow: hidden; }\n";
        $html .= "        body { font-family: 'Source Sans Pro', sans-serif; }\n";
        $html .= "        .slide { width: 100%; height: 100%; position: relative; overflow: hidden; }\n";
        $html .= "        .background { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: {$background}; }\n";
        $html .= "        .content { position: relative; z-index: 10; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 4rem 6rem; text-align: center; word-wrap: break-word; }\n";
        $html .= "        h1, h2 { font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3); line-height: 1.2; }\n";
        $html .= "        p { line-height: 1.6; max-width: 90%; margin: 0 auto 1rem; word-wrap: break-word; }\n";
        $html .= "        ul { list-style: none; max-width: 80%; margin: 0 auto; text-align: left; }\n";
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
