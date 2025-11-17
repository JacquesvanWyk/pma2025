<!-- Latest Sermons -->
<section class="py-20 lg:py-32" style="background: var(--gradient-spiritual);">
    <div class="container mx-auto px-6">
        <div class="pma-section-header pma-animate-on-scroll">
            <div class="pma-accent-line mx-auto mb-4"></div>
            <h2 class="pma-section-title pma-heading" style="color: var(--color-indigo);">Latest Sermons</h2>
            <p class="pma-section-subtitle pma-body">
                Watch our latest messages proclaiming present truth
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestSermons as $index => $sermon)
            <div class="pma-card pma-animate-on-scroll pma-stagger-{{ ($index % 6) + 1 }}">
                <div class="aspect-video w-full overflow-hidden rounded-t-lg">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $sermon->youtube_id }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="p-6">
                    <h3 class="pma-heading text-xl mb-2" style="color: var(--color-indigo);">
                        {!! $sermon->title !!}
                    </h3>
                    <p class="pma-body mb-2" style="color: var(--color-olive);">{!! $sermon->speaker !!}</p>
                    <div class="text-sm pma-body" style="color: var(--color-ochre);">
                        {{ $sermon->date_preached->format('F j, Y') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12 pma-animate-on-scroll">
            <a href="{{ route('sermons') }}" class="pma-btn pma-btn-primary">View All Sermons</a>
        </div>
    </div>
</section>
