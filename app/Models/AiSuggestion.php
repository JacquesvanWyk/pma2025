<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiSuggestion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sermon_id',
        'type',
        'content',
        'applied',
        'applied_at',
    ];

    protected $casts = [
        'applied' => 'boolean',
        'applied_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }
}
