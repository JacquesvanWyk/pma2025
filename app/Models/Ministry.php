<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ministry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'description',
        'website',
        'youtube',
        'facebook',
        'instagram',
        'twitter',
        'focus_areas',
        'languages',
        'tags',
        'country',
        'city',
        'latitude',
        'longitude',
        'is_approved',
        'approved_at',
        'approved_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'focus_areas' => 'array',
            'languages' => 'array',
            'tags' => 'array',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'recipient');
    }

    public function feedPosts(): MorphMany
    {
        return $this->morphMany(FeedPost::class, 'author');
    }

    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'organizer');
    }
}
