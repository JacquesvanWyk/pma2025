{{-- <?php

use App\Models\FeedPost;
use App\Models\PostComment;
use App\Models\PostReaction;
use App\Models\Message;
use Livewire\Volt\Component;

new class extends Component {
    public function getStats(): array
    {
        $user = auth()->user();

        $userPosts = FeedPost::where('author_type', 'App\Models\User')
            ->where('author_id', $user->id)
            ->pluck('id');

        return [
            'total_posts' => $userPosts->count(),
            'total_comments_received' => PostComment::whereIn('feed_post_id', $userPosts)->count(),
            'total_reactions_received' => PostReaction::whereIn('feed_post_id', $userPosts)->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
            'unread_messages' => Message::where('recipient_type', 'App\Models\User')
                ->where('recipient_id', $user->id)
                ->whereNull('read_at')
                ->count(),
        ];
    }
}; ?>

{{-- <div>
    @php
        $stats = $this->getStats();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Total Posts -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" style="color: var(--color-indigo);">{{ $stats['total_posts'] }}</p>
            <p class="text-sm" style="color: var(--color-olive);">Posts</p>
        </div>

        <!-- Comments Received -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" style="color: var(--color-indigo);">{{ $stats['total_comments_received'] }}</p>
            <p class="text-sm" style="color: var(--color-olive);">Comments</p>
        </div>

        <!-- Reactions Received -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" style="color: var(--color-indigo);">{{ $stats['total_reactions_received'] }}</p>
            <p class="text-sm" style="color: var(--color-olive);">Reactions</p>
        </div>

        <!-- Unread Notifications -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" style="color: var(--color-indigo);">{{ $stats['unread_notifications'] }}</p>
            <p class="text-sm" style="color: var(--color-olive);">Notifications</p>
        </div>

        <!-- Unread Messages -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                <svg class="h-6 w-6" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" style="color: var(--color-indigo);">{{ $stats['unread_messages'] }}</p>
            <p class="text-sm" style="color: var(--color-olive);">Messages</p>
        </div>
    </div>
</div>
