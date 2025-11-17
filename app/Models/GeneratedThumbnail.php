<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedThumbnail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sermon_id',
        'media_file_id',
        'prompt',
        'style_preset',
        'provider',
        'model',
        'generation_time_seconds',
        'cost',
        'selected',
    ];

    protected $casts = [
        'generation_time_seconds' => 'integer',
        'cost' => 'decimal:4',
        'selected' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }
}
