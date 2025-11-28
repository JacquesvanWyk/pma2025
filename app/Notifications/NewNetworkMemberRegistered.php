<?php

namespace App\Notifications;

use App\Models\NetworkMember;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNetworkMemberRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public NetworkMember $networkMember
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $type = $this->networkMember->type === 'individual' ? 'Individual' : 'Fellowship';

        return (new MailMessage)
            ->subject("New {$type} Registration Needs Approval - Pioneer Missions Africa")
            ->greeting("New {$type} Registration!")
            ->line("A new {$type} has registered on the Believer Network and needs approval.")
            ->line('**Name:** '.$this->networkMember->name)
            ->line('**Email:** '.$this->networkMember->email)
            ->line('**Location:** '.collect([
                $this->networkMember->city,
                $this->networkMember->province,
                $this->networkMember->country,
            ])->filter()->implode(', '))
            ->line('**Registered at:** '.$this->networkMember->created_at->format('d M Y, H:i'))
            ->action('Review in Admin Panel', url('/admin/network-members'))
            ->line('Please review and approve this registration.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'network_member_id' => $this->networkMember->id,
            'network_member_name' => $this->networkMember->name,
            'network_member_type' => $this->networkMember->type,
        ];
    }
}
