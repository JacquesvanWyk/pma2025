<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SermonTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'structure',
        'sections',
        'default_duration_minutes',
        'created_by',
        'is_public',
        'usage_count',
    ];

    protected $casts = [
        'structure' => 'array',
        'sections' => 'array',
        'default_duration_minutes' => 'integer',
        'is_public' => 'boolean',
        'usage_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class, 'template_id');
    }
}
