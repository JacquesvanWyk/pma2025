<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'image_path',
        'title',
        'caption',
        'alt_text',
        'order_position',
        'download_count',
        'created_by',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'order_position' => 'integer',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }

    public function taggedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['tagged_by', 'notified', 'notified_at'])
            ->withTimestamps();
    }
}
