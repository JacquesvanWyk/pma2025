<?php

namespace App\Notifications;

use App\Models\Ministry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MinistryApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Your Ministry Has Been Approved - Pioneer Missions Africa')
            ->greeting('Praise God, '.$this->ministry->name.'!')
            ->line('Your ministry has been reviewed and approved!')
            ->line('You are now part of our growing community of believers and ministries across Africa.')
            ->line('---')
            ->line('**Your Ministry on the Network**')
            ->line('Your ministry is now visible on our interactive network map')
            ->line('Other believers can discover and connect with your ministry')
            ->line('You can explore the network to find partner ministries and supporters')
            ->action('View the Network Map', route('network.index'))
            ->line('---')
            ->line('**Stay Connected With Us**')
            ->line('Join our WhatsApp group to stay updated and connect with fellow believers:')
            ->line('[Join WhatsApp Group](https://chat.whatsapp.com/FtjaXDiw5xtJZKuxYdfnYS)')
            ->line('')
            ->line('**Monthly Prayer Room**')
            ->line('We gather online for prayer every **last Wednesday of the month**. Join us as we lift up the work of the Gospel across Africa!')
            ->line('[Join Prayer Room WhatsApp](https://chat.whatsapp.com/EqqJGyKQm02KSaYoi6RhiN)')
            ->line('')
            ->line('**Follow Us on Social Media**')
            ->line('[Facebook](https://www.facebook.com/pioneermissionsafrica) - Updates, testimonies & encouragement')
            ->line('[YouTube](https://www.youtube.com/@pioneermissionsafrica3344) - Sermons, studies & worship')
            ->line('---')
            ->line('We pray that your ministry will be a blessing to many and that this network will help you build meaningful partnerships.')
            ->line('If you need to update your ministry information or have any questions, please don\'t hesitate to contact us.')
            ->salutation("In His Service,\nPioneer Missions Africa Team");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ministry_id' => $this->ministry->id,
            'type' => 'ministry_approval',
        ];
    }
}
