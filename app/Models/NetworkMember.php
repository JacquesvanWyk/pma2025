<?php

namespace App\Models;

use App\Notifications\NetworkMemberSubmissionNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkMember extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function ($networkMember) {
            // Send notification to admin users when a new network member is submitted
            $adminUsers = User::where('email', 'like', '%admin%')->get();

            foreach ($adminUsers as $admin) {
                $admin->notify(new NetworkMemberSubmissionNotification($networkMember));
            }
        });
    }

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'email',
        'phone',
        'bio',
        'image_path',
        'professional_skills',
        'ministry_skills',
        'total_believers',
        'household_members',
        'show_household_members',
        'latitude',
        'longitude',
        'city',
        'province',
        'country',
        'address',
        'meeting_times',
        'show_email',
        'show_phone',
        'status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
        'total_believers' => 'integer',
        'household_members' => 'array',
        'professional_skills' => 'array',
        'ministry_skills' => 'array',
        'show_household_members' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'network_member_language');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeIndividuals($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeGroups($query)
    {
        return $query->where('type', 'group');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isIndividual(): bool
    {
        return $this->type === 'individual';
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }
}
