<?php

namespace App\Notifications;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPrayerRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PrayerRequest $prayerRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Prayer Request - '.$this->prayerRequest->name)
            ->greeting('New Prayer Request Submitted')
            ->line('A new prayer request has been submitted through the website.')
            ->line('')
            ->line('**Name:** '.$this->prayerRequest->name)
            ->line('**Email:** '.($this->prayerRequest->email ?: 'Not provided'))
            ->line('**Phone:** '.($this->prayerRequest->phone ?: 'Not provided'))
            ->line('**Private Request:** '.($this->prayerRequest->is_private ? 'Yes' : 'No'))
            ->line('')
            ->line('**Prayer Request:**')
            ->line($this->prayerRequest->request)
            ->line('')
            ->action('View in Admin Panel', url('/admin/prayer-requests/'.$this->prayerRequest->id))
            ->line('May God bless this prayer request.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'prayer_request_id' => $this->prayerRequest->id,
            'name' => $this->prayerRequest->name,
        ];
    }
}
