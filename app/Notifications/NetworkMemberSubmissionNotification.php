<?php

namespace App\Notifications;

use App\Models\NetworkMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NetworkMemberSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public NetworkMember $networkMember
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Network Member Submission - Pioneer Missions Africa')
            ->greeting('Hello Administrator')
            ->line('A new network member has submitted their profile for approval.')
            ->line('**Member Details:**')
            ->line('Name: '.$this->networkMember->name)
            ->line('Type: '.ucfirst($this->networkMember->type))
            ->line('Email: '.$this->networkMember->email)
            ->line('Location: '.$this->networkMember->address ?? 'Coordinates provided')
            ->action('Review Submission', route('filament.admin.resources.network-members.edit', $this->networkMember->id))
            ->line('Please review and approve or reject this submission at your earliest convenience.')
            ->salutation('Blessings,\nPioneer Missions Africa Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'network_member_id' => $this->networkMember->id,
            'type' => 'network_member_submission',
        ];
    }
}
