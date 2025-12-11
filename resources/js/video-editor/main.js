import { createApp } from 'vue';
import VideoEditor from './VideoEditor.vue';

const mountElement = document.getElementById('video-editor-app');

if (mountElement) {
    const app = createApp(VideoEditor, {
        audioUrl: mountElement.dataset.audioUrl || '',
        lyrics: JSON.parse(mountElement.dataset.lyrics || '[]'),
        projectId: mountElement.dataset.projectId || null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
    });

    app.mount('#video-editor-app');
}
