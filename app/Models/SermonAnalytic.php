<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'sermon_id',
        'date',
        'views',
        'unique_views',
        'downloads',
        'shares',
        'average_read_time_seconds',
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'unique_views' => 'integer',
        'downloads' => 'integer',
        'shares' => 'integer',
        'average_read_time_seconds' => 'integer',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }
}
