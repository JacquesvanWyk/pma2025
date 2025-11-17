<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;

class SlideExportService
{
    public function exportToPowerPoint(array $slides, ?int $sermonId = null): string
    {
        $presentation = new PhpPresentation;
        $presentation->getLayout()->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9);

        // Remove default slide
        $presentation->removeSlideByIndex(0);

        foreach ($slides as $slideData) {
            $slide = $presentation->createSlide();

            // Parse background color from gradient or solid color
            $backgroundType = $slideData['background_type'] ?? 'gradient';
            $backgroundValue = $slideData['background_value'] ?? 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)';

            $hexColor = $this->extractColorFromGradient($backgroundValue);

            // Create a fill shape that covers the entire slide as background
            $background = $slide->createRichTextShape();
            $background->setWidth(960)->setHeight(540)->setOffsetX(0)->setOffsetY(0);

            $backgroundFill = $background->getFill();
            $backgroundFill->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID);

            $bgColor = new Color;
            $bgColor->setRGB($hexColor);
            $backgroundFill->setStartColor($bgColor);

            // Parse HTML content and add to slide
            $htmlContent = $slideData['html_content'] ?? '';
            $this->addHtmlContentToSlide($slide, $htmlContent);
        }

        // Save to temporary file
        $fileName = $sermonId
            ? "sermon_{$sermonId}_slides_".time().'.pptx'
            : 'slides_'.time().'.pptx';

        $tempPath = storage_path('app/temp/'.$fileName);

        if (! file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
        $writer->save($tempPath);

        return $tempPath;
    }

    public function exportToPdf(array $slides, ?int $sermonId = null): string
    {
        $fileName = $sermonId
            ? "sermon_{$sermonId}_slides_".time().'.pdf'
            : 'slides_'.time().'.pdf';

        $tempPath = storage_path('app/temp/'.$fileName);

        if (! file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        // Use DomPDF instead of Browsershot
        $pdf = DomPdf::loadView('pdf.slides', ['slides' => $slides])
            ->setPaper('a4', 'landscape');

        $pdf->save($tempPath);

        return $tempPath;
    }

    protected function extractColorFromGradient(string $gradient): string
    {
        // Extract first hex color from gradient string
        if (preg_match('/#([0-9a-fA-F]{6})/', $gradient, $matches)) {
            return $matches[1];
        }

        return '1e3a8a'; // Default blue
    }

    protected function addHtmlContentToSlide($slide, string $html): void
    {
        // Create a text shape that fills most of the slide
        $shape = $slide->createRichTextShape()
            ->setHeight(500)
            ->setWidth(900)
            ->setOffsetX(60)
            ->setOffsetY(90);

        $shape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $whiteColor = new Color;
        $whiteColor->setRGB('FFFFFF');

        // Parse HTML structure to extract headings and content
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $html, $h1)) {
            $textRun = $shape->createTextRun(strip_tags($h1[1]));
            $textRun->getFont()
                ->setSize(54)
                ->setBold(true)
                ->setColor($whiteColor);
        } elseif (preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $html, $h2)) {
            $textRun = $shape->createTextRun(strip_tags($h2[1]));
            $textRun->getFont()
                ->setSize(44)
                ->setBold(true)
                ->setColor($whiteColor);

            // Add content if exists
            if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $html, $p)) {
                $shape->createBreak();
                $shape->createBreak();
                $contentRun = $shape->createTextRun(strip_tags($p[1]));
                $contentRun->getFont()
                    ->setSize(28)
                    ->setColor($whiteColor);
            }

            // Add list items if exist
            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $lis)) {
                foreach ($lis[1] as $li) {
                    $shape->createBreak();
                    $liRun = $shape->createTextRun('• '.strip_tags($li));
                    $liRun->getFont()
                        ->setSize(28)
                        ->setColor($whiteColor);
                }
            }
        } elseif (preg_match('/<blockquote[^>]*>(.*?)<\/blockquote>/is', $html, $quote)) {
            $textRun = $shape->createTextRun('"'.strip_tags($quote[1]).'"');
            $textRun->getFont()
                ->setSize(36)
                ->setColor($whiteColor);

            if (preg_match('/<cite[^>]*>(.*?)<\/cite>/is', $html, $cite)) {
                $shape->createBreak();
                $shape->createBreak();
                $citeRun = $shape->createTextRun('— '.strip_tags($cite[1]));
                $citeRun->getFont()
                    ->setSize(24)
                    ->setColor($whiteColor);
            }
        } else {
            // Fallback: just add all text
            $text = strip_tags($html);
            $textRun = $shape->createTextRun(substr($text, 0, 500));
            $textRun->getFont()
                ->setSize(32)
                ->setColor($whiteColor);
        }
    }
}
