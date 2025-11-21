<?php

use App\Models\FeedPost;
use App\Models\PostReaction;
use Livewire\Volt\Component;

new class extends Component {
    public FeedPost $post;

    public function mount(FeedPost $post): void
    {
        $this->post = $post;
    }

    public function toggleReaction(string $type): void
    {
        if (! auth()->check()) {
            $this->dispatch('show-login-prompt');

            return;
        }

        $existingReaction = PostReaction::where('feed_post_id', $this->post->id)
            ->where('user_id', auth()->id())
            ->first();

        $shouldNotify = false;

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                $existingReaction->delete();
            } else {
                $existingReaction->update(['type' => $type]);
                $shouldNotify = true;
            }
        } else {
            $reaction = PostReaction::create([
                'feed_post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'type' => $type,
            ]);
            $shouldNotify = true;

            if ($shouldNotify && $this->post->author && $this->post->author->user_id && $this->post->author->user_id !== auth()->id()) {
                $this->post->author->user->notify(new \App\Notifications\NewReactionNotification($reaction, $this->post));
            }
        }

        $this->post->refresh();
    }

    public function getReactionCounts(): array
    {
        return [
            'like' => $this->post->reactions()->where('type', 'like')->count(),
            'pray' => $this->post->reactions()->where('type', 'pray')->count(),
            'amen' => $this->post->reactions()->where('type', 'amen')->count(),
            'heart' => $this->post->reactions()->where('type', 'heart')->count(),
        ];
    }

    public function getUserReaction(): ?string
    {
        if (! auth()->check()) {
            return null;
        }

        return PostReaction::where('feed_post_id', $this->post->id)
            ->where('user_id', auth()->id())
            ->value('type');
    }
}; ?>

<div class="flex items-center gap-3 flex-wrap">
    @php
        $counts = $this->getReactionCounts();
        $userReaction = $this->getUserReaction();
        $reactions = [
            'like' => ['icon' => '👍', 'label' => 'Like'],
            'pray' => ['icon' => '🙏', 'label' => 'Pray'],
            'amen' => ['icon' => '✝️', 'label' => 'Amen'],
            'heart' => ['icon' => '❤️', 'label' => 'Love'],
        ];
    @endphp

    @foreach($reactions as $type => $reaction)
        <button
            wire:click="toggleReaction('{{ $type }}')"
            wire:loading.attr="disabled"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition-all hover:scale-105 {{ $userReaction === $type ? 'ring-2 ring-offset-1' : 'hover:bg-gray-100' }}"
            style="{{ $userReaction === $type ? 'background: var(--color-pma-green); color: white; ring-color: var(--color-pma-green);' : 'background: var(--color-cream); color: var(--color-olive);' }}"
        >
            <span class="text-base">{{ $reaction['icon'] }}</span>
            <span>{{ $counts[$type] > 0 ? $counts[$type] : '' }}</span>
            <span wire:loading.remove wire:target="toggleReaction('{{ $type }}')">{{ $reaction['label'] }}</span>
            <span wire:loading wire:target="toggleReaction('{{ $type }}')" class="animate-pulse">...</span>
        </button>
    @endforeach

    <div class="ml-auto text-sm" style="color: var(--color-olive);">
        @php
            $totalReactions = array_sum($counts);
        @endphp
        @if($totalReactions > 0)
            <span class="font-medium">{{ $totalReactions }}</span> {{ Str::plural('reaction', $totalReactions) }}
        @endif
    </div>
</div>
