<?php

use App\Models\FeedPost;
use Livewire\Volt\Component;

new class extends Component {
    public function getRecentPosts()
    {
        return FeedPost::where('author_type', 'App\Models\User')
            ->where('author_id', auth()->id())
            ->with(['reactions', 'comments'])
            ->latest()
            ->limit(5)
            ->get();
    }
}; ?>

<div class="bg-white rounded-lg shadow border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-lg" style="color: var(--color-indigo);">My Recent Posts</h3>
        <a href="{{ route('network.index') }}" class="text-sm font-medium hover:underline" style="color: var(--color-pma-green);">
            View all posts
        </a>
    </div>

    <div class="space-y-4">
        @php
            $posts = $this->getRecentPosts();
        @endphp

        @forelse($posts as $post)
            <div wire:key="post-{{ $post->id }}" class="border-l-4 pl-4 py-2" style="border-color: var(--color-pma-green);">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full" style="background: var(--color-cream); color: var(--color-olive);">
                        {{ ucfirst($post->type) }}
                    </span>
                    <span class="text-xs" style="color: var(--color-olive);">{{ $post->created_at->diffForHumans() }}</span>
                </div>

                @if($post->title)
                    <h4 class="font-semibold mb-1" style="color: var(--color-indigo);">{{ $post->title }}</h4>
                @endif

                <p class="text-sm line-clamp-2 mb-2" style="color: var(--color-olive);">
                    {{ $post->content }}
                </p>

                <div class="flex items-center gap-4 text-xs" style="color: var(--color-olive);">
                    <span>👍 {{ $post->reactions->count() }}</span>
                    <span>💬 {{ $post->comments->count() }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="h-12 w-12 mx-auto mb-3" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <p class="text-sm mb-2" style="color: var(--color-olive);">You haven't posted anything yet</p>
                <a href="{{ route('network.index') }}" class="text-sm font-medium hover:underline" style="color: var(--color-pma-green);">
                    Create your first post
                </a>
            </div>
        @endforelse
    </div>
</div>
