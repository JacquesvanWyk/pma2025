import { createApp } from 'vue';
import VideoEditor from './VideoEditor.vue';

const mountElement = document.getElementById('video-editor-app');

if (mountElement) {
    const projectData = mountElement.dataset.project;
    let project = null;
    try {
        project = projectData && projectData !== 'null' ? JSON.parse(projectData) : null;
    } catch (e) {
        console.error('Failed to parse project data:', e);
    }

    const app = createApp(VideoEditor, {
        audioUrl: mountElement.dataset.audioUrl || '',
        lyrics: JSON.parse(mountElement.dataset.lyrics || '[]'),
        projectId: mountElement.dataset.projectId || null,
        project: project,
        projectsListUrl: mountElement.dataset.projectsListUrl || '',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
        livewireId: mountElement.dataset.livewireId || null,
    });

    app.mount('#video-editor-app');
}
