<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SermonTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    public function sermons(): BelongsToMany
    {
        return $this->belongsToMany(Sermon::class, 'sermon_topic_mappings', 'topic_id', 'sermon_id')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }
}
