<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Study extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'content_images',
        'language',
        'type',
        'status',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content_images' => 'array',
        'content' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($study) {
            if (empty($study->slug)) {
                $slug = Str::slug($study->title);
                $originalSlug = $slug;
                $count = 1;

                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $originalSlug.'-'.$count;
                    $count++;
                }

                $study->slug = $slug;
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

    public function getPlainTextAttribute(): string
    {
        if (is_array($this->content)) {
            $text = '';
            foreach ($this->content as $block) {
                if ($block['type'] === 'text') {
                    $text .= ' ' . strip_tags($block['data']['content']);
                } elseif ($block['type'] === 'image') {
                    $text .= ' ' . ($block['data']['caption'] ?? '') . ' ' . ($block['data']['alt'] ?? '');
                }
            }
            return trim($text);
        }

        return strip_tags($this->content ?? '');
    }

    public function getReadingTimeAttribute(): int
    {
        return ceil(str_word_count($this->plain_text) / 200);
    }
}
