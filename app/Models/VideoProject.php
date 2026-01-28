<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class VideoProject extends Model
{
    /** @use HasFactory<\Database\Factories\VideoProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'status',
        'description',
        'audio_media_id',
        'audio_path',
        'audio_url',
        'audio_duration_ms',
        'background_type',
        'background_value',
        'background_images',
        'logo_path',
        'logo_position',
        'text_style',
        'resolution',
        'aspect_ratio',
        'fps',
        'output_path',
        'thumbnail_path',
        'output_duration_ms',
        'output_size_bytes',
        'settings',
        'reference_lyrics',
        'metadata',
        'error_message',
        'processing_started_at',
        'processing_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'background_images' => 'array',
            'text_style' => 'array',
            'settings' => 'array',
            'metadata' => 'array',
            'processing_started_at' => 'datetime',
            'processing_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function audioMedia(): BelongsTo
    {
        return $this->belongsTo(GeneratedMedia::class, 'audio_media_id');
    }

    public function lyricTimestamps(): HasMany
    {
        return $this->hasMany(LyricTimestamp::class)->orderBy('order');
    }

    public function exports(): HasMany
    {
        return $this->hasMany(VideoExport::class)->orderByDesc('created_at');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function draft($query)
    {
        return $query->where('status', 'draft');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function processing($query)
    {
        return $query->where('status', 'processing');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function completed($query)
    {
        return $query->where('status', 'completed');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function failed($query)
    {
        return $query->where('status', 'failed');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function lyricVideos($query)
    {
        return $query->where('type', 'lyric_video');
    }

    public function getOutputUrlAttribute(): ?string
    {
        if ($this->output_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->output_path);
        }

        return null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->thumbnail_path);
        }

        return null;
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->logo_path);
        }

        return null;
    }

    public function getAudioSourceUrlAttribute(): ?string
    {
        if ($this->audio_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->audio_path);
        }

        if ($this->audio_url) {
            return $this->audio_url;
        }

        if ($this->audioMedia) {
            return $this->audioMedia->file_url;
        }

        return null;
    }

    public function getFormattedDurationAttribute(): ?string
    {
        if (! $this->audio_duration_ms) {
            return null;
        }

        $seconds = floor($this->audio_duration_ms / 1000);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $remainingSeconds);
    }

    public function getResolutionDimensionsAttribute(): array
    {
        return match ($this->resolution) {
            '720p' => ['width' => 1280, 'height' => 720],
            '1080p' => ['width' => 1920, 'height' => 1080],
            '4k' => ['width' => 3840, 'height' => 2160],
            default => ['width' => 1920, 'height' => 1080],
        };
    }

    public function getDefaultTextStyleAttribute(): array
    {
        return array_merge([
            'font' => 'Arial',
            'font_size' => 48,
            'font_color' => '#FFFFFF',
            'font_weight' => 'bold',
            'text_align' => 'center',
            'position' => 'center',
            'background_color' => null,
            'background_opacity' => 0.5,
            'shadow' => true,
            'animation' => 'fade',
        ], $this->text_style ?? []);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
