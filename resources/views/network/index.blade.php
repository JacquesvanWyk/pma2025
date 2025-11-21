@extends('layouts.public')

@section('title', 'Believer Network - Pioneer Missions Africa')
@section('description', 'Connect with believers and fellowship groups across South Africa. Find like-minded individuals in your area through our interactive faith community map.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center pma-animate-on-scroll">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                 style="background: rgba(255, 255, 255, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h1 class="pma-hero-title pma-display text-white mb-6">
                Believer Network
            </h1>
            <p class="pma-hero-subtitle pma-accent text-white/90 mb-8">
                Connect with believers and fellowship groups across South Africa. Find like-minded individuals in your area and build meaningful faith relationships.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('network.join') }}" class="pma-btn pma-btn-primary">
                    <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Join the Network
                </a>
                <a href="#network-map" class="pma-btn pma-btn-secondary">
                    <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Network
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Network Map -->
<section id="network-map" class="relative" style="background: white;">
    <livewire:network-map />
</section>

<!-- Network Feed Section -->
<section class="py-20" style="background: white;" x-data="{
    showModal: false,
    modalType: '',
    modalData: null,
    openModal(type, data) {
        this.modalType = type;
        this.modalData = data;
        this.showModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.showModal = false;
        document.body.style.overflow = '';
    }
}">
    <div class="container mx-auto px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Feed (Left Side - takes 2 columns) -->
                <div class="lg:col-span-2">
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="pma-section-title pma-display mb-2" style="color: var(--color-indigo);">
                                    Network Feed
                                </h2>
                                <p class="pma-body" style="color: var(--color-olive);">
                                    Latest updates, prayers, and testimonies from the network
                                </p>
                            </div>
                            <div class="hidden md:block">
                                <livewire:feed.create-post :key="'create-post'" />
                            </div>
                        </div>

                        <!-- Mobile Create Post Button -->
                        <div class="md:hidden mb-4">
                            <livewire:feed.create-post :key="'create-post-mobile'" />
                        </div>
                    </div>

                    <!-- Feed Posts -->
                    <div class="space-y-6">
                        @forelse($feedPosts as $post)
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                                <!-- Post Header -->
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: var(--color-pma-green);">
                                        @if($post->author_type === 'App\Models\Ministry')
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        @elseif($post->author_type === 'App\Models\Individual')
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @else
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg" style="color: var(--color-indigo);">
                                            {{ $post->author?->name ?? 'Unknown' }}
                                        </h3>
                                        <div class="flex items-center gap-3 text-sm" style="color: var(--color-olive);">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: var(--color-cream);">
                                                {{ ucfirst($post->type) }}
                                            </span>
                                            <span>{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Post Content -->
                                @if($post->title)
                                    <h4 class="font-bold text-xl mb-3" style="color: var(--color-indigo);">
                                        {{ $post->title }}
                                    </h4>
                                @endif
                                <p class="pma-body mb-4 whitespace-pre-line" style="color: var(--color-olive);">
                                    {{ $post->content }}
                                </p>

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
                            <div class="text-center py-12">
                                <p class="pma-body" style="color: var(--color-olive);">No posts yet. Be the first to share!</p>
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $feedPosts->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Right Side) -->
                <div class="lg:col-span-1">
                    <!-- Ministries Section -->
                    <div class="mb-8">
                        <h3 class="font-bold text-xl mb-4" style="color: var(--color-indigo);">Ministries</h3>
                        <div class="space-y-4">
                            @forelse($ministries->take(5) as $ministry)
                                <div @click="openModal('ministry', {{ $ministry->toJson() }})" class="bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition cursor-pointer">
                                    <h4 class="font-semibold mb-1" style="color: var(--color-indigo);">{{ $ministry->name }}</h4>
                                    <p class="text-sm mb-2" style="color: var(--color-olive);">{{ $ministry->city }}, {{ $ministry->country }}</p>
                                    @if($ministry->focus_areas)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($ministry->focus_areas, 0, 3) as $area)
                                                <span class="px-2 py-1 text-xs rounded" style="background: var(--color-cream); color: var(--color-olive);">{{ $area }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm" style="color: var(--color-olive);">No ministries yet</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Fellowships Section -->
                    <div class="mb-8">
                        <h3 class="font-bold text-xl mb-4" style="color: var(--color-indigo);">Fellowships</h3>
                        <div class="space-y-4">
                            @forelse($fellowships->take(5) as $fellowship)
                                <div @click="openModal('fellowship', {{ $fellowship->toJson() }})" class="bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition cursor-pointer">
                                    <h4 class="font-semibold mb-1" style="color: var(--color-indigo);">{{ $fellowship->name }}</h4>
                                    <p class="text-sm mb-2" style="color: var(--color-olive);">{{ $fellowship->city }}, {{ $fellowship->country }}</p>
                                    @if($fellowship->member_count)
                                        <p class="text-xs" style="color: var(--color-olive);">{{ $fellowship->member_count }} members</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm" style="color: var(--color-olive);">No fellowships yet</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Individuals Section -->
                    <div>
                        <h3 class="font-bold text-xl mb-4" style="color: var(--color-indigo);">Believers</h3>
                        <div class="space-y-4">
                            @forelse($individuals->take(5) as $individual)
                                <div @click="openModal('individual', {{ $individual->toJson() }})" class="bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition cursor-pointer">
                                    <h4 class="font-semibold mb-1" style="color: var(--color-indigo);">{{ $individual->name }}</h4>
                                    <p class="text-sm" style="color: var(--color-olive);">{{ $individual->city }}, {{ $individual->country }}</p>
                                </div>
                            @empty
                                <p class="text-sm" style="color: var(--color-olive);">No individuals yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div x-show="showModal"
         x-cloak
         @click.self="closeModal()"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="background: rgba(0, 0, 0, 0.75);">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div @click.stop
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="pma-heading text-2xl" style="color: var(--color-indigo);" x-text="modalData?.name"></h2>
                    <button @click="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="h-6 w-6" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-6">
                    <!-- Ministry Details -->
                    <template x-if="modalType === 'ministry'">
                        <div>
                            <!-- Location -->
                            <div class="mb-6">
                                <div class="flex items-center gap-2 text-sm mb-4" style="color: var(--color-olive);">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="`${modalData?.city}, ${modalData?.country}`"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">About</h3>
                                <p class="pma-body whitespace-pre-line" style="color: var(--color-olive);" x-text="modalData?.description"></p>
                            </div>

                            <!-- Focus Areas -->
                            <div class="mb-6" x-show="modalData?.focus_areas?.length">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">Focus Areas</h3>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="area in modalData?.focus_areas" :key="area">
                                        <span class="px-3 py-1 text-sm rounded-full" style="background: var(--color-cream); color: var(--color-olive);" x-text="area"></span>
                                    </template>
                                </div>
                            </div>

                            <!-- Website & Social -->
                            <div class="mb-6" x-show="modalData?.website || modalData?.social_media?.facebook || modalData?.social_media?.twitter || modalData?.social_media?.instagram">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">Connect</h3>
                                <div class="space-y-2">
                                    <a x-show="modalData?.website" :href="modalData?.website" target="_blank" class="flex items-center gap-2 text-sm hover:underline" style="color: var(--color-pma-green);">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                        <span x-text="modalData?.website"></span>
                                    </a>
                                    <a x-show="modalData?.social_media?.facebook" :href="modalData?.social_media?.facebook" target="_blank" class="flex items-center gap-2 text-sm hover:underline" style="color: var(--color-pma-green);">
                                        <span>Facebook</span>
                                    </a>
                                    <a x-show="modalData?.social_media?.twitter" :href="modalData?.social_media?.twitter" target="_blank" class="flex items-center gap-2 text-sm hover:underline" style="color: var(--color-pma-green);">
                                        <span>Twitter</span>
                                    </a>
                                    <a x-show="modalData?.social_media?.instagram" :href="modalData?.social_media?.instagram" target="_blank" class="flex items-center gap-2 text-sm hover:underline" style="color: var(--color-pma-green);">
                                        <span>Instagram</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Fellowship Details -->
                    <template x-if="modalType === 'fellowship'">
                        <div>
                            <!-- Location -->
                            <div class="mb-6">
                                <div class="flex items-center gap-2 text-sm mb-4" style="color: var(--color-olive);">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="`${modalData?.city}, ${modalData?.country}`"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">About</h3>
                                <p class="pma-body whitespace-pre-line" style="color: var(--color-olive);" x-text="modalData?.description"></p>
                            </div>

                            <!-- Member Count & Meeting Times -->
                            <div class="mb-6 grid md:grid-cols-2 gap-4">
                                <div x-show="modalData?.member_count">
                                    <h3 class="font-bold text-sm mb-1" style="color: var(--color-indigo);">Members</h3>
                                    <p class="pma-body" style="color: var(--color-olive);" x-text="modalData?.member_count"></p>
                                </div>
                                <div x-show="modalData?.meeting_times">
                                    <h3 class="font-bold text-sm mb-1" style="color: var(--color-indigo);">Meeting Times</h3>
                                    <p class="pma-body" style="color: var(--color-olive);" x-text="modalData?.meeting_times"></p>
                                </div>
                            </div>

                            <!-- Resources -->
                            <div class="mb-6" x-show="modalData?.resources?.length">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">Resources</h3>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="resource in modalData?.resources" :key="resource">
                                        <span class="px-3 py-1 text-sm rounded-full" style="background: var(--color-cream); color: var(--color-olive);" x-text="resource"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Individual Details -->
                    <template x-if="modalType === 'individual'">
                        <div>
                            <!-- Location -->
                            <div class="mb-6">
                                <div class="flex items-center gap-2 text-sm mb-4" style="color: var(--color-olive);">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="`${modalData?.city}, ${modalData?.country}`"></span>
                                </div>
                            </div>

                            <!-- Bio -->
                            <div class="mb-6" x-show="modalData?.bio">
                                <h3 class="font-bold text-lg mb-2" style="color: var(--color-indigo);">About</h3>
                                <p class="pma-body whitespace-pre-line" style="color: var(--color-olive);" x-text="modalData?.bio"></p>
                            </div>

                            <!-- Skills, Needs, Offers -->
                            <div class="mb-6 grid md:grid-cols-3 gap-4">
                                <div x-show="modalData?.skills?.length">
                                    <h3 class="font-bold text-sm mb-2" style="color: var(--color-indigo);">Skills</h3>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="skill in modalData?.skills" :key="skill">
                                            <span class="px-2 py-1 text-xs rounded" style="background: var(--color-cream); color: var(--color-olive);" x-text="skill"></span>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="modalData?.needs?.length">
                                    <h3 class="font-bold text-sm mb-2" style="color: var(--color-indigo);">Needs</h3>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="need in modalData?.needs" :key="need">
                                            <span class="px-2 py-1 text-xs rounded" style="background: var(--color-cream); color: var(--color-olive);" x-text="need"></span>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="modalData?.offers?.length">
                                    <h3 class="font-bold text-sm mb-2" style="color: var(--color-indigo);">Offers</h3>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="offer in modalData?.offers" :key="offer">
                                            <span class="px-2 py-1 text-xs rounded" style="background: var(--color-cream); color: var(--color-olive);" x-text="offer"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Feed Posts Section (Common for all types) -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="font-bold text-lg mb-4" style="color: var(--color-indigo);">Recent Posts</h3>
                        <div class="space-y-4">
                            <template x-if="!modalData?.feed_posts || modalData?.feed_posts.length === 0">
                                <p class="text-center py-8 pma-body" style="color: var(--color-olive);">No posts yet</p>
                            </template>
                            <template x-for="post in modalData?.feed_posts" :key="post.id">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full" style="background: var(--color-cream); color: var(--color-olive);" x-text="post.type.charAt(0).toUpperCase() + post.type.slice(1)"></span>
                                        <span class="text-xs" style="color: var(--color-olive);" x-text="new Date(post.created_at).toLocaleDateString()"></span>
                                    </div>
                                    <h4 class="font-semibold mb-2" style="color: var(--color-indigo);" x-text="post.title"></h4>
                                    <p class="pma-body text-sm whitespace-pre-line" style="color: var(--color-olive);" x-text="post.content"></p>
                                    <div class="flex items-center gap-4 mt-3 text-xs" style="color: var(--color-olive);">
                                        <span x-text="`${post.reactions?.length || 0} reactions`"></span>
                                        <span x-text="`${post.comments?.length || 0} comments`"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                    <button @click="closeModal()" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 transition">
                        Close
                    </button>
                    <button class="pma-btn pma-btn-primary">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contact
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 lg:py-32" style="background: var(--color-cream);">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto text-center mb-16">
            <h2 class="pma-section-title pma-display mb-6" style="color: var(--color-indigo);">
                How It Works
            </h2>
            <p class="pma-body text-lg" style="color: var(--color-olive);">
                Simple steps to connect with believers in your community
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center pma-animate-on-scroll pma-stagger-1">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Join the Network</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Create your profile with your location and interests. We'll review your submission to ensure community safety.
                </p>
            </div>

            <div class="text-center pma-animate-on-scroll pma-stagger-2">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Find Believers</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Search the interactive map to find individuals and fellowship groups in your area. Filter by type and language.
                </p>
            </div>

            <div class="text-center pma-animate-on-scroll pma-stagger-3">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background: var(--color-pma-green);">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-4.216-7.919M9 16a9.003 9.003 0 01-4.216-7.919M12 8c0 4.418-4.03 8-9 8a9.003 9.003 0 01-9-8c0-4.418 4.03-8 9-8a9.003 9.003 0 019 8" />
                    </svg>
                </div>
                <h3 class="pma-heading text-xl mb-3" style="color: var(--color-indigo);">Connect & Fellowship</h3>
                <p class="pma-body" style="color: var(--color-olive);">
                    Reach out to believers in your area. Join or start fellowship groups, and build lasting faith relationships.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 relative overflow-hidden" style="background: var(--gradient-spiritual);">
    <div class="pma-cross-pattern absolute inset-0"></div>

    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="max-w-3xl mx-auto pma-animate-on-scroll">
            <h2 class="pma-section-title pma-display mb-6" style="color: var(--color-indigo);">
                Ready to Connect?
            </h2>
            <p class="pma-body text-lg mb-10" style="color: var(--color-olive);">
                Join thousands of believers who are building faith communities across South Africa.
                Your next spiritual connection might be just around the corner.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('network.join') }}" class="pma-btn pma-btn-primary">
                    Join the Network
                </a>
                <a href="{{ route('contact') }}" class="pma-btn pma-btn-secondary">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate-on-scroll functionality
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.pma-animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
@endsection