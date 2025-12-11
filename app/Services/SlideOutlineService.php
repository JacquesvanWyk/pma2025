<?php

namespace App\Services;

use Prism\Prism\Prism;

class SlideOutlineService
{
    public function generateOutline(string $sourceText): array
    {
        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($sourceText);

        $modelConfig = config('study-ai.models.text');

        $response = Prism::text()
            ->using($modelConfig['provider'], $modelConfig['model'])
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($userPrompt)
            ->withMaxTokens(8000)
            ->usingTemperature(0.7)
            ->withClientOptions([
                'timeout' => 180,
                'connect_timeout' => 30,
            ])
            ->generate();

        return $this->parseResponse($response->text);
    }

    protected function buildSystemPrompt(): string
    {
        return <<<'PROMPT'
You are a VISUAL THEOLOGIAN and INFORMATION DESIGNER for a NON-TRINITARIAN ADVENTIST ministry.

=== THE GOLDEN RULE ===
Do NOT just decorate with text on backgrounds. TRANSLATE concepts into DIAGRAMS.
- If content discusses a CONTRAST → create a SPLIT-SCREEN
- If content discusses a PROCESS or EXCHANGE → create a FLOW DIAGRAM with arrows
- If content discusses a LIST → create a MODULAR GRID of cards
- If content uses DATA or PERCENTAGES → create an INFOGRAPHIC

=== THEOLOGICAL FRAMEWORK ===
This ministry teaches:
- The Holy Spirit is the shared divine presence of the Father AND the Son (NOT a separate third person)
- There are TWO divine beings: God the Father and Jesus Christ His Son
- The Spirit is HOW they are present with us - their omnipresent influence

NEVER USE: "Trinity", "Triune", "Third Person", "three persons", "God the Holy Spirit" as separate being
USE: "Spirit of God", "Spirit of Christ", "Father and Son", "Their shared presence"

=== ATMOSPHERIC DESIGN (Mood-Based Colors) ===
Analyze the emotional/theological tone of the content to determine the palette:

HISTORIC/SCRIPTURAL topics → "Parchment & Ink" aesthetic (creams, browns, golds, aged textures)
DIVINE/COSMIC/ROYAL topics (Cross, Heaven, Glory) → "Celestial Depth" (deep royals, midnight blues, glowing whites, soft gold)
WARNING/JUDGMENT topics → "Shadow & Flame" (dark greys, muted reds, somber tones)
TEACHING/EXPLANATION topics → "Scholar's Study" (navy, cream, warm wood tones)
HOPE/PROMISE topics → "Dawn Light" (soft golds, warm whites, gentle gradients)

=== STRUCTURAL ARCHETYPES (Required for each slide) ===
Identify the LOGICAL STRUCTURE of each slide's content and assign ONE of these layouts:

1. THE SPLIT-CONTRAST
   For: Comparing two things (Human vs Divine, Old vs New, Error vs Truth)
   Layout: Split screen with distinct iconography on left and right
   Example: Left = gears/brain (human), Right = light/dove (divine), Center = unifying element

2. THE TRANSACTIONAL FLOW
   For: Exchanges, transformations, processes (Great Exchange, Salvation, Sanctification)
   Layout: Left-to-right diagram showing state change with arrows
   Example: [SIN] → through Cross → [RIGHTEOUSNESS]

3. THE MODULAR GRID
   For: Lists, evidence, multiple points (5 witnesses, 7 churches, steps)
   Layout: Separate bordered cards/containers for each point with icons
   Example: 5 cards arranged in a row or grid, each with icon + label

4. THE DATA METAPHOR
   For: Statistics, percentages, proportions, limits
   Layout: Pie charts, bar graphs, visual representations of data
   Example: 5% knowledge shown as small slice of pie

5. THE SCRIPTURE SPOTLIGHT
   For: Key verses, biblical quotes
   Layout: Large quote text with visual context (scroll, open Bible, ancient manuscript)
   Include annotations pointing to key words

6. THE CONCEPT MAP
   For: Relationships, connections, theological concepts
   Layout: Central idea with branching explanations and connecting lines

=== FORBIDDEN IMAGERY ===
NEVER USE: Interlocking circles, triangles, sacred geometry, swirling mist, glowing orbs, three-in-one symbols, halos, mystical patterns, occult elements

USE INSTEAD: Open Bibles, simple dove, light rays, nature (mountains, water, olive branches), clean diagrams, human silhouettes, simple wooden cross

=== OUTPUT FORMAT (JSON) ===
{
  "title": "Presentation Title",
  "mood": "celestial-depth|parchment-ink|shadow-flame|scholars-study|dawn-light",
  "slides": [
    {
      "number": 1,
      "title": "Slide Title",
      "archetype": "title|split-contrast|transactional-flow|modular-grid|data-metaphor|scripture-spotlight|concept-map|conclusion",
      "summary": "Brief description for outline review",
      "image_prompt": "DETAILED 200-400 word prompt describing: the archetype layout, all visual elements, exact diagram structure with positions, exact text to render, mood-appropriate colors, iconography"
    }
  ]
}

=== SLIDE RULES ===
- Create 6-12 slides based on content depth
- First slide: Title slide with topic and subtitle
- NEVER use the same archetype twice in a row
- The viewer should understand the concept just by looking at the images
- EVERY image_prompt must specify exact text to render
- Make it EDUCATIONAL - diagrams that TEACH, not just decorate
PROMPT;
    }

