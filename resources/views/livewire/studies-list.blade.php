<div>
    <!-- Filters Section -->
    <section id="filters" class="sticky top-16 z-40 py-6" style="background: var(--color-cream); border-bottom: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div class="container mx-auto px-6">
            <div class="space-y-4">
                <!-- Main Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search studies..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-2 transition-all"
                                   style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                        </div>
                    </div>

                    <!-- Language -->
                    <div>
                        <select wire:model.live="language"
                                class="w-full px-4 py-2 rounded-lg border-2 transition-all"
                                style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                            <option value="all">All Languages</option>
                            <option value="english">English</option>
                            <option value="afrikaans">Afrikaans</option>                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <select wire:model.live="sort"
                                class="w-full px-4 py-2 rounded-lg border-2 transition-all"
                                style="border-color: var(--color-pma-green-light); focus:border-color: var(--color-pma-green); outline: none;">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title-asc">Title A-Z</option>
                            <option value="title-desc">Title Z-A</option>
                        </select>
                    </div>
                </div>

                <!-- Topic Tags Row -->
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="pma-body text-sm font-semibold" style="color: var(--color-indigo);">Topics:</span>

                    @foreach($topics as $slug => $name)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="selectedTags"
                                   value="{{ $slug }}"
                                   class="hidden peer">
                            <span class="px-4 py-2 rounded-full text-sm pma-heading-light transition-all peer-checked:text-white"
                                  style="background: {{ in_array($slug, $selectedTags) ? 'var(--color-pma-green)' : 'white' }}; border: 2px solid var(--color-pma-green); color: {{ in_array($slug, $selectedTags) ? 'white' : 'var(--color-pma-green)' }};">
                                {{ $name }}
                            </span>
                        </label>
                    @endforeach

                    @if($search || !empty($selectedTags) || ($language && $language !== 'all'))
                        <button wire:click="clearFilters"
                                class="ml-4 px-4 py-2 rounded-full text-sm pma-body"
                                style="background: var(--color-terracotta); color: white;">
                            Clear Filters
                        </button>
                    @endif
                </div>

                <!-- Active Filters Display -->
                @if($search || !empty($selectedTags) || ($language && $language !== 'all'))
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Active filters:</span>

                        @if($search)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                Search: "{{ $search }}"
                            </span>
                        @endif

                        @if($language && $language !== 'all')
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                {{ $language === 'english' ? 'English' : 'Afrikaans' }}
                            </span>
                        @endif

                        @if(!empty($selectedTags))
                            @foreach($selectedTags as $tagSlug)
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                      style="background: var(--color-pma-green-light); color: white;">
                                    {{ $topics[$tagSlug] ?? $tagSlug }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Studies Grid -->
    <section class="py-20 lg:py-32" style="background: white;">
        <div class="container mx-auto px-6">
            @if($studies->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($studies as $index => $study)
                        <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }} group">
                            <a href="{{ route('studies.show', $study->slug) }}" class="block p-6">
                                <!-- Featured Image or Gradient -->
                                <div class="w-full h-48 mb-4 rounded-lg overflow-hidden"
                                     style="background: linear-gradient(135deg, var(--color-pma-green-light), var(--color-pma-green));">
                                    @if($study->featured_image)
                                        <img src="{{ Storage::url($study->featured_image) }}"
                                             alt="{{ $study->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tags -->
                                @if($study->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($study->tags as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full text-xs pma-heading-light"
                                                  style="background: var(--color-pma-green); color: white;">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Title -->
                                <h3 class="pma-heading text-xl mb-2 group-hover:underline" style="color: var(--color-indigo);">
                                    {{ $study->title }}
                                </h3>

                                <!-- Excerpt -->
                                @if($study->excerpt)
                                    <p class="pma-body text-sm mb-4 line-clamp-3" style="color: var(--color-olive);">
                                        {{ $study->excerpt }}
                                    </p>
                                @endif

                                <!-- Metadata -->
                                <div class="flex items-center gap-4 text-xs pma-body" style="color: var(--color-ochre);">
                                    <span>{{ $study->language === 'afrikaans' ? 'Afrikaans' : 'English' }}</span>
                                    <span>•</span>
                                    <span>{{ $study->published_at->format('M d, Y') }}</span>
                                    @if($study->content)
                                        <span>•</span>
                                        <span>{{ $study->reading_time }} min read</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $studies->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <svg class="mx-auto h-24 w-24 mb-6" style="color: var(--color-olive-light);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="pma-heading text-2xl mb-3" style="color: var(--color-indigo);">No studies found</h3>
                    <p class="pma-body mb-6" style="color: var(--color-olive);">
                        We couldn't find any studies matching your filters. Try adjusting your search or removing some filters.
                    </p>
                    <button wire:click="clearFilters" class="pma-btn pma-btn-primary">
                        View All Studies
                    </button>
                </div>
            @endif
        </div>
    </section>
</div>
