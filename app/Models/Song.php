<?php

namespace App\Models;

use App\Observers\SongObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[ObservedBy(SongObserver::class)]
class Song extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pw_songs';

    protected $fillable = [
        'album_id',
        'title',
        'slug',
        'track_number',
        'duration',
        'wav_file',
        'wav_file_path',
        'mp4_video',
        'description',
        'lyrics',
        'is_published',
        'is_preview',
        'download_count',
        'audio_download_count',
        'video_download_count',
        'lyrics_download_count',
        'bundle_download_count',
        'bundle_path',
        'bundle_generated_at',
        'play_count',
    ];

    protected function casts(): array
    {
        return [
            'track_number' => 'integer',
            'is_published' => 'boolean',
            'is_preview' => 'boolean',
            'download_count' => 'integer',
            'audio_download_count' => 'integer',
            'video_download_count' => 'integer',
            'lyrics_download_count' => 'integer',
            'bundle_download_count' => 'integer',
            'bundle_generated_at' => 'datetime',
            'play_count' => 'integer',
        ];
    }

    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Song $song) {
            if (empty($song->slug)) {
                $song->slug = Str::slug($song->title);
            }
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getWavFileUrlAttribute(): ?string
    {
        if (! $this->wav_file) {
            return null;
        }

        return Storage::disk('public')->url($this->wav_file);
    }

    public function getWavFilePathUrlAttribute(): ?string
    {
        if (! $this->wav_file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->wav_file_path);
    }

    public function hasMp3(): bool
    {
        return ! empty($this->wav_file);
    }

    public function hasWav(): bool
    {
        return ! empty($this->wav_file_path);
    }

    public function getMp3FileSize(): ?int
    {
        if (! $this->wav_file || ! Storage::disk('public')->exists($this->wav_file)) {
            return null;
        }

        return Storage::disk('public')->size($this->wav_file);
    }

    public function getWavFileSize(): ?int
    {
        if (! $this->wav_file_path || ! Storage::disk('public')->exists($this->wav_file_path)) {
            return null;
        }

        return Storage::disk('public')->size($this->wav_file_path);
    }

    public function getMp3FileSizeFormatted(): ?string
    {
        $size = $this->getMp3FileSize();
        if (! $size) {
            return null;
        }

        return $this->formatFileSize($size);
    }

    public function getWavFileSizeFormatted(): ?string
    {
        $size = $this->getWavFileSize();
        if (! $size) {
            return null;
        }

        return $this->formatFileSize($size);
    }

    protected function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 1) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }

    public function getMp4VideoUrlAttribute(): ?string
    {
        if (! $this->mp4_video) {
            return null;
        }

        return Storage::disk('public')->url($this->mp4_video);
    }

    public function hasVideo(): bool
    {
        return ! empty($this->mp4_video);
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }

    public function incrementAudioDownload(): void
    {
        $this->increment('download_count');
        $this->increment('audio_download_count');
    }

    public function incrementVideoDownload(): void
    {
        $this->increment('download_count');
        $this->increment('video_download_count');
    }

    public function incrementLyricsDownload(): void
    {
        $this->increment('download_count');
        $this->increment('lyrics_download_count');
    }

    public function incrementBundleDownload(): void
    {
        $this->increment('download_count');
        $this->increment('bundle_download_count');
    }

    public function incrementPlayCount(): void
    {
        $this->increment('play_count');
    }

    public function getDownloadBreakdown(): array
    {
        return [
            'audio' => $this->audio_download_count,
            'video' => $this->video_download_count,
            'lyrics' => $this->lyrics_download_count,
            'bundle' => $this->bundle_download_count,
            'total' => $this->download_count,
        ];
    }

    public function getBundleUrl(): ?string
    {
        if (! $this->bundle_path) {
            return null;
        }

        return Storage::disk('r2')->url($this->bundle_path);
    }

    public function hasBundle(): bool
    {
        return $this->bundle_generated_at !== null;
    }

    public function clearBundle(): void
    {
        if ($this->bundle_path) {
            Storage::disk('r2')->delete($this->bundle_path);
        }

        $this->update([
            'bundle_path' => null,
            'bundle_generated_at' => null,
        ]);
    }
}
