<?php

namespace App\Notifications;

use App\Models\NetworkMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NetworkMemberRejectionNotification extends Notification implements ShouldQueue
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
            ->subject('Regarding Your Network Member Submission - Pioneer Missions Africa')
            ->greeting('Dear '.$this->networkMember->name.',')
            ->line('Thank you for your interest in joining the Pioneer Missions Africa network.')
            ->line('After careful review of your submission, we regret to inform you that we are unable to approve your profile at this time.')
            ->line('This decision helps us maintain the integrity and safety of our network for all members.')
            ->line('**Please note:**')
            ->line('• You are welcome to submit a new application in the future if circumstances change')
            ->line('• We appreciate your understanding and pray God\'s guidance in your fellowship journey')
            ->line('• If you have questions about this decision, you can contact us for clarification')
            ->action('Contact Us', route('contact'))
            ->line('We pray that God will continue to guide you and connect you with fellow believers in your community.')
            ->salutation('In His Grace,\nPioneer Missions Africa Team');
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
            'type' => 'network_member_rejection',
        ];
    }
}
