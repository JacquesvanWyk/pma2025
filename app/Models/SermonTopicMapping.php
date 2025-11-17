<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonTopicMapping extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sermon_id',
        'topic_id',
        'relevance_score',
    ];

    protected $casts = [
        'relevance_score' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(SermonTopic::class, 'topic_id');
    }
}
