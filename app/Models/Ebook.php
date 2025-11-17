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
}
