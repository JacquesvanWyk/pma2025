@extends('layouts.public')

@section('title', $study->title . ' - Pioneer Missions Africa')
@section('description', $study->excerpt ?? 'A biblical study from Pioneer Missions Africa')

@section('content')
<!-- Breadcrumb Navigation -->
<div style="background: var(--color-cream); border-bottom: 1px solid rgba(0,0,0,0.1);">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center gap-2 text-sm pma-body">
            <a href="{{ route('home') }}" class="transition-colors" style="color: var(--color-pma-green);">Home</a>
            <span style="color: var(--color-olive);">›</span>
            <a href="{{ route('studies') }}" class="transition-colors" style="color: var(--color-pma-green);">Studies</a>
            <span style="color: var(--color-olive);">›</span>
            <span style="color: var(--color-olive);">{{ $study->title }}</span>
        </div>
    </div>
</div>

<!-- Study Header -->
<section class="relative py-16 lg:py-24 overflow-hidden" style="background: var(--gradient-hero);">
    <div class="pma-light-rays"></div>
    <div class="absolute inset-0 bg-black/40 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Tags -->
            @if($study->tags && $study->tags->count() > 0)
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($study->tags as $tag)
                        <span class="inline-block px-4 py-2 rounded-full text-sm pma-heading-light"
                              style="background: rgba(255, 255, 255, 0.2); color: white;">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Title -->
            <h1 class="text-3xl lg:text-5xl font-bold mb-6 text-white pma-display">
                {{ $study->title }}
            </h1>

            <!-- Metadata -->
            <div class="flex flex-wrap gap-4 text-sm mb-6" style="color: rgba(255, 255, 255, 0.9);">
                <div class="flex items-center gap-2">
                    <span>{{ $study->language === 'afrikaans' ? '🇿🇦 Afrikaans' : '🇬🇧 English' }}</span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $study->published_at->format('F j, Y') }}</span>
                </div>
                @if($study->content)
                    <span>•</span>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ ceil(str_word_count(strip_tags($study->content)) / 200) }} min read</span>
                    </div>
                @endif
            </div>

            <!-- Excerpt -->
            @if($study->excerpt)
                <p class="text-xl leading-relaxed" style="color: rgba(255, 255, 255, 0.9);">
                    {{ $study->excerpt }}
                </p>
            @endif
        </div>
    </div>
</section>

