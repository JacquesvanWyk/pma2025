<?php

use App\Models\FeedPost;
use App\Models\PostComment;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public FeedPost $post;

    public bool $showAll = false;

    public int $initialLimit = 3;

    public function mount(FeedPost $post): void
    {
        $this->post = $post;
    }

    #[On('comment-added')]
    public function refresh(): void
    {
        $this->post->refresh();
        $this->showAll = true;
    }

    public function deleteComment(int $commentId): void
    {
        $comment = PostComment::findOrFail($commentId);

        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        $this->post->refresh();
    }

    public function toggleShowAll(): void
    {
        $this->showAll = ! $this->showAll;
    }

    public function getComments()
    {
        $query = $this->post->comments()->with('user')->latest();

        if (! $this->showAll) {
            return $query->limit($this->initialLimit)->get();
        }

        return $query->get();
    }
}; ?>

<div>
    @php
        $comments = $this->getComments();
        $totalComments = $this->post->comments()->count();
    @endphp

    @if($totalComments > 0)
        <div class="space-y-4 mb-4">
            @foreach($comments as $comment)
                <div class="flex gap-3" wire:key="comment-{{ $comment->id }}">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                            <span class="font-semibold text-sm" style="color: var(--color-olive);">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</span>
                        </div>
                    </div>

                    <div class="flex-1 bg-gray-50 rounded-lg px-4 py-3">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <a href="{{ route('users.show', $comment->user) }}" class="font-semibold text-sm hover:underline" style="color: var(--color-indigo);">{{ $comment->user->name }}</a>
                                <span class="text-xs ml-2" style="color: var(--color-olive);">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            @if(auth()->check() && $comment->user_id === auth()->id())
                                <button
                                    wire:click="deleteComment({{ $comment->id }})"
                                    wire:loading.attr="disabled"
                                    wire:confirm="Are you sure you want to delete this comment?"
                                    class="text-red-500 hover:text-red-700 text-xs font-medium"
                                >
                                    Delete
                                </button>
                            @endif
                        </div>

                        <p class="text-sm whitespace-pre-line" style="color: var(--color-olive);">{{ $comment->comment }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if($totalComments > $this->initialLimit)
            <button
                wire:click="toggleShowAll"
                class="text-sm font-medium hover:underline mb-4"
                style="color: var(--color-pma-green);"
            >
                @if($showAll)
                    Show less
                @else
                    View all {{ $totalComments }} comments
                @endif
            </button>
        @endif
    @else
        <p class="text-sm text-center py-4" style="color: var(--color-olive);">No comments yet. Be the first to comment!</p>
    @endif
</div>
