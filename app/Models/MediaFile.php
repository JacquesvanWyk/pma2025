<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MediaFile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'sermon_id',
        'type',
        'filename',
        'original_filename',
        'mime_type',
        'size_bytes',
        'storage_path',
        'public_url',
        'metadata',
        'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'metadata' => 'array',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function generatedThumbnail(): HasOne
    {
        return $this->hasOne(GeneratedThumbnail::class);
    }

    public function powerpointPresentation(): HasOne
    {
        return $this->hasOne(PowerpointPresentation::class);
    }

    public function socialMediaPosts(): HasMany
    {
        return $this->hasMany(SocialMediaPost::class);
    }

    public function churchAssets(): HasMany
    {
        return $this->hasMany(ChurchAsset::class);
    }
}
