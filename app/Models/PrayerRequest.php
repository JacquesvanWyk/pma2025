<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'request',
        'prayer_room_date',
        'status',
        'admin_notes',
        'is_private',
        'emailed',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'emailed' => 'boolean',
    ];
}
