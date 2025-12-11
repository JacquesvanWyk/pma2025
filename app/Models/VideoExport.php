<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VideoExport extends Model
{
    protected $fillable = [
        'video_project_id',
        'resolution',
        'file_path',
        'thumbnail_path',
        'file_size_bytes',
        'duration_ms',
        'status',
    ];

    public function videoProject(): BelongsTo
    {
        return $this->belongsTo(VideoProject::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return Storage::disk(config('kie.storage.disk', 'public'))->url($this->file_path);
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

    public function getFormattedFileSizeAttribute(): ?string
    {
        if (! $this->file_size_bytes) {
            return null;
        }

        $bytes = $this->file_size_bytes;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }

    public function getFormattedDurationAttribute(): ?string
    {
        if (! $this->duration_ms) {
            return null;
        }

        $seconds = floor($this->duration_ms / 1000);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $remainingSeconds);
    }
}
