<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestUseSendEmail extends Command
{
    protected $signature = 'mail:test-usesend {email : The email address to send to}';

    protected $description = 'Send a test email via UseSend to verify the integration';

    public function handle(): int
    {
        $email = $this->argument('email');

        $this->info("Sending test email to {$email}...");

        try {
            Mail::raw('This is a test email from Pioneer Missions Africa using UseSend.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('UseSend Test Email - '.now()->format('Y-m-d H:i:s'));
            });

            $this->info('Email sent successfully!');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send email: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
