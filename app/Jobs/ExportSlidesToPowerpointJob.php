<?php

namespace App\Jobs;

use App\Models\Sermon;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Writer\PowerPoint2007;

class ExportSlidesToPowerpointJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public function __construct(
        public Sermon $sermon
    ) {}

    public function handle(): void
    {
        try {
            if ($this->sermon->slides->isEmpty()) {
                Notification::make()
                    ->title('No Slides to Export')
                    ->warning()
                    ->body('This sermon has no slides to export.')
                    ->send();

                return;
            }

            $presentation = new PhpPresentation;

            // Set document properties
            $presentation->getDocumentProperties()
                ->setCreator('PMA Sermon Maker')
                ->setTitle($this->sermon->title)
                ->setSubject('Sermon Slides')
                ->setDescription("Slides for sermon: {$this->sermon->title}");

            // Set slide dimensions (16:9 aspect ratio)
            $presentation->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9);

            // Remove the default first slide
            $presentation->removeSlideByIndex(0);

            // Add each slide
            foreach ($this->sermon->slides as $index => $sermonSlide) {
                $slide = $presentation->createSlide();

                // Set background
                $this->setSlideBackground($slide, $sermonSlide);

                // Add content
                $this->addSlideContent($slide, $sermonSlide);
            }

            // Save the presentation
            $filename = \Illuminate\Support\Str::slug($this->sermon->title).'-slides-'.now()->format('Y-m-d-His').'.pptx';
            $path = config('slides.export.storage_path').'/'.$filename;

            Storage::disk('local')->makeDirectory(config('slides.export.storage_path'));

            $writer = new PowerPoint2007($presentation);
            $writer->save(Storage::disk('local')->path($path));

            // Create a download URL (you may need to adjust this based on your storage setup)
            $downloadUrl = Storage::disk('local')->url($path);

            Notification::make()
                ->title('PowerPoint Export Complete')
                ->success()
                ->body("Your presentation has been generated: {$filename}")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->label('Download')
                        ->url($downloadUrl)
                        ->openUrlInNewTab(),
                ])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('PowerPoint Export Failed')
                ->danger()
                ->body('Error: '.$e->getMessage())
                ->send();

            throw $e;
        }
    }

    protected function setSlideBackground($slide, $sermonSlide): void
    {
        $background = $slide->getBackground();

        if ($sermonSlide->background_type === 'color') {
            $background->setType(\PhpOffice\PhpPresentation\Slide\Background::TYPE_COLOR);
            $background->setColor(new Color($this->hexToRgb($sermonSlide->background_value ?? '#FFFFFF')));
        } elseif ($sermonSlide->background_type === 'image' && $sermonSlide->background_value) {
            // For image backgrounds, we'll need to download and use the image
            // For now, fall back to a solid color
            $background->setType(\PhpOffice\PhpPresentation\Slide\Background::TYPE_COLOR);
            $background->setColor(new Color('FFFFFFFF'));
        } else {
            // Gradient backgrounds are complex in PHPPresentation, use solid color for now
            $theme = $sermonSlide->metadata['theme'] ?? config('slides.default_theme');
            $themeConfig = config("slides.themes.{$theme}");
            $background->setType(\PhpOffice\PhpPresentation\Slide\Background::TYPE_COLOR);
            $background->setColor(new Color($this->hexToRgb($themeConfig['primary'] ?? '#1e3a8a')));
        }
    }

    protected function addSlideContent($slide, $sermonSlide): void
    {
        // Parse HTML content to extract text
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$sermonSlide->html_content);

        $theme = $sermonSlide->metadata['theme'] ?? config('slides.default_theme');
        $themeConfig = config("slides.themes.{$theme}");

        $yPosition = 100;
        $xPosition = 100;
        $width = 1720; // 1920 - 2*100 margin
        $maxHeight = 880; // 1080 - 2*100 margin

        // Add Unsplash attribution if image background is used
        $imageAttribution = $sermonSlide->metadata['image_attribution'] ?? null;

        // Extract headings and paragraphs
        $elements = [];

        // Get h1 elements
        $h1Tags = $dom->getElementsByTagName('h1');
        foreach ($h1Tags as $h1) {
            $elements[] = [
                'type' => 'h1',
                'text' => trim($h1->textContent),
            ];
        }

        // Get h2 elements
        $h2Tags = $dom->getElementsByTagName('h2');
        foreach ($h2Tags as $h2) {
            $elements[] = [
                'type' => 'h2',
                'text' => trim($h2->textContent),
            ];
        }

        // Get paragraphs
        $pTags = $dom->getElementsByTagName('p');
        foreach ($pTags as $p) {
            $text = trim($p->textContent);
            if (! empty($text)) {
                $elements[] = [
                    'type' => 'p',
                    'text' => $text,
                ];
            }
        }

        // Get list items
        $liTags = $dom->getElementsByTagName('li');
        if ($liTags->length > 0) {
            $bulletPoints = [];
            foreach ($liTags as $li) {
                $bulletPoints[] = trim($li->textContent);
            }
            $elements[] = [
                'type' => 'list',
                'items' => $bulletPoints,
            ];
        }

        // Add elements to slide
        foreach ($elements as $element) {
            if ($element['type'] === 'h1') {
                $shape = $slide->createRichTextShape();
                $shape->setHeight(120);
                $shape->setWidth($width);
                $shape->setOffsetX($xPosition);
                $shape->setOffsetY($yPosition);

                $textRun = $shape->createTextRun($element['text']);
                $textRun->getFont()
                    ->setSize(72)
                    ->setBold(true)
                    ->setColor(new Color($this->hexToRgb($themeConfig['text'] ?? '#FFFFFF')));

                $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $yPosition += 140;
            } elseif ($element['type'] === 'h2') {
                $shape = $slide->createRichTextShape();
                $shape->setHeight(80);
                $shape->setWidth($width);
                $shape->setOffsetX($xPosition);
                $shape->setOffsetY($yPosition);

                $textRun = $shape->createTextRun($element['text']);
                $textRun->getFont()
                    ->setSize(36)
                    ->setBold(true)
                    ->setColor(new Color($this->hexToRgb($themeConfig['text'] ?? '#FFFFFF')));

                $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $yPosition += 100;
            } elseif ($element['type'] === 'p') {
                $shape = $slide->createRichTextShape();
                $shape->setHeight(60);
                $shape->setWidth($width);
                $shape->setOffsetX($xPosition);
                $shape->setOffsetY($yPosition);

                $textRun = $shape->createTextRun($element['text']);
                $textRun->getFont()
                    ->setSize(32)
                    ->setColor(new Color($this->hexToRgb($themeConfig['text'] ?? '#FFFFFF')));

                $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $yPosition += 80;
            } elseif ($element['type'] === 'list') {
                $shape = $slide->createRichTextShape();
                $shape->setHeight(count($element['items']) * 60);
                $shape->setWidth($width - 100);
                $shape->setOffsetX($xPosition + 50);
                $shape->setOffsetY($yPosition);

                foreach ($element['items'] as $item) {
                    $textRun = $shape->createTextRun('• '.$item);
                    $textRun->getFont()
                        ->setSize(32)
                        ->setColor(new Color($this->hexToRgb($themeConfig['text'] ?? '#FFFFFF')));

                    $shape->createBreak();
                }

                $yPosition += (count($element['items']) * 60) + 40;
            }
        }

        // Add Unsplash attribution at bottom if image was used
        if ($imageAttribution && $sermonSlide->background_type === 'image') {
            $attributionText = "Photo by {$imageAttribution['photographer_name']} on Unsplash";

            $shape = $slide->createRichTextShape();
            $shape->setHeight(30);
            $shape->setWidth($width);
            $shape->setOffsetX($xPosition);
            $shape->setOffsetY(1020); // Near bottom

            $textRun = $shape->createTextRun($attributionText);
            $textRun->getFont()
                ->setSize(10)
                ->setColor(new Color('FFFFFFFF'));

            $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
    }

    protected function hexToRgb(string $hex): string
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Convert to RGB
        if (strlen($hex) === 6) {
            return 'FF'.strtoupper($hex);
        } elseif (strlen($hex) === 3) {
            $r = str_repeat($hex[0], 2);
            $g = str_repeat($hex[1], 2);
            $b = str_repeat($hex[2], 2);

            return 'FF'.strtoupper($r.$g.$b);
        }

        return 'FFFFFFFF'; // Default to white if invalid
    }
}
