<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LyricTimestamp extends Model
{
    protected $fillable = [
        'video_project_id',
        'order',
        'text',
        'section',
        'start_ms',
        'end_ms',
        'style_overrides',
        'animation',
    ];

    protected function casts(): array
    {
        return [
            'style_overrides' => 'array',
        ];
    }

    public function videoProject(): BelongsTo
    {
        return $this->belongsTo(VideoProject::class);
    }

    public function getStartTimeFormattedAttribute(): string
    {
        return $this->formatMilliseconds($this->start_ms);
    }

    public function getEndTimeFormattedAttribute(): string
    {
        return $this->formatMilliseconds($this->end_ms);
    }

    public function getDurationFormattedAttribute(): string
    {
        return $this->formatMilliseconds($this->end_ms - $this->start_ms);
    }

    protected function formatMilliseconds(int $ms): string
    {
        $seconds = floor($ms / 1000);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        $remainingMs = $ms % 1000;

        return sprintf('%02d:%02d.%03d', $minutes, $remainingSeconds, $remainingMs);
    }

    public function getStartSecondsAttribute(): float
    {
        return $this->start_ms / 1000;
    }

    public function getEndSecondsAttribute(): float
    {
        return $this->end_ms / 1000;
    }

    public function getDurationSecondsAttribute(): float
    {
        return ($this->end_ms - $this->start_ms) / 1000;
    }
}