<!-- Main Content Area -->
<section class="py-12 lg:py-16" style="background: white;">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-12 gap-8 max-w-7xl mx-auto">

            <!-- Main Study Content -->
            <div class="lg:col-span-8">
                <div class="pma-card">
                    <div class="p-8">

                        <!-- Featured Image -->
                        @if($study->featured_image)
                            <div class="mb-8 -mx-8 -mt-8">
                                <img src="{{ Storage::url($study->featured_image) }}"
                                     alt="{{ $study->title }}"
                                     class="w-full h-auto rounded-t-lg object-cover"
                                     style="max-height: 400px;">
                            </div>
                        @endif

                        <!-- Study Content -->
                        <div class="prose prose-lg max-w-none pma-body"
                             style="
                                 --tw-prose-body: var(--color-olive);
                                 --tw-prose-headings: var(--color-indigo);
                                 --tw-prose-links: var(--color-pma-green);
                                 --tw-prose-bold: var(--color-indigo);
                                 --tw-prose-quotes: var(--color-ochre);
                                 --tw-prose-quote-borders: var(--color-pma-green);
                                 --tw-prose-code: var(--color-indigo);
                             ">
                            {!! Str::markdown($study->content) !!}
                        </div>

                        <!-- Tags -->
                        @if($study->tags && $study->tags->count() > 0)
                            <div class="mt-12 pt-8" style="border-top: 1px solid var(--color-cream);">
                                <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Topics</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($study->tags as $tag)
                                        <a href="{{ route('studies', ['tags' => [$tag->slug]]) }}"
                                           class="inline-block px-4 py-2 rounded-full text-sm pma-heading-light transition-all"
                                           style="background: var(--color-cream); color: var(--color-pma-green); border: 1px solid var(--color-pma-green-light);">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Study Actions -->
                <div class="mt-6 grid md:grid-cols-2 gap-4">

                    <!-- Share Buttons -->
                    <div class="pma-card p-6">
                        <h4 class="pma-heading text-sm mb-4" style="color: var(--color-indigo);">Share This Study</h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('studies.show', $study->slug)) }}"
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-all"
                               style="background: var(--color-pma-green); color: white;">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('studies.show', $study->slug)) }}&text={{ urlencode($study->title) }}"
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-all"
                               style="background: var(--color-pma-green); color: white;">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ route('studies.show', $study->slug) }}'); alert('Link copied!');"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-all"
                                    style="background: var(--color-cream); color: var(--color-pma-green); border: 1px solid var(--color-pma-green-light);">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy Link
                            </button>
                        </div>
                    </div>

                    <!-- Download Options -->
                    <div class="pma-card p-6">
                        <h4 class="pma-heading text-sm mb-4" style="color: var(--color-indigo);">Download Options</h4>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="window.print();"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-all"
                                    style="background: var(--color-pma-green); color: white;">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Previous/Next Navigation -->
                <div class="mt-8 grid md:grid-cols-2 gap-4">
                    @php
                        $prevStudy = \App\Models\Study::published()
                            ->where('published_at', '<', $study->published_at)
                            ->orderBy('published_at', 'desc')
                            ->first();
                        $nextStudy = \App\Models\Study::published()
                            ->where('published_at', '>', $study->published_at)
                            ->orderBy('published_at', 'asc')
                            ->first();
                    @endphp

                    @if($prevStudy)
                        <a href="{{ route('studies.show', $prevStudy->slug) }}"
                           class="pma-card p-6 group transition-all">
                            <div class="text-xs pma-body mb-2" style="color: var(--color-ochre);">Previous Study</div>
                            <h4 class="pma-heading group-hover:underline" style="color: var(--color-indigo);">
                                {{ $prevStudy->title }}
                            </h4>
                        </a>
                    @endif

                    @if($nextStudy)
                        <a href="{{ route('studies.show', $nextStudy->slug) }}"
                           class="pma-card p-6 group transition-all {{ $prevStudy ? '' : 'md:col-start-2' }}">
                            <div class="text-xs pma-body mb-2" style="color: var(--color-ochre);">Next Study</div>
                            <h4 class="pma-heading group-hover:underline" style="color: var(--color-indigo);">
                                {{ $nextStudy->title }}
                            </h4>
                        </a>
                    @endif
                </div>

                <!-- Back to Studies Button -->
                <div class="mt-8">
                    <a href="{{ route('studies') }}" class="pma-btn pma-btn-secondary">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to All Studies
                    </a>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">

                    <!-- Related Studies -->
                    @php
                        $relatedStudies = \App\Models\Study::published()
                            ->where('id', '!=', $study->id)
                            ->when($study->tags->count() > 0, function($q) use ($study) {
                                $q->whereHas('tags', function($query) use ($study) {
                                    $query->whereIn('tags.id', $study->tags->pluck('id'));
                                });
                            })
                            ->with('tags')
                            ->limit(3)
                            ->latest('published_at')
                            ->get();
                    @endphp

                    @if($relatedStudies->count() > 0)
                        <div class="pma-card p-6">
                            <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">Related Studies</h3>
                            <div class="space-y-4">
                                @foreach($relatedStudies as $related)
                                    <a href="{{ route('studies.show', $related->slug) }}"
                                       class="block group">
                                        <h4 class="pma-heading text-sm mb-2 group-hover:underline" style="color: var(--color-indigo);">
                                            {{ $related->title }}
                                        </h4>
                                        @if($related->tags->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($related->tags->take(2) as $tag)
                                                    <span class="inline-block px-2 py-1 rounded text-xs pma-body"
                                                          style="background: var(--color-cream); color: var(--color-pma-green);">
                                                        {{ $tag->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </a>
                                    @if(!$loop->last)
                                        <div style="border-bottom: 1px solid var(--color-cream);"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- More Resources -->
                    <div class="pma-card p-6">
                        <h3 class="pma-heading text-lg mb-4" style="color: var(--color-indigo);">More Resources</h3>
                        <div class="space-y-3">
                            <a href="{{ route('resources.tracts') }}"
                               class="flex items-center gap-3 p-3 rounded-lg transition-all"
                               style="background: var(--color-cream);">
                                <svg class="h-5 w-5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="pma-body text-sm" style="color: var(--color-indigo);">Gospel Tracts</span>
                            </a>
                            <a href="{{ route('resources.ebooks') }}"
                               class="flex items-center gap-3 p-3 rounded-lg transition-all"
                               style="background: var(--color-cream);">
                                <svg class="h-5 w-5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="pma-body text-sm" style="color: var(--color-indigo);">E-Books</span>
                            </a>
                            <a href="{{ route('sermons') }}"
                               class="flex items-center gap-3 p-3 rounded-lg transition-all"
                               style="background: var(--color-cream);">
                                <svg class="h-5 w-5 flex-shrink-0" style="color: var(--color-pma-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                </svg>
                                <span class="pma-body text-sm" style="color: var(--color-indigo);">Sermons</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
