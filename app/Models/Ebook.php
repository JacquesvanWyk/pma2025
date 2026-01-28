<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'edition',
        'description',
        'language',
        'thumbnail',
        'pdf_file',
        'download_url',
        'slug',
        'is_featured',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'download_count' => 'integer',
        ];
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function published($query)
    {
        return $query->whereNotNull('created_at');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function featured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getThumbnailUrlAttribute()
    {
        if (! $this->thumbnail) {
            return null;
        }

        return asset('storage/ebooks/thumbnails/'.$this->thumbnail);
    }

    public function getPdfUrlAttribute()
    {
        return asset('storage/ebooks/'.$this->pdf_file);
    }
}
