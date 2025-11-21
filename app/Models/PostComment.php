<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'feed_post_id',
        'user_id',
        'comment',
    ];

    public function feedPost(): BelongsTo
    {
        return $this->belongsTo(FeedPost::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
