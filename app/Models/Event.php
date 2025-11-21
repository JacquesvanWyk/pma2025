<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'feed_post_id',
        'organizer_type',
        'organizer_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'city',
        'country',
        'latitude',
        'longitude',
        'event_type',
        'max_attendees',
        'is_online',
        'online_url',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_online' => 'boolean',
            'max_attendees' => 'integer',
        ];
    }

    public function feedPost(): BelongsTo
    {
        return $this->belongsTo(FeedPost::class);
    }

    public function organizer(): MorphTo
    {
        return $this->morphTo();
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function attendees(): HasMany
    {
        return $this->rsvps()->where('status', 'going');
    }

    public function isFull(): bool
    {
        if (! $this->max_attendees) {
            return false;
        }

        return $this->attendees()->count() >= $this->max_attendees;
    }
}
