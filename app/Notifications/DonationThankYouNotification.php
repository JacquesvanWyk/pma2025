<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationThankYouNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Donation $donation
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
        $name = $this->donation->donor_name ?: 'Friend';
        $amount = number_format($this->donation->amount, 2);
        $currency = $this->donation->currency ?? 'ZAR';

        return (new MailMessage)
            ->subject('Thank You for Your Donation - Pioneer Missions Africa')
            ->greeting('Dear '.$name.',')
            ->line('Thank you so much for your generous donation of **'.$currency.' '.$amount.'**!')
            ->line('Your support helps us continue spreading the Gospel across Africa. Every contribution makes a real difference in reaching souls for Christ.')
            ->line('**Reference:** '.$this->donation->transaction_reference)
            ->line('---')
            ->line('**Explore Our Resources**')
            ->line('We have many free resources to help you grow in faith and stay connected:')
            ->line('• [PMA Worship]('.route('music.index').') - Listen to uplifting worship music')
            ->line('• [Picture Studies]('.route('resources.picture-studies').') - Visual Bible studies')
            ->line('• [Network Map]('.route('network.index').') - Connect with believers near you')
            ->line('• [Bible Studies]('.route('studies').') - In-depth studies on various topics')
            ->line('• [Sermons]('.route('sermons').') - Encouraging messages')
            ->line('---')
            ->line('**Follow Us**')
            ->line('• [Facebook](https://www.facebook.com/pioneermissionsafrica) - Updates & encouragement')
            ->line('• [YouTube](https://www.youtube.com/@pioneermissionsafrica3344) - Sermons, studies & worship')
            ->line('---')
            ->line('May God bless you abundantly for your faithfulness and generosity.')
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
            'donation_id' => $this->donation->id,
            'type' => 'donation_thank_you',
        ];
    }
}
