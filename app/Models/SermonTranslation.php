<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sermon_id',
        'language',
        'title',
        'subtitle',
        'content',
        'excerpt',
        'translated_by_ai',
        'verified_by_user_id',
        'verified_at',
    ];

    protected $casts = [
        'translated_by_ai' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }
}
