<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pw_albums';

    protected $fillable = [
        'title',
        'slug',
        'artist',
        'cover_image',
        'description',
        'release_date',
        'release_time',
        'suggested_donation',
        'is_published',
        'is_featured',
        'download_count',
        'audio_download_count',
        'video_download_count',
        'full_download_count',
        'audio_bundle_path',
        'mp3_bundle_path',
        'wav_bundle_path',
        'video_bundle_path',
        'full_bundle_path',
        'bundles_generated_at',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'suggested_donation' => 'decimal:2',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'download_count' => 'integer',
            'audio_download_count' => 'integer',
            'video_download_count' => 'integer',
            'full_download_count' => 'integer',
            'bundles_generated_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Album $album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title);
            }
        });

        static::deleting(function (Album $album) {
            $album->songs()->delete();
        });

        static::forceDeleting(function (Album $album) {
            $album->songs()->forceDelete();
        });

        static::restoring(function (Album $album) {
            $album->songs()->withTrashed()->restore();
        });
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class)->orderBy('track_number');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function published($query)
    {
        return $query->where('is_published', true);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function featured($query)
    {
        return $query->where('is_featured', true);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function released($query)
    {
        return $query->where('release_date', '<=', now());
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return Storage::disk('public')->url($this->cover_image);
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

    public function incrementFullDownload(): void
    {
        $this->increment('download_count');
        $this->increment('full_download_count');
    }

    public function getTotalDownloads(): int
    {
        return $this->download_count;
    }

    public function getDownloadBreakdown(): array
    {
        return [
            'audio' => $this->audio_download_count,
            'video' => $this->video_download_count,
            'full' => $this->full_download_count,
            'total' => $this->download_count,
        ];
    }

    public function getBundlePath(string $type): ?string
    {
        return match ($type) {
            'audio' => $this->audio_bundle_path,
            'mp3' => $this->mp3_bundle_path,
            'wav' => $this->wav_bundle_path,
            'video' => $this->video_bundle_path,
            'full' => $this->full_bundle_path,
            default => null,
        };
    }

    public function getBundleUrl(string $type): ?string
    {
        $path = $this->getBundlePath($type);

        if (! $path) {
            return null;
        }

        return Storage::disk('r2')->temporaryUrl($path, now()->addHour(), [
            'ResponseContentDisposition' => 'attachment; filename="'.basename($path).'"',
        ]);
    }

    public function hasBundles(): bool
    {
        return $this->bundles_generated_at !== null;
    }

    public function clearBundles(): void
    {
        // Delete old files from R2
        if ($this->audio_bundle_path) {
            Storage::disk('r2')->delete($this->audio_bundle_path);
        }
        if ($this->mp3_bundle_path) {
            Storage::disk('r2')->delete($this->mp3_bundle_path);
        }
        if ($this->wav_bundle_path) {
            Storage::disk('r2')->delete($this->wav_bundle_path);
        }
        if ($this->video_bundle_path) {
            Storage::disk('r2')->delete($this->video_bundle_path);
        }
        if ($this->full_bundle_path) {
            Storage::disk('r2')->delete($this->full_bundle_path);
        }

        $this->update([
            'audio_bundle_path' => null,
            'mp3_bundle_path' => null,
            'wav_bundle_path' => null,
            'video_bundle_path' => null,
            'full_bundle_path' => null,
            'bundles_generated_at' => null,
        ]);
    }

    public function isReleased(): bool
    {
        if (! $this->release_date) {
            return true;
        }

        $releaseDateTime = $this->getReleaseDateTime();

        return $releaseDateTime->lte(now());
    }

    public function getReleaseDateTime(): \Carbon\Carbon
    {
        $date = $this->release_date->format('Y-m-d');
        $time = $this->release_time ?? '00:00:00';

        return \Carbon\Carbon::parse("{$date} {$time}", 'Africa/Johannesburg');
    }

    public function getTimeUntilRelease(): ?array
    {
        if ($this->isReleased()) {
            return null;
        }

        $releaseDateTime = $this->getReleaseDateTime();
        $now = now('Africa/Johannesburg');
        $diff = $now->diff($releaseDateTime);

        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'timestamp' => $releaseDateTime->timestamp,
            'iso' => $releaseDateTime->toIso8601String(),
        ];
    }
}
