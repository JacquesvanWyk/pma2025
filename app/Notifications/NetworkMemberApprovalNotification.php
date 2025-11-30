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
        $type = $this->networkMember->type === 'individual' ? 'Individual/Family' : 'Fellowship/Group';

        return (new MailMessage)
            ->subject('Welcome to the Pioneer Missions Africa Network!')
            ->greeting('Praise God, '.$this->networkMember->name.'!')
            ->line('Your '.$type.' profile has been reviewed and approved!')
            ->line('You are now part of our growing community of believers across South Africa.')
            ->line('---')
            ->line('**Your Profile on the Network**')
            ->line('• Your profile is now visible on our interactive network map')
            ->line('• Other believers can find and connect with you for fellowship')
            ->line('• You can explore the network to find like-minded believers in your area')
            ->action('View the Network Map', route('network.index'))
            ->line('---')
            ->line('**Stay Connected With Us**')
            ->line('Join our WhatsApp group to stay updated and connect with fellow believers:')
            ->line('[Join WhatsApp Group](https://chat.whatsapp.com/FtjaXDiw5xtJZKuxYdfnYS)')
            ->line('')
            ->line('**Monthly Prayer Room**')
            ->line('We gather online for prayer every **last Wednesday of the month**. Join us as we lift up the work of the Gospel across Africa!')
            ->line('[Visit Prayer Room]('.route('prayer-room.index').')')
            ->line('')
            ->line('**Follow Us on Social Media**')
            ->line('• [Facebook](https://www.facebook.com/pioneermissionsafrica) - Updates, testimonies & encouragement')
            ->line('• [YouTube](https://www.youtube.com/@pioneermissionsafrica3344) - Sermons, studies & worship')
            ->line('---')
            ->line('**Grow in the Word**')
            ->line('We have many free resources to help you grow in faith:')
            ->line('• [Bible Studies]('.route('studies').') - In-depth studies on various topics')
            ->line('• [E-Books]('.route('resources.ebooks').') - Free downloadable books')
            ->line('• [Tracts]('.route('resources.tracts').') - Share the Gospel with others')
            ->line('• [Sermons]('.route('sermons').') - Listen to encouraging messages')
            ->line('---')
            ->line('We pray this network will be a blessing to you and help you build meaningful faith relationships.')
            ->line('If you need to update your information or have any questions, please don\'t hesitate to contact us.')
            ->salutation("In His Service,\nPioneer Missions Africa Team");
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
