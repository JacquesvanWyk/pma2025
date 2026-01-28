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

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function facebook($query)
    {
        return $query->where('platform', 'facebook');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function posted($query)
    {
        return $query->where('status', 'posted');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function failed($query)
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
