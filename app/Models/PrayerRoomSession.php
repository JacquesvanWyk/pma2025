<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PrayerRoomSession extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'session_date',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'datetime',
        ];
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('session_date', '>=', now());
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('session_date', '<', now());
    }
}
