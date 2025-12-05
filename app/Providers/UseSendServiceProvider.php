<?php

declare(strict_types=1);

namespace App\Providers;

use App\Mail\Transport\UseSendTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class UseSendServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('usesend', function () {
                return new UseSendTransport(
                    apiKey: config('services.usesend.key'),
                    baseUrl: config('services.usesend.base_url', 'https://app.usesend.com/api/')
                );
            });
        });
    }
}
