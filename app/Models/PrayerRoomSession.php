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

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function upcoming(Builder $query): Builder
    {
        return $query->where('session_date', '>=', now());
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function past(Builder $query): Builder
    {
        return $query->where('session_date', '<', now());
    }
}
