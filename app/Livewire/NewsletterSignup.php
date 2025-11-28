<?php

namespace App\Livewire;

use App\Models\EmailList;
use App\Models\EmailSubscriber;
use Livewire\Component;

class NewsletterSignup extends Component
{
    public string $email = '';

    public string $language = 'en';

    public string $variant = 'default';

    public bool $showLanguage = true;

    public bool $success = false;

    public function mount(string $variant = 'default', bool $showLanguage = true): void
    {
        $this->variant = $variant;
        $this->showLanguage = $showLanguage;
    }

    public function subscribe(): void
    {
        $this->validate([
            'email' => 'required|email',
            'language' => 'required|in:en,af',
        ]);

        $list = EmailList::firstOrCreate(
            ['slug' => 'newsletter'],
            ['title' => 'Newsletter', 'is_active' => true]
        );

        EmailSubscriber::updateOrCreate(
            [
                'email_list_id' => $list->id,
                'email' => $this->email,
            ],
            [
                'language' => $this->language,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        $this->success = true;
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.newsletter-signup');
    }
}
