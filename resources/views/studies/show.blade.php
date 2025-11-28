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
                    <span>{{ $study->language === 'afrikaans' ? 'Afrikaans' : 'English' }}</span>
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
                        <span>{{ $study->reading_time }} min read</span>
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
                <div class="pma-card shadow-lg border-0 ring-1 ring-black/5 overflow-hidden">
                    <div class="p-8 lg:p-12">

                        <!-- Featured Image -->
                        @if($study->featured_image)
                            <div class="mb-10 -mx-8 -mt-8 lg:-mx-12 lg:-mt-12">
                                <img src="{{ Storage::url($study->featured_image) }}"
                                     alt="{{ $study->title }}"
                                     class="w-full h-auto object-cover shadow-sm"
                                     style="max-height: 500px;">
                            </div>
                        @endif

                        <!-- Study Content -->
                        <div class="prose prose-lg lg:prose-xl max-w-none pma-body marker:text-[var(--color-pma-green)]"
                             style="
                                 --tw-prose-body: #334155;
                                 --tw-prose-headings: var(--color-indigo);
                                 --tw-prose-links: var(--color-pma-green);
                                 --tw-prose-bold: var(--color-indigo);
                                 --tw-prose-quotes: var(--color-indigo);
                                 --tw-prose-quote-borders: var(--color-pma-green);
                                 --tw-prose-code: var(--color-indigo);
                             ">
                            @if(is_array($study->content))
                                @foreach($study->content as $block)
                                    @if($block['type'] === 'text')
                                        {!! $block['data']['content'] !!}
                                    @elseif($block['type'] === 'image')
                                        <figure class="my-8">
                                            <img src="{{ Storage::url($block['data']['url']) }}"
                                                 alt="{{ $block['data']['alt'] }}"
                                                 class="w-full rounded-lg shadow-md">
                                            @if(!empty($block['data']['caption']))
                                                <figcaption class="text-center text-gray-500 text-sm mt-2 italic">{{ $block['data']['caption'] }}</figcaption>
                                            @endif
                                        </figure>
                                    @elseif($block['type'] === 'video')
                                        @php
                                            $videoUrl = $block['data']['url'];
                                            // Simple converter for YouTube URLs
                                            if (str_contains($videoUrl, 'watch?v=')) {
                                                $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                                // Remove extra query params
                                                if (str_contains($videoUrl, '&')) {
                                                    $videoUrl = substr($videoUrl, 0, strpos($videoUrl, '&'));
                                                }
                                            } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                                $videoUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $videoUrl);
                                            }
                                        @endphp
                                        <div class="my-8" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; border-radius: 0.75rem;">
                                            <iframe src="{{ $videoUrl }}"
                                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                {{-- Fallback for legacy content --}}
                                {!! Str::markdown($study->content ?? '') !!}
                            @endif
                        </div>

                        <!-- Tags -->
                        @if($study->tags && $study->tags->count() > 0)
                            <div class="mt-16 pt-8 border-t border-gray-100">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Topics</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($study->tags as $tag)
                                        <a href="{{ route('studies', ['tags' => [$tag->slug]]) }}"
                                           class="inline-block px-4 py-2 rounded-full text-sm font-medium transition-all hover:-translate-y-0.5"
                                           style="background: var(--color-cream); color: var(--color-pma-green);">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Study Actions -->
                <div class="mt-8 grid md:grid-cols-2 gap-6">

                    <!-- Share Buttons -->
                    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                        <h4 class="font-bold text-[var(--color-indigo)] mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share This Study
                        </h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('studies.show', $study->slug)) }}"
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all hover:opacity-90 text-white"
                               style="background: #1877F2;">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('studies.show', $study->slug)) }}&text={{ urlencode($study->title) }}"
                               target="_blank"
                               class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all hover:opacity-90 text-white"
                               style="background: #1DA1F2;">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                Twitter
                            </a>
                            <button onclick="copyToClipboard(this, '{{ route('studies.show', $study->slug) }}')"
                                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all hover:bg-gray-50 border border-gray-200 text-gray-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy Link
                            </button>
                        </div>
                    </div>

                    <!-- Download Options -->
                    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                        <h4 class="font-bold text-[var(--color-indigo)] mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Save for Later
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="window.print();"
                                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all hover:bg-[var(--color-pma-green-dark)] text-white"
                                    style="background: var(--color-pma-green);">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print / PDF
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Previous/Next Navigation -->
                <div class="mt-8 grid md:grid-cols-2 gap-6">
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
                           class="group p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-left block">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Previous Study</span>
                            <h4 class="font-bold text-[var(--color-indigo)] group-hover:text-[var(--color-pma-green)] transition-colors">
                                {{ $prevStudy->title }}
                            </h4>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($nextStudy)
                        <a href="{{ route('studies.show', $nextStudy->slug) }}"
                           class="group p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-right block">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Next Study</span>
                            <h4 class="font-bold text-[var(--color-indigo)] group-hover:text-[var(--color-pma-green)] transition-colors">
                                {{ $nextStudy->title }}
                            </h4>
                        </a>
                    @endif
                </div>

                <!-- Back to Studies Button -->
                <div class="mt-12 text-center">
                    <a href="{{ route('studies') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to All Studies
                    </a>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Table of Contents (Auto-generated could be here, static for now) -->
                <div class="bg-[var(--color-cream)] rounded-2xl p-8 sticky top-28">
                    <h3 class="font-bold text-[var(--color-indigo)] text-lg mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--color-pma-green)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        About This Study
                    </h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between border-b border-black/5 pb-2">
                            <span class="text-gray-500">Author</span>
                            <span class="font-medium text-[var(--color-indigo)]">PMA Ministry</span>
                        </div>
                        <div class="flex justify-between border-b border-black/5 pb-2">
                            <span class="text-gray-500">Published</span>
                            <span class="font-medium text-[var(--color-indigo)]">{{ $study->published_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-black/5 pb-2">
                            <span class="text-gray-500">Read Time</span>
                            <span class="font-medium text-[var(--color-indigo)]">~{{ $study->reading_time }} min</span>
                        </div>
                        <div class="flex justify-between border-b border-black/5 pb-2">
                            <span class="text-gray-500">Language</span>
                            <span class="font-medium text-[var(--color-indigo)]">{{ ucfirst($study->language) }}</span>
                        </div>
                    </div>
                </div>

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
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-[var(--color-indigo)] text-lg mb-6">Related Studies</h3>
                        <div class="space-y-6">
                            @foreach($relatedStudies as $related)
                                <a href="{{ route('studies.show', $related->slug) }}"
                                   class="block group">
                                    <h4 class="font-bold text-gray-900 mb-2 group-hover:text-[var(--color-pma-green)] transition-colors leading-snug">
                                        {{ $related->title }}
                                    </h4>
                                    <div class="text-xs text-gray-500 mb-2">{{ $related->published_at->format('M d, Y') }}</div>
                                    @if($related->tags->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($related->tags->take(2) as $tag)
                                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </a>
                                @if(!$loop->last)
                                    <div class="border-b border-gray-100"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>

@push('styles')
<style>
    /* Custom Study Typography */
    .prose blockquote {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-weight: 600;
        font-style: italic;
        font-size: 1.5rem;
        line-height: 1.6;
        color: var(--color-indigo);
        border-left: none;
        padding: 2.5rem;
        margin: 3rem 0;
        background: var(--color-cream);
        border-radius: 1rem;
        position: relative;
        box-shadow: inset 4px 0 0 var(--color-pma-green);
    }
    
    .prose blockquote::before {
        content: '“';
        position: absolute;
        top: 0.5rem;
        left: 1.5rem;
        font-size: 4rem;
        color: var(--color-pma-green);
        opacity: 0.2;
        line-height: 1;
        font-family: serif;
    }

    /* Verse Styling (if wrapped in .verse class or similar, or simple blockquote override logic) */
    /* Since user asked for verses styling, let's style them distinctively. 
       Assuming they might be just blockquotes in the seeded content, but maybe we can target them if they contain citations. 
       For now, the above blockquote style is rich enough for both. */
    
    .prose h3 {
        color: var(--color-indigo);
        font-family: "Plus Jakarta Sans", sans-serif;
        font-weight: 800;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--color-cream);
    }

    .prose p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }

    .prose strong {
        color: var(--color-pma-green-dark);
        font-weight: 700;
    }

    .prose ul {
        list-style-type: none;
        padding-left: 0;
    }

    .prose li {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .prose li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.6rem;
        width: 6px;
        height: 6px;
        background-color: var(--color-pma-green);
        border-radius: 50%;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .pma-card, .pma-card * {
            visibility: visible;
        }
        .pma-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }
        .prose {
            width: 100%;
            max-width: none;
        }
        /* No specific hiding for pma-card img to ensure it prints */
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard(button, text) {
        console.log('copyToClipboard function called with text:', text); // Debugging
        navigator.clipboard.writeText(text).then(() => {
            console.log('Text successfully copied!'); // Debugging
            const originalContent = button.innerHTML;
            // Change to checkmark
            button.innerHTML = `
                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-green-600">Copied!</span>
            `;
            
            setTimeout(() => {
                button.innerHTML = originalContent;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy to clipboard:', err); // More explicit error
            alert('Failed to copy link. Please try again or copy manually.'); // User feedback on error
        });
    }
</script>
@endpush


@endsection