    protected function getStyleGuide(string $style): string
    {
        return match ($style) {
            'biblical-classic' => 'STYLE GUIDE - Biblical Classic:
- Background: Dark navy blue gradient with subtle ancient scripture texture
- Accent colors: Golden (#D4AF37), cream (#F5F0E8), warm amber
- Typography: Elegant serif fonts (Cinzel, Playfair Display style) in gold and cream
- Icons: Sacred geometry, glowing orbs, dove, cross, olive branches, scrolls
- Aesthetic: Reverent, scholarly, majestic
- Illustrations: Use glowing elements, sacred symbols, comparison diagrams
- Include faint scripture text in background for texture
- All text must be crisp and readable against the dark background',
            'modern-ministry' => 'STYLE GUIDE - Modern Ministry:
- Background: Clean gradients (teal to blue, or warm earth tones)
- Accent colors: White, soft gold, teal accents
- Typography: Clean sans-serif fonts in white/light colors
- Icons: Minimalist line icons, simple shapes
- Aesthetic: Contemporary, approachable, clean
- Illustrations: Simple diagrams, clean infographics
- All text must be bold and highly readable',
            'warm-sepia' => 'STYLE GUIDE - Warm Sepia:
- Background: Soft cream (#F5F0E8) with parchment texture
- Accent colors: Sepia brown (#704214), gold, dark brown (#4A3C2A)
- Typography: Classic serif fonts in dark brown
- Icons: Vintage illustrations, hand-drawn style elements
- Aesthetic: Warm, traditional, scholarly
- Illustrations: Classic artistic style, warm lighting',
            default => 'STYLE GUIDE - Biblical Classic:
- Background: Dark navy blue gradient with subtle ancient scripture texture
- Accent colors: Golden (#D4AF37), cream (#F5F0E8), warm amber
- Typography: Elegant serif fonts (Cinzel, Playfair Display style) in gold and cream
- Icons: Sacred geometry, glowing orbs, dove, cross, olive branches, scrolls
- Aesthetic: Reverent, scholarly, majestic
- Illustrations: Use glowing elements, sacred symbols, comparison diagrams
- Include faint scripture text in background for texture
- All text must be crisp and readable against the dark background',
        };
    }

    protected function buildUserPrompt(string $sourceText): string
    {
        return <<<PROMPT
Create a slide deck from this study. The viewer should understand the concepts just by looking at the images.

CONTENT:
{$sourceText}

INSTRUCTIONS:
1. Analyze the theological/emotional MOOD of the content to set the color palette
2. For EACH slide, identify its LOGICAL STRUCTURE:
   - Comparing two things? → Use SPLIT-CONTRAST (split screen with icons on each side)
   - Explaining a trade/exchange? → Use TRANSACTIONAL FLOW (arrows showing transformation)
   - Listing evidence/points? → Use MODULAR GRID (cards with icons)
   - Using statistics/percentages? → Use DATA METAPHOR (charts, infographics)
   - Featuring scripture? → Use SCRIPTURE SPOTLIGHT (large quote with visual context)

3. NEVER just put text on a background - every slide must TRANSLATE the concept into a DIAGRAM

4. Theological requirement: Present the Spirit as the shared presence of Father and Son (TWO beings, not three). Never use "Trinity" or "third person" language.

5. Include EXACT text to render in each image prompt

GOAL: Premium, atmospheric, cinematic documentary feel - diagrams that TEACH theology visually.
PROMPT;
    }

    protected function parseResponse(string $response): array
    {
        $jsonStart = strpos($response, '{');
        $jsonEnd = strrpos($response, '}');

        if ($jsonStart === false || $jsonEnd === false) {
            throw new \Exception('Failed to parse outline response - no JSON found');
        }

        $json = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse outline JSON: '.json_last_error_msg());
        }

        return $data;
    }
}
