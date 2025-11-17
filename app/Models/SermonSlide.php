<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'sermon_id',
        'slide_number',
        'slide_type',
        'html_content',
        'css_styles',
        'background_type',
        'background_value',
        'ai_prompt_history',
        'metadata',
    ];

    protected $casts = [
        'slide_number' => 'integer',
        'ai_prompt_history' => 'array',
        'metadata' => 'array',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function moveUp(): bool
    {
        if ($this->slide_number <= 1) {
            return false;
        }

        $previousSlide = static::where('sermon_id', $this->sermon_id)
            ->where('slide_number', $this->slide_number - 1)
            ->first();

        if ($previousSlide) {
            $previousSlide->update(['slide_number' => $this->slide_number]);
            $this->update(['slide_number' => $this->slide_number - 1]);

            return true;
        }

        return false;
    }

    public function moveDown(): bool
    {
        $nextSlide = static::where('sermon_id', $this->sermon_id)
            ->where('slide_number', $this->slide_number + 1)
            ->first();

        if ($nextSlide) {
            $nextSlide->update(['slide_number' => $this->slide_number]);
            $this->update(['slide_number' => $this->slide_number + 1]);

            return true;
        }

        return false;
    }

    public function duplicate(): self
    {
        $maxSlideNumber = static::where('sermon_id', $this->sermon_id)->max('slide_number');

        return static::create([
            'sermon_id' => $this->sermon_id,
            'slide_number' => $maxSlideNumber + 1,
            'slide_type' => $this->slide_type,
            'html_content' => $this->html_content,
            'css_styles' => $this->css_styles,
            'background_type' => $this->background_type,
            'background_value' => $this->background_value,
            'metadata' => $this->metadata,
        ]);
    }

    public function getRenderedHtmlAttribute(): string
    {
        $html = '<div class="slide" style="width: 1920px; height: 1080px; position: relative; overflow: hidden;">';

        // Add background
        $html .= $this->getBackgroundHtml();

        // Add content
        $html .= '<div class="slide-content" style="position: relative; z-index: 10; padding: 60px;">';
        $html .= $this->html_content;
        $html .= '</div>';

        // Add custom styles
        if ($this->css_styles) {
            $html .= '<style>'.$this->css_styles.'</style>';
        }

        $html .= '</div>';

        return $html;
    }

    protected function getBackgroundHtml(): string
    {
        $style = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;';

        return match ($this->background_type) {
            'color' => '<div class="slide-background" style="'.$style.' background-color: '.($this->background_value ?? '#ffffff').'"></div>',
            'gradient' => '<div class="slide-background" style="'.$style.' background: '.($this->background_value ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)').'"></div>',
            'image' => '<div class="slide-background" style="'.$style.' background-image: url('.($this->background_value ?? '').');  background-size: cover; background-position: center;"></div>',
            default => '<div class="slide-background" style="'.$style.' background-color: #ffffff;"></div>',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($slide) {
            if (! $slide->slide_number) {
                $slide->slide_number = static::where('sermon_id', $slide->sermon_id)->max('slide_number') + 1;
            }
        });
    }
}
