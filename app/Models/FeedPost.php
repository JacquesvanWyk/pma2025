<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_type',
        'author_id',
        'type',
        'title',
        'content',
        'images',
        'attachments',
        'video_url',
        'answered_at',
        'is_pinned',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'attachments' => 'array',
            'answered_at' => 'datetime',
            'is_pinned' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function event(): HasOne
    {
        return $this->hasOne(Event::class);
    }

    public function markAsAnswered(): void
    {
        if ($this->type === 'prayer' && ! $this->answered_at) {
            $this->update(['answered_at' => now()]);
        }
    }

    public function isAnswered(): bool
    {
        return $this->answered_at !== null;
    }

    public function isPrayer(): bool
    {
        return $this->type === 'prayer';
    }
}
