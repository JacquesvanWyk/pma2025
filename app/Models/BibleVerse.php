<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BibleVerse extends Model
{
    use HasFactory;

    protected $fillable = [
        'book',
        'chapter',
        'verse',
        'translation',
        'language',
        'text',
        'formatted_reference',
        'api_source',
    ];

    protected $casts = [
        'chapter' => 'integer',
        'verse' => 'integer',
    ];

    public function sermons(): BelongsToMany
    {
        return $this->belongsToMany(Sermon::class, 'sermon_verses', 'verse_id', 'sermon_id')
            ->withPivot('position', 'context_notes')
            ->withTimestamps();
    }
}
