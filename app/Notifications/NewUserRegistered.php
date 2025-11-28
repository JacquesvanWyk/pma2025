<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New User Registration - Pioneer Missions Africa')
            ->greeting('New User Registered!')
            ->line('A new user has registered on Pioneer Missions Africa.')
            ->line('**Name:** '.$this->user->name)
            ->line('**Email:** '.$this->user->email)
            ->line('**Registered at:** '.$this->user->created_at->format('d M Y, H:i'))
            ->action('View in Admin Panel', url('/admin/users'))
            ->line('You can manage users from the admin panel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}
