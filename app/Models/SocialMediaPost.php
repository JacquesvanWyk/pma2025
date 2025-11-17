<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialMediaPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'sermon_id',
        'platform',
        'content',
        'media_file_id',
        'language',
        'status',
        'scheduled_for',
        'published_at',
        'platform_post_id',
        'engagement_data',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'engagement_data' => 'array',
        'approved_at' => 'datetime',
    ];

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
