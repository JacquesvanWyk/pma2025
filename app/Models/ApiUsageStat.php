<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiUsageStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'service',
        'model',
        'action',
        'request_data',
        'response_data',
        'tokens_used',
        'status',
        'error_message',
        'rate_limit_info',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'rate_limit_info' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeForService($query, string $service)
    {
        return $query->where('service', $service);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeRateLimited($query)
    {
        return $query->where('status', 'rate_limited');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'error');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisHour($query)
    {
        return $query->where('created_at', '>=', now()->subHour());
    }
}
