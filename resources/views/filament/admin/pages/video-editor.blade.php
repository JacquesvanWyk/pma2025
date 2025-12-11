<x-filament-panels::page>
    @push('styles')
    @vite('resources/js/video-editor/main.js')
    @endpush
    {{-- Cache bust: v2 --}}

    <div class="video-editor-wrapper" style="margin: -1.5rem; height: calc(100vh - 4rem);">
        <div
            id="video-editor-app"
            data-audio-url="{{ $audioUrl }}"
            data-lyrics="{{ $this->getLyricsJson() }}"
            data-project-id="{{ $projectId }}"
            data-project="{{ $this->getProjectDataJson() }}"
            data-projects-list-url="{{ route('filament.admin.resources.video-projects.index') }}"
            data-livewire-id="{{ $this->getId() }}"
        ></div>
    </div>
</x-filament-panels::page>
