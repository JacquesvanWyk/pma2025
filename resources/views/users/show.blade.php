<x-layouts.app :title="$user->name . ' - Profile'">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center gap-6">
                    <!-- Avatar -->
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl font-bold"
                         style="background: var(--color-cream); color: var(--color-indigo);">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>

                    <!-- User Info -->
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">{{ $user->name }}</h1>
                        <p class="text-white/80 mb-4">{{ $user->email }}</p>

                        <!-- Stats -->
                        <div class="flex gap-6 text-sm">
                            <div>
                                <span class="font-bold">{{ $stats['total_posts'] }}</span>
                                <span class="text-white/80">Posts</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ $stats['total_reactions'] }}</span>
                                <span class="text-white/80">Reactions</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ $stats['total_comments'] }}</span>
                                <span class="text-white/80">Comments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User's Feed -->
    <div class="py-12" style="background: var(--color-cream);">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2" style="color: var(--color-indigo);">
                        {{ $user->name }}'s Posts
                    </h2>
                    <p style="color: var(--color-olive);">
                        @if($stats['total_posts'] > 0)
                            Showing {{ $posts->count() }} of {{ $stats['total_posts'] }} posts
                        @else
                            No posts yet
                        @endif
                    </p>
                </div>

                <!-- Posts -->
                <div class="space-y-6">
                    @forelse($posts as $post)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                            <!-- Post Header -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold"
                                     style="background: var(--color-cream); color: var(--color-indigo);">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold" style="color: var(--color-indigo);">{{ $user->name }}</h3>
                                    <div class="flex items-center gap-2 text-sm" style="color: var(--color-olive);">
                                        <span class="px-2 py-1 rounded text-xs font-medium"
                                              style="background: var(--color-cream); color: var(--color-indigo);">
                                            {{ ucfirst($post->type) }}
                                        </span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Title -->
                            @if($post->title)
                            <h4 class="text-xl font-bold mb-3" style="color: var(--color-indigo);">
                                {{ $post->title }}
                            </h4>
                            @endif

                            <!-- Post Content -->
                            <p class="mb-4" style="color: var(--color-olive);">
                                {{ $post->content }}
                            </p>

                            <!-- Post Images -->
                            @if($post->images && count($post->images) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                                @foreach($post->images as $image)
                                <img src="{{ Storage::url($image) }}" alt="Post image"
                                     class="rounded-lg w-full h-48 object-cover">
                                @endforeach
                            </div>
                            @endif

                            <!-- Reactions -->
                            <div class="pt-4 border-t border-gray-200">
                                <livewire:feed.reaction-buttons :post="$post" :key="'reactions-'.$post->id" />
                            </div>

                            <!-- Comments Section -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h5 class="font-semibold mb-4" style="color: var(--color-indigo);">
                                    Comments ({{ $post->comments->count() }})
                                </h5>

                                <livewire:feed.comment-list :post="$post" :key="'comments-'.$post->id" />

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <livewire:feed.comment-form :post="$post" :key="'comment-form-'.$post->id" />
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
                            <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <p class="text-lg font-medium mb-2" style="color: var(--color-indigo);">No posts yet</p>
                            <p style="color: var(--color-olive);">{{ $user->name }} hasn't shared any posts yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Back to Network -->
    <div class="py-8" style="background: white;">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto text-center">
                <a href="{{ route('network.index') }}"
                   class="inline-flex items-center px-6 py-3 rounded-lg font-medium transition border-2"
                   style="border-color: var(--color-indigo); color: var(--color-indigo);">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Network Feed
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
