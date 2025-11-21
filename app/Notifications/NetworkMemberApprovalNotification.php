<?php

namespace App\Notifications;

use App\Models\NetworkMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NetworkMemberApprovalNotification extends Notification implements ShouldQueue
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
            ->subject('Welcome to the Pioneer Missions Africa Network! - Your Profile is Approved')
            ->greeting('Praise God, '.$this->networkMember->name.'!')
            ->line('Your network member profile has been reviewed and approved!')
            ->line('You are now part of our growing community of believers across South Africa.')
            ->line('**What happens next?**')
            ->line('• Your profile is now visible on our interactive network map')
            ->line('• Other believers can find and connect with you for fellowship')
            ->line('• You can explore the network to find like-minded believers in your area')
            ->action('View the Network Map', route('network.index'))
            ->line('We pray this network will be a blessing to you and help you build meaningful faith relationships.')
            ->line('If you need to update your information or have any questions, please don\'t hesitate to contact us.')
            ->salutation('In His Service,\nPioneer Missions Africa Team');
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
            'type' => 'network_member_approval',
        ];
    }
}
