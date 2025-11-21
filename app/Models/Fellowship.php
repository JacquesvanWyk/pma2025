<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fellowship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'description',
        'phone',
        'email',
        'show_phone',
        'show_email',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'focus_areas',
        'languages',
        'resources',
        'tags',
        'member_count',
        'meeting_time',
        'website',
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
            'resources' => 'array',
            'tags' => 'array',
            'show_phone' => 'boolean',
            'show_email' => 'boolean',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'member_count' => 'integer',
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
