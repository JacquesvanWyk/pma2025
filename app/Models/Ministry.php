<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Ministry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'show_email',
        'show_phone',
        'logo',
        'description',
        'website',
        'youtube',
        'facebook',
        'instagram',
        'twitter',
        'focus_areas',
        'languages',
        'tags',
        'country',
        'city',
        'province',
        'address',
        'latitude',
        'longitude',
        'is_approved',
        'approved_at',
        'approved_by',
        'is_active',
        'status',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected function casts(): array
    {
        return [
            'focus_areas' => 'array',
            'languages' => 'array',
            'tags' => 'array',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Ministry $ministry) {
            $adminUsers = User::where('email', 'like', '%admin%')->get();
            foreach ($adminUsers as $admin) {
                Mail::raw(
                    "A new ministry '{$ministry->name}' has been registered and is pending approval.",
                    fn ($message) => $message
                        ->to($admin->email)
                        ->subject('New Ministry Registration Pending Approval')
                );
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'recipient');
    }

    public function feedPosts(): MorphMany
    {
        return $this->morphMany(FeedPost::class, 'author');
    }

    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'organizer');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function approved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function rejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
