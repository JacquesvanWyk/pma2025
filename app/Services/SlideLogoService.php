<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SlideLogoService
{
    protected string $logoPath = 'images/PMALogoWhiteText.png';

    protected int $logoWidth = 150;

    protected int $padding = 20;

    protected string $position = 'bottom-right';

    public function addLogoToSlide(string $slidePath): ?string
    {
        $fullPath = Storage::disk('public')->path($slidePath);

        if (! file_exists($fullPath)) {
            return null;
        }

        $logoFullPath = public_path($this->logoPath);

        if (! file_exists($logoFullPath)) {
            $logoFullPath = Storage::disk('public')->path('logo-white.png');
            if (! file_exists($logoFullPath)) {
                return $slidePath;
            }
        }

        $slide = Image::read($fullPath);
        $logo = Image::read($logoFullPath);

        $aspectRatio = $logo->height() / $logo->width();
        $logoHeight = (int) ($this->logoWidth * $aspectRatio);
        $logo->resize($this->logoWidth, $logoHeight);

        $slideWidth = $slide->width();
        $slideHeight = $slide->height();

        [$x, $y] = match ($this->position) {
            'top-left' => [$this->padding, $this->padding],
            'top-right' => [$slideWidth - $this->logoWidth - $this->padding, $this->padding],
            'bottom-left' => [$this->padding, $slideHeight - $logoHeight - $this->padding],
            'bottom-right' => [$slideWidth - $this->logoWidth - $this->padding, $slideHeight - $logoHeight - $this->padding],
            'center' => [($slideWidth - $this->logoWidth) / 2, ($slideHeight - $logoHeight) / 2],
            default => [$slideWidth - $this->logoWidth - $this->padding, $slideHeight - $logoHeight - $this->padding],
        };

        $slide->place($logo, 'top-left', $x, $y);

        $outputDir = 'generated/slides/with-logo';
        Storage::disk('public')->makeDirectory($outputDir);

        $filename = pathinfo($slidePath, PATHINFO_FILENAME).'_with_logo.png';
        $outputPath = $outputDir.'/'.$filename;

        $slide->save(Storage::disk('public')->path($outputPath));

        return $outputPath;
    }

    public function setLogoPath(string $path): self
    {
        $this->logoPath = $path;

        return $this;
    }

    public function setLogoWidth(int $width): self
    {
        $this->logoWidth = $width;

        return $this;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setPadding(int $padding): self
    {
        $this->padding = $padding;

        return $this;
    }
}
