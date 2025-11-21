<?php

namespace App\Notifications;

use App\Models\FeedPost;
use App\Models\PostComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PostComment $comment,
        public FeedPost $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->post->id,
            'commenter_id' => $this->comment->user_id,
            'commenter_name' => $this->comment->user->name,
            'post_title' => $this->post->title,
            'comment_preview' => substr($this->comment->comment, 0, 100),
            'message' => "{$this->comment->user->name} commented on your post",
        ];
    }
}
