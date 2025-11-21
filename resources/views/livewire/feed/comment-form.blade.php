<?php

use App\Models\FeedPost;
use App\Models\PostComment;
use Livewire\Volt\Component;

new class extends Component {
    public FeedPost $post;

    public string $comment = '';

    public function mount(FeedPost $post): void
    {
        $this->post = $post;
    }

    public function addComment(): void
    {
        if (! auth()->check()) {
            $this->dispatch('show-login-prompt');

            return;
        }

        $this->validate([
            'comment' => 'required|string|min:1|max:2000',
        ]);

        $comment = PostComment::create([
            'feed_post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'comment' => $this->comment,
        ]);

        if ($this->post->author && $this->post->author->user_id && $this->post->author->user_id !== auth()->id()) {
            $this->post->author->user->notify(new \App\Notifications\NewCommentNotification($comment, $this->post));
        }

        $this->comment = '';

        $this->dispatch('comment-added');
    }
}; ?>

<div>
    @auth
        <form wire:submit="addComment" class="flex gap-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--color-pma-green);">
                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
            </div>

            <div class="flex-1">
                <textarea
                    wire:model="comment"
                    rows="2"
                    placeholder="Write a comment..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"
                    style="color: var(--color-olive);"
                ></textarea>

                @error('comment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="flex justify-end mt-2">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50"
                        style="background: var(--color-pma-green); color: white;"
                    >
                        <span wire:loading.remove>Post Comment</span>
                        <span wire:loading>Posting...</span>
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="text-center py-4">
            <p class="text-sm mb-2" style="color: var(--color-olive);">Please <a href="{{ route('login') }}" class="font-medium hover:underline" style="color: var(--color-pma-green);">log in</a> to comment</p>
        </div>
    @endauth
</div>
