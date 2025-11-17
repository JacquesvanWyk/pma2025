<?php

namespace App\Services;

use App\Models\ApiUsageStat;
use Illuminate\Support\Facades\Log;

class ApiUsageTracker
{
    public static function trackRequest(
        string $provider,
        string $service,
        string $action,
        array $requestData = [],
        ?string $model = null,
        ?int $tokensUsed = null,
        string $status = 'success',
        ?string $errorMessage = null,
        ?array $rateLimitInfo = null,
        array $responseData = []
    ): void {
        try {
            ApiUsageStat::create([
                'user_id' => auth()->id(),
                'provider' => $provider,
                'service' => $service,
                'model' => $model,
                'action' => $action,
                'request_data' => $requestData,
                'response_data' => $responseData,
                'tokens_used' => $tokensUsed,
                'status' => $status,
                'error_message' => $errorMessage,
                'rate_limit_info' => $rateLimitInfo,
            ]);

            // Log for debugging
            Log::info('API Usage Tracked', [
                'provider' => $provider,
                'service' => $service,
                'action' => $action,
                'status' => $status,
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            // Don't let tracking errors break the main functionality
            Log::error('Failed to track API usage', [
                'error' => $e->getMessage(),
                'provider' => $provider,
                'service' => $service,
                'action' => $action,
            ]);
        }
    }

    public static function trackSuccess(
        string $provider,
        string $service,
        string $action,
        array $requestData = [],
        ?string $model = null,
        ?int $tokensUsed = null,
        ?array $rateLimitInfo = null,
        array $responseData = []
    ): void {
        self::trackRequest(
            provider: $provider,
            service: $service,
            action: $action,
            requestData: $requestData,
            model: $model,
            tokensUsed: $tokensUsed,
            status: 'success',
            rateLimitInfo: $rateLimitInfo,
            responseData: $responseData
        );
    }

    public static function trackRateLimit(
        string $provider,
        string $service,
        string $action,
        string $errorMessage,
        array $requestData = [],
        ?string $model = null,
        ?array $rateLimitInfo = null
    ): void {
        self::trackRequest(
            provider: $provider,
            service: $service,
            action: $action,
            requestData: $requestData,
            model: $model,
            status: 'rate_limited',
            errorMessage: $errorMessage,
            rateLimitInfo: $rateLimitInfo
        );
    }

    public static function trackError(
        string $provider,
        string $service,
        string $action,
        string $errorMessage,
        array $requestData = [],
        ?string $model = null
    ): void {
        self::trackRequest(
            provider: $provider,
            service: $service,
            action: $action,
            requestData: $requestData,
            model: $model,
            status: 'error',
            errorMessage: $errorMessage
        );
    }

    public static function getUsageStats(string $period = 'today'): array
    {
        $query = ApiUsageStat::query();

        switch ($period) {
            case 'today':
                $query->today();
                break;
            case 'this_hour':
                $query->thisHour();
                break;
            case 'this_week':
                $query->where('created_at', '>=', now()->startOfWeek());
                break;
            case 'this_month':
                $query->where('created_at', '>=', now()->startOfMonth());
                break;
        }

        return [
            'total_requests' => $query->count(),
            'successful_requests' => $query->successful()->count(),
            'rate_limited_requests' => $query->rateLimited()->count(),
            'failed_requests' => $query->failed()->count(),
            'gemini_requests' => $query->forProvider('gemini')->count(),
            'nanobanana_requests' => $query->forProvider('nanobanana')->count(),
            'image_generation_requests' => $query->forService('image-generation')->count(),
            'text_generation_requests' => $query->forService('text-generation')->count(),
        ];
    }
}
