<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialPost extends Model
{
    protected $fillable = [
        'postable_type',
        'postable_id',
        'platform',
        'post_id',
        'status',
        'error_message',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
        ];
    }

    public function postable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFacebook($query)
    {
        return $query->where('platform', 'facebook');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
