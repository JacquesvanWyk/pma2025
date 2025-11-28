<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'file_type',
        'language',
        'author',
        'category',
        'download_count',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'download_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            if (empty($note->slug)) {
                $slug = Str::slug($note->title);
                $originalSlug = $slug;
                $count = 1;

                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $originalSlug.'-'.$count;
                    $count++;
                }

                $note->slug = $slug;
            }
        });
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

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/notes/'.$this->file_path);
    }
}
