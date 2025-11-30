<div>
    <!-- Filters Section -->
    <section id="filters" class="sticky top-16 z-40 py-4" style="background: var(--color-cream); border-bottom: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div class="container mx-auto px-6">
            <div class="space-y-4">
                <!-- Search and Clear Row -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search shorts..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-2 transition-all focus:outline-none"
                                   style="border-color: var(--color-pma-green-light);">
                        </div>
                    </div>

                    @if($search || $selectedTag)
                        <button wire:click="clearFilters"
                                class="px-4 py-2 rounded-lg text-sm pma-body whitespace-nowrap"
                                style="background: var(--color-terracotta); color: white;">
                            Clear Filters
                        </button>
                    @endif
                </div>

                <!-- Tags Row -->
                @if($allTags->count() > 0)
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="pma-body text-sm font-semibold" style="color: var(--color-indigo);">Topics:</span>

                    @foreach($allTags as $tag)
                        <button wire:click="selectTag('{{ $tag }}')"
                                class="px-4 py-2 rounded-full text-sm pma-heading-light transition-all"
                                style="background: {{ $selectedTag === $tag ? 'var(--color-pma-green)' : 'white' }}; border: 2px solid var(--color-pma-green); color: {{ $selectedTag === $tag ? 'white' : 'var(--color-pma-green)' }};">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
                @endif

                <!-- Active Filters Display -->
                @if($search || $selectedTag)
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Active filters:</span>

                        @if($search)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                Search: "{{ $search }}"
                            </span>
                        @endif

                        @if($selectedTag)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                {{ $selectedTag }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Shorts Grid -->
    <section class="py-16 lg:py-24" style="background: white;">
        <div class="container mx-auto px-6">
            @if($shorts->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($shorts as $index => $short)
                <div class="pma-card group cursor-pointer" wire:key="short-{{ $short->id }}"
                     onclick="openShortModal({{ $short->id }})">
                    <!-- Thumbnail -->
                    <div class="aspect-video w-full overflow-hidden rounded-t-lg bg-gray-200 relative">
                        @if($short->thumbnail_url)
                            <img src="{{ $short->thumbnail_url }}"
                                 alt="{{ $short->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif($short->youtube_id)
                            <img src="https://img.youtube.com/vi/{{ $short->youtube_id }}/maxresdefault.jpg"
                                 alt="{{ $short->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='https://img.youtube.com/vi/{{ $short->youtube_id }}/hqdefault.jpg'">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Duration/Type Badge -->
                        <div class="absolute bottom-2 right-2">
                            @if($short->youtube_url)
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">YouTube</span>
                            @else
                                <span class="px-2 py-1 bg-black/70 text-white text-xs font-bold rounded">Video</span>
                            @endif
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="pma-heading text-base mb-2 line-clamp-2" style="color: var(--color-indigo);">
                            {{ $short->title }}
                        </h3>
                        @if($short->tags && count($short->tags) > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($short->tags, 0, 2) as $tag)
                                <span class="px-2 py-0.5 text-xs rounded-full" style="background: var(--color-cream); color: var(--color-olive);">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center" style="background: var(--color-cream);">
                    <svg class="w-10 h-10" style="color: var(--color-olive);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                @if($search || $selectedTag)
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">No shorts found</h2>
                    <p class="pma-body text-lg mb-6" style="color: var(--color-olive);">
                        No shorts match your current filters. Try adjusting your search or clearing filters.
                    </p>
                    <button wire:click="clearFilters" class="pma-btn pma-btn-primary">
                        View All Shorts
                    </button>
                @else
                    <h2 class="pma-heading text-2xl mb-4" style="color: var(--color-indigo);">Coming Soon</h2>
                    <p class="pma-body text-lg" style="color: var(--color-olive);">
                        Short videos will be available here soon. Check back later!
                    </p>
                @endif
            </div>
            @endif
        </div>
    </section>
</div>
