<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PowerpointPresentation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sermon_id',
        'media_file_id',
        'template_name',
        'slide_count',
        'includes_animations',
        'auto_generated',
        'metadata',
    ];

    protected $casts = [
        'slide_count' => 'integer',
        'includes_animations' => 'boolean',
        'auto_generated' => 'boolean',
        'metadata' => 'array',
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
