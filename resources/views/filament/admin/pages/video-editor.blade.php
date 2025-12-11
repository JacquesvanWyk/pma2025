<x-filament-panels::page>
    @push('styles')
    @vite('resources/js/video-editor/main.js')
    @endpush

    <div class="video-editor-wrapper" style="margin: -1.5rem; height: calc(100vh - 4rem);">
        <div
            id="video-editor-app"
            data-audio-url="{{ $audioUrl }}"
            data-lyrics="{{ $this->getLyricsJson() }}"
            data-project-id="{{ $projectId }}"
        ></div>
    </div>
</x-filament-panels::page>
