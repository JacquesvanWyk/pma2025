<?php

use App\Models\PictureStudy;
use App\Models\Tag;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $language = '';
    public array $selectedTags = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedLanguage(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedTags(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->language = '';
        $this->selectedTags = [];
        $this->resetPage();
    }

    public function with(): array
    {
        $query = PictureStudy::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with('tags');

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', fn ($q) => $q->whereIn('slug', $this->selectedTags));
        }

        if ($this->language) {
            $query->where('language', $this->language);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $pictureStudies = $query->latest('published_at')->paginate(12);

        $tags = Tag::query()
            ->whereHas('pictureStudies', fn ($q) => $q->where('status', 'published'))
            ->orderBy('name')
            ->get();

        return compact('pictureStudies', 'tags');
    }
}; ?>

<div>
    <!-- Filters Section -->
    <section class="sticky top-16 z-40 py-6" style="background: var(--color-cream); border-bottom: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div class="container mx-auto px-6">
            <div class="space-y-4">
                <!-- Main Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search picture studies..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-2 transition-all"
                                   style="border-color: var(--color-pma-green-light); outline: none;">
                        </div>
                    </div>

                    <!-- Language -->
                    <div>
                        <select wire:model.live="language"
                                class="w-full px-4 py-2 rounded-lg border-2 transition-all"
                                style="border-color: var(--color-pma-green-light); outline: none;">
                            <option value="">All Languages</option>
                            <option value="en">English</option>
                            <option value="af">Afrikaans</option>
                        </select>
                    </div>
                </div>

                <!-- Topic Tags Row -->
                @if($tags->count() > 0)
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="pma-body text-sm font-semibold" style="color: var(--color-indigo);">Topics:</span>

                    @foreach($tags as $tagItem)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="selectedTags"
                                   value="{{ $tagItem->slug }}"
                                   class="hidden peer">
                            <span class="px-4 py-2 rounded-full text-sm pma-heading-light transition-all peer-checked:text-white"
                                  style="background: {{ in_array($tagItem->slug, $selectedTags) ? 'var(--color-pma-green)' : 'white' }}; border: 2px solid var(--color-pma-green); color: {{ in_array($tagItem->slug, $selectedTags) ? 'white' : 'var(--color-pma-green)' }};">
                                {{ $tagItem->name }}
                            </span>
                        </label>
                    @endforeach

                    @if($search || !empty($selectedTags) || $language)
                        <button wire:click="clearFilters"
                                class="ml-4 px-4 py-2 rounded-full text-sm pma-body"
                                style="background: var(--color-terracotta); color: white;">
                            Clear Filters
                        </button>
                    @endif
                </div>
                @endif

                <!-- Active Filters Display -->
                @if($search || !empty($selectedTags) || $language)
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="pma-body text-sm" style="color: var(--color-olive);">Active filters:</span>

                        @if($search)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                Search: "{{ $search }}"
                            </span>
                        @endif

                        @if($language)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                  style="background: var(--color-pma-green-light); color: white;">
                                {{ $language === 'en' ? 'English' : 'Afrikaans' }}
                            </span>
                        @endif

                        @if(!empty($selectedTags))
                            @foreach($selectedTags as $tagSlug)
                                @php
                                    $tagName = $tags->firstWhere('slug', $tagSlug)?->name ?? $tagSlug;
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm pma-body"
                                      style="background: var(--color-pma-green-light); color: white;">
                                    {{ $tagName }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                @endif

                <div wire:loading class="text-sm" style="color: var(--color-olive);">
                    <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Picture Studies Grid -->
    <section class="py-20 lg:py-32" style="background: white;">
        <div class="container mx-auto px-6">
            <!-- Print Tip Banner -->
            <div class="mb-8 p-4 rounded-lg border flex items-center gap-4"
                 style="background: var(--color-cream); border-color: var(--color-cream-dark);">
                <div class="shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </div>
                <p class="pma-body" style="color: var(--color-indigo);">
                    <strong>Tip:</strong> Print these pictures in A5 for best use as short studies or tracts.
                </p>
            </div>

            @if($pictureStudies->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" style="color: var(--color-olive);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h2 class="pma-heading text-2xl mb-2" style="color: var(--color-indigo);">No picture studies found</h2>
                    <p class="pma-body" style="color: var(--color-olive);">
                        @if($search || $language || $tag)
                            Try adjusting your filters or search terms.
                        @else
                            Check back soon for new picture studies.
                        @endif
                    </p>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($pictureStudies as $study)
                    <div wire:key="study-{{ $study->id }}" class="pma-card overflow-hidden group">
                        <div class="aspect-video overflow-hidden bg-gray-100 cursor-pointer"
                             onclick="openLightbox('{{ asset('storage/' . $study->image_path) }}', '{{ addslashes($study->title) }}')">
                            <img src="{{ asset('storage/' . $study->image_path) }}"
                                 alt="{{ $study->title }}"
                                 class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-block px-2 py-1 rounded text-xs pma-heading-light"
                                      style="background: var(--color-pma-green); color: white;">
                                    {{ $study->language === 'en' ? 'English' : 'Afrikaans' }}
                                </span>
                                <span class="text-xs" style="color: var(--color-olive);">
                                    {{ number_format($study->download_count) }} downloads
                                </span>
                            </div>

                            <h3 class="pma-heading text-lg mb-2" style="color: var(--color-indigo);">
                                {{ $study->title }}
                            </h3>

                            @if($study->description)
                            <p class="pma-body text-sm mb-3" style="color: var(--color-olive);">
                                {{ Str::limit($study->description, 80) }}
                            </p>
                            @endif

                            @if($study->tags->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($study->tags->take(3) as $studyTag)
                                        <span class="inline-block px-2 py-0.5 rounded text-xs"
                                              style="background: var(--color-cream-dark); color: var(--color-indigo);">
                                            {{ $studyTag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <a href="{{ route('picture-study.download', $study) }}"
                               class="pma-btn pma-btn-secondary w-full inline-flex items-center justify-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $pictureStudies->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/95 backdrop-blur-sm" onclick="closeLightbox(event)">
        <button onclick="closeLightbox(event)" class="absolute top-4 right-4 z-10 p-2 text-white/80 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="absolute inset-0 flex items-center justify-center p-4 md:p-8">
            <div class="relative max-w-6xl max-h-full w-full h-full flex flex-col items-center justify-center" onclick="event.stopPropagation()">
                <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
                <p id="lightbox-title" class="mt-4 text-white text-lg pma-heading text-center"></p>
            </div>
        </div>
    </div>

    <script>
        function openLightbox(imageSrc, title) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxTitle = document.getElementById('lightbox-title');

            lightboxImage.src = imageSrc;
            lightboxImage.alt = title;
            lightboxTitle.textContent = title;

            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox(event) {
            if (event) {
                event.stopPropagation();
            }
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const lightbox = document.getElementById('lightbox');
                if (!lightbox.classList.contains('hidden')) {
                    lightbox.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
</div>
