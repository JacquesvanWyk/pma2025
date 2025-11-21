<?php

namespace App\Notifications;

use App\Models\FeedPost;
use App\Models\PostReaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PostReaction $reaction,
        public FeedPost $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $reactionEmojis = [
            'like' => '👍',
            'pray' => '🙏',
            'amen' => '✝️',
            'heart' => '❤️',
        ];

        return [
            'reaction_id' => $this->reaction->id,
            'post_id' => $this->post->id,
            'reactor_id' => $this->reaction->user_id,
            'reactor_name' => $this->reaction->user->name,
            'reaction_type' => $this->reaction->type,
            'reaction_emoji' => $reactionEmojis[$this->reaction->type] ?? '',
            'post_title' => $this->post->title,
            'message' => "{$this->reaction->user->name} reacted {$reactionEmojis[$this->reaction->type]} to your post",
        ];
    }
}
