<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GeneratedMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'provider',
        'model',
        'task_id',
        'status',
        'prompt',
        'title',
        'lyrics',
        'settings',
        'style_preset_id',
        'file_path',
        'remote_url',
        'thumbnail_path',
        'credits_used',
        'cost_usd',
        'duration_seconds',
        'metadata',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'metadata' => 'array',
            'credits_used' => 'decimal:4',
            'cost_usd' => 'decimal:4',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stylePreset(): BelongsTo
    {
        return $this->belongsTo(MusicStylePreset::class);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeMusic($query)
    {
        return $query->where('type', 'music');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->file_path);
        }

        return $this->remote_url;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->thumbnail_path);
        }

        return null;
    }

    public function isExpired(): bool
    {
        if (! $this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function hasLocalFile(): bool
    {
        return ! empty($this->file_path);
    }

    public function getFormattedDurationAttribute(): ?string
    {
        if (! $this->duration_seconds) {
            return null;
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
