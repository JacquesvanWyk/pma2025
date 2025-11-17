<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonVerse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sermon_id',
        'verse_id',
        'position',
        'context_notes',
    ];

    protected $casts = [
        'position' => 'integer',
        'created_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function verse(): BelongsTo
    {
        return $this->belongsTo(BibleVerse::class, 'verse_id');
    }
}
