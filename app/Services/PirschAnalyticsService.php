<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PirschAnalyticsService
{
    protected string $baseUrl = 'https://api.pirsch.io/api/v1';

    protected ?string $accessToken = null;

    public function __construct(
        protected string $clientId,
        protected string $clientSecret,
        protected string $domainId
    ) {}

    public static function make(): self
    {
        return new self(
            config('services.pirsch.client_id'),
            config('services.pirsch.client_secret'),
            config('services.pirsch.domain_id')
        );
    }

    protected function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $cacheKey = 'pirsch_access_token';
        $cached = Cache::get($cacheKey);

        if ($cached) {
            $this->accessToken = $cached;

            return $this->accessToken;
        }

        $response = Http::post("{$this->baseUrl}/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to get Pirsch access token: '.$response->body());
        }

        $data = $response->json();
        $this->accessToken = $data['access_token'];

        Cache::put($cacheKey, $this->accessToken, now()->addHours(23));

        return $this->accessToken;
    }

    protected function request(string $method, string $endpoint, array $params = []): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->{$method}("{$this->baseUrl}{$endpoint}", $params);

        if ($response->failed()) {
            throw new \Exception("Pirsch API request failed: {$endpoint} - ".$response->body());
        }

        return $response->json() ?? [];
    }

    public function getVisitorStats(string $from, string $to, string $scale = 'day'): array
    {
        return $this->request('get', '/statistics/visitor', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
            'scale' => $scale,
        ]);
    }

    public function getActiveVisitors(): array
    {
        return $this->request('get', '/statistics/active', [
            'id' => $this->domainId,
        ]);
    }

    public function getTopPages(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/page', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getReferrers(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/referrer', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getCountries(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/country', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getBrowsers(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/browser', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getDevices(string $from, string $to): array
    {
        return $this->request('get', '/statistics/platform', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function getOperatingSystems(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/os', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getLanguages(string $from, string $to, int $limit = 10): array
    {
        $data = $this->request('get', '/statistics/language', [
            'id' => $this->domainId,
            'from' => $from,
            'to' => $to,
        ]);

        return array_slice($data, 0, $limit);
    }

    public function getSummary(string $from, string $to): array
    {
        $visitors = $this->getVisitorStats($from, $to);

        $totalVisitors = 0;
        $totalViews = 0;
        $totalSessions = 0;
        $totalBounces = 0;

        foreach ($visitors as $day) {
            $totalVisitors += $day['visitors'] ?? 0;
            $totalViews += $day['views'] ?? 0;
            $totalSessions += $day['sessions'] ?? 0;
            $totalBounces += $day['bounces'] ?? 0;
        }

        $bounceRate = $totalSessions > 0 ? round(($totalBounces / $totalSessions) * 100, 1) : 0;

        return [
            'visitors' => $totalVisitors,
            'views' => $totalViews,
            'sessions' => $totalSessions,
            'bounce_rate' => $bounceRate,
            'daily_stats' => $visitors,
        ];
    }

    public function getCountryName(string $code): string
    {
        $countries = [
            'za' => 'South Africa',
            'us' => 'United States',
            'gb' => 'United Kingdom',
            'br' => 'Brazil',
            'ke' => 'Kenya',
            'ng' => 'Nigeria',
            'cn' => 'China',
            'de' => 'Germany',
            'fr' => 'France',
            'in' => 'India',
            'au' => 'Australia',
            'ca' => 'Canada',
            'mw' => 'Malawi',
            'zw' => 'Zimbabwe',
            'bw' => 'Botswana',
            'na' => 'Namibia',
            'dz' => 'Algeria',
            'hr' => 'Croatia',
            'ni' => 'Nicaragua',
        ];

        return $countries[strtolower($code)] ?? strtoupper($code);
    }
}
