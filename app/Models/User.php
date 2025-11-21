<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'language_preference',
        'timezone',
        'avatar_url',
        'last_login_at',
        'city',
        'province',
        'country',
        'latitude',
        'longitude',
        'location_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class, 'author_id');
    }

    public function createdSeries(): HasMany
    {
        return $this->hasMany(SermonSeries::class, 'created_by');
    }

    public function createdTemplates(): HasMany
    {
        return $this->hasMany(SermonTemplate::class, 'created_by');
    }

    public function uploadedMediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class, 'uploaded_by');
    }

    public function aiConversations(): HasMany
    {
        return $this->hasMany(AiConversation::class);
    }

    public function verifiedTranslations(): HasMany
    {
        return $this->hasMany(SermonTranslation::class, 'verified_by_user_id');
    }

    public function createdSocialPosts(): HasMany
    {
        return $this->hasMany(SocialMediaPost::class, 'created_by');
    }

    public function approvedSocialPosts(): HasMany
    {
        return $this->hasMany(SocialMediaPost::class, 'approved_by');
    }

    public function createdChurchAssets(): HasMany
    {
        return $this->hasMany(ChurchAsset::class, 'created_by');
    }

    // Believer Network Relationships

    public function networkMember()
    {
        return $this->hasOne(NetworkMember::class);
    }

    public function individuals(): HasMany
    {
        return $this->hasMany(Individual::class);
    }

    public function fellowships(): HasMany
    {
        return $this->hasMany(Fellowship::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function postReactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function eventRsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function approvedIndividuals(): HasMany
    {
        return $this->hasMany(Individual::class, 'approved_by');
    }

    public function approvedFellowships(): HasMany
    {
        return $this->hasMany(Fellowship::class, 'approved_by');
    }

    public function approvedMinistries(): HasMany
    {
        return $this->hasMany(Ministry::class, 'approved_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
