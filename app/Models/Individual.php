<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Individual extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'profile_photo',
        'bio',
        'phone',
        'email',
        'show_phone',
        'show_email',
        'city',
        'country',
        'latitude',
        'longitude',
        'focus_areas',
        'languages',
        'skills',
        'needs',
        'offers',
        'tags',
        'privacy_level',
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
            'skills' => 'array',
            'needs' => 'array',
            'offers' => 'array',
            'tags' => 'array',
            'show_phone' => 'boolean',
            'show_email' => 'boolean',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
