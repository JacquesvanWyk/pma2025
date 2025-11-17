<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sermon_id',
        'session_id',
        'provider',
        'model',
        'title',
        'total_tokens_used',
        'estimated_cost',
        'ended_at',
    ];

    protected $casts = [
        'total_tokens_used' => 'integer',
        'estimated_cost' => 'decimal:4',
        'ended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class, 'conversation_id');
    }
}
