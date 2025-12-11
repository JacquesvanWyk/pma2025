<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlidePresentation extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'source_text',
        'outline',
        'slides',
        'status',
        'style',
        'current_slide',
        'total_slides',
    ];

    protected function casts(): array
    {
        return [
            'outline' => 'array',
            'slides' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isGenerating(): bool
    {
        return $this->status === 'generating';
    }

    public function isComplete(): bool
    {
        return $this->status === 'complete';
    }

    public function getProgressPercentage(): int
    {
        if ($this->total_slides === 0) {
            return 0;
        }

        return (int) round(($this->current_slide / $this->total_slides) * 100);
    }
}
