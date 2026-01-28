<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusicStylePreset extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'style_description',
        'genre',
        'mood',
        'instruments',
        'tempo',
        'is_global',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'is_global' => 'boolean',
            'usage_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forUser(Builder $query, ?int $userId = null): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('is_global', true);
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        });
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function global(Builder $query): Builder
    {
        return $query->where('is_global', true);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function getFullStyleAttribute(): string
    {
        $parts = [$this->style_description];

        if ($this->genre) {
            $parts[] = "Genre: {$this->genre}";
        }
        if ($this->mood) {
            $parts[] = "Mood: {$this->mood}";
        }
        if ($this->instruments) {
            $parts[] = "Instruments: {$this->instruments}";
        }
        if ($this->tempo) {
            $parts[] = "Tempo: {$this->tempo}";
        }

        return implode('. ', $parts);
    }
}
