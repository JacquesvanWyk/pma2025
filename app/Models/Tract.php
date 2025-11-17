<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'title_afrikaans',
        'slug',
        'content',
        'description',
        'pdf_file',
        'language',
        'category',
        'status',
        'format_config',
        'download_count',
        'published_at',
    ];

    protected $casts = [
        'format_config' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tract) {
            if (empty($tract->slug)) {
                $slug = Str::slug($tract->title);
                $originalSlug = $slug;
                $count = 1;

                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $originalSlug.'-'.$count;
                    $count++;
                }

                $tract->slug = $slug;
            }
        });
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
