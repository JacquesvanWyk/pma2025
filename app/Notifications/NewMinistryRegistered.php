<?php

namespace App\Notifications;

use App\Models\Ministry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMinistryRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public Ministry $ministry
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Ministry Registration Needs Approval - Pioneer Missions Africa')
            ->greeting('New Ministry Registration!')
            ->line('A new ministry has registered on the Believer Network and needs approval.')
            ->line('**Name:** '.$this->ministry->name)
            ->line('**Email:** '.($this->ministry->email ?? 'Not provided'))
            ->line('**Location:** '.collect([
                $this->ministry->city,
                $this->ministry->province,
                $this->ministry->country,
            ])->filter()->implode(', '))
            ->line('**Registered by:** '.($this->ministry->user?->name ?? 'Unknown'))
            ->line('**Registered at:** '.$this->ministry->created_at->format('d M Y, H:i'))
            ->action('Review in Admin Panel', url('/admin/ministries'))
            ->line('Please review this ministry and approve or reject it in the admin panel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ministry_id' => $this->ministry->id,
            'ministry_name' => $this->ministry->name,
        ];
    }
}
