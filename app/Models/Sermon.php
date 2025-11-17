<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sermon extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'uuid',
        'series_id',
        'title',
        'subtitle',
        'content',
        'excerpt',
        'sermon_date',
        'sermon_time',
        'duration_minutes',
        'primary_scripture',
        'author_id',
        'template_id',
        'language',
        'status',
        'published_at',
        'views_count',
        'downloads_count',
    ];

    protected $casts = [
        'sermon_date' => 'date',
        'sermon_time' => 'datetime:H:i',
        'duration_minutes' => 'integer',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(SermonSeries::class, 'series_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SermonTemplate::class, 'template_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SermonTranslation::class);
    }

    public function verses(): BelongsToMany
    {
        return $this->belongsToMany(BibleVerse::class, 'sermon_verses', 'sermon_id', 'verse_id')
            ->withPivot('position', 'context_notes')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function sermonVerses(): HasMany
    {
        return $this->hasMany(SermonVerse::class);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    public function aiConversations(): HasMany
    {
        return $this->hasMany(AiConversation::class);
    }

    public function aiSuggestions(): HasMany
    {
        return $this->hasMany(AiSuggestion::class);
    }

    public function generatedThumbnails(): HasMany
    {
        return $this->hasMany(GeneratedThumbnail::class);
    }

    public function powerpointPresentations(): HasMany
    {
        return $this->hasMany(PowerpointPresentation::class);
    }

    public function socialMediaPosts(): HasMany
    {
        return $this->hasMany(SocialMediaPost::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(SermonAnalytic::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(SermonTopic::class, 'sermon_topic_mappings', 'sermon_id', 'topic_id')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }

    public function topicMappings(): HasMany
    {
        return $this->hasMany(SermonTopicMapping::class);
    }

    public function slides(): HasMany
    {
        return $this->hasMany(SermonSlide::class)->orderBy('slide_number');
    }
}
