<?php

namespace App\Notifications;

use App\Models\NetworkMember;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

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

        // Generate signed URL for quick approval (valid for 7 days)
        $approveUrl = URL::signedRoute('network.quick-approve', [
            'networkMember' => $this->networkMember->id,
        ], now()->addDays(7));

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
            ->action('Approve Now', $approveUrl)
            ->line('[View in Admin Panel]('.url('/admin/network-members').')')
            ->line('This approval link expires in 7 days.');
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
