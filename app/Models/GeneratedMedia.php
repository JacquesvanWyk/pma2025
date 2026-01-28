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

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forType($query, string $type)
    {
        return $query->where('type', $type);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function images($query)
    {
        return $query->where('type', 'image');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function videos($query)
    {
        return $query->where('type', 'video');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function music($query)
    {
        return $query->where('type', 'music');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function completed($query)
    {
        return $query->where('status', 'completed');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function pending($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function failed($query)
    {
        return $query->where('status', 'failed');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function byProvider($query, string $provider)
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
