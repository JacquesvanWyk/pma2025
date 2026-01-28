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

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function forService($query, string $service)
    {
        return $query->where('service', $service);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function successful($query)
    {
        return $query->where('status', 'success');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function rateLimited($query)
    {
        return $query->where('status', 'rate_limited');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function failed($query)
    {
        return $query->where('status', 'error');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function today($query)
    {
        return $query->whereDate('created_at', today());
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function thisHour($query)
    {
        return $query->where('created_at', '>=', now()->subHour());
    }
}
