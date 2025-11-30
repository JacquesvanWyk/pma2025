<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Short extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'video_path',
        'youtube_url',
        'thumbnail_path',
        'tags',
        'is_published',
        'published_at',
        'view_count',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($short) {
            if (empty($short->slug)) {
                $short->slug = Str::slug($short->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (! $this->youtube_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);

        if (isset($matches[1])) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return null;
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (! $this->youtube_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);

        return $matches[1] ?? null;
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        return Storage::disk('r2')->url($this->video_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail_path) {
            return null;
        }

        return Storage::disk('r2')->url($this->thumbnail_path);
    }
}
