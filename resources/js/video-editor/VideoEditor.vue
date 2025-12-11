<template>
    <div class="video-editor" :class="{ 'dark': isDark }">
        <!-- Upload Screen (shown when no audio) -->
        <div v-if="!localAudioUrl && !isUploading" class="upload-screen">
            <div class="upload-container">
                <div class="upload-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 18V5l12-2v13"/>
                        <circle cx="6" cy="18" r="3"/>
                        <circle cx="18" cy="16" r="3"/>
                    </svg>
                </div>
                <h2>Create a Lyric Video</h2>
                <p>Upload an audio file to get started</p>

                <div class="upload-options">
                    <label class="upload-btn primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Upload Audio File
                        <input
                            type="file"
                            accept="audio/*"
                            @change="onAudioFileSelect"
                            class="hidden-input"
                        />
                    </label>

                    <div class="upload-divider">
                        <span>or</span>
                    </div>

                    <div class="url-input-group">
                        <input
                            type="text"
                            v-model="audioUrlInput"
                            placeholder="Paste audio URL..."
                            class="url-input"
                            @keyup.enter="loadAudioFromUrl"
                        />
                        <button class="upload-btn secondary" @click="loadAudioFromUrl" :disabled="!audioUrlInput">
                            Load
                        </button>
                    </div>
                </div>

                <div class="upload-hints">
                    <p>Supported formats: MP3, WAV, M4A, OGG</p>
                    <p>Tip: Use AI-generated music from the Music Generator</p>
                </div>
            </div>
        </div>

        <!-- Uploading State -->
        <div v-else-if="isUploading" class="upload-screen">
            <div class="upload-container">
                <div class="spinner large"></div>
                <h2>Uploading Audio...</h2>
                <p>{{ uploadProgress }}%</p>
            </div>
        </div>

        <!-- Main Editor (shown when audio is loaded) -->
        <template v-else>
        <!-- Top Toolbar -->
        <div class="editor-toolbar">
            <div class="toolbar-left">
                <button class="toolbar-btn" @click="undo" :disabled="!canUndo" title="Undo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 10h10a5 5 0 0 1 5 5v2M3 10l4-4M3 10l4 4"/>
                    </svg>
                </button>
                <button class="toolbar-btn" @click="redo" :disabled="!canRedo" title="Redo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10H11a5 5 0 0 0-5 5v2M21 10l-4-4M21 10l-4 4"/>
                    </svg>
                </button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn" @click="autoDetectTimestamps" :disabled="isProcessing" title="Auto-detect timestamps with AI">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"/>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3M8 23h8"/>
                    </svg>
                    <span v-if="isProcessing">Detecting...</span>
                    <span v-else>Auto-Detect</span>
                </button>
            </div>
            <div class="toolbar-center">
                <span class="project-title">{{ projectTitle || 'Lyric Video Editor' }}</span>
            </div>
            <div class="toolbar-right">
                <select v-model="outputSize" class="size-select">
                    <option value="1920x1080">YouTube (1920x1080)</option>
                    <option value="1080x1920">Shorts (1080x1920)</option>
                    <option value="1080x1080">Square (1080x1080)</option>
                </select>
                <button class="toolbar-btn primary" @click="exportVideo" :disabled="isExporting">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <span v-if="isExporting">Exporting...</span>
                    <span v-else>Export</span>
                </button>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="editor-content">
            <!-- Left Panel: Lyrics List -->
            <div class="panel lyrics-panel">
                <div class="panel-header">
                    <h3>Lyrics</h3>
                    <button class="btn-sm" @click="addLyricLine" title="Add line">+</button>
                </div>
                <div class="lyrics-list">
                    <div
                        v-for="(lyric, index) in localLyrics"
                        :key="index"
                        class="lyric-item"
                        :class="{ 'active': currentLyricIndex === index, 'selected': selectedLyricIndex === index }"
                        @click="selectLyric(index)"
                        draggable="true"
                        @dragstart="onDragStart($event, index)"
                        @dragover.prevent
                        @drop="onDrop($event, index)"
                    >
                        <div class="lyric-handle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/>
                                <circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/>
                                <circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/>
                            </svg>
                        </div>
                        <div class="lyric-content">
                            <input
                                type="text"
                                v-model="lyric.text"
                                class="lyric-text-input"
                                placeholder="Enter lyric text..."
                                @focus="selectLyric(index)"
                            />
                            <div class="lyric-timing">
                                <input
                                    type="text"
                                    :value="formatTimeEditable(lyric.start_ms)"
                                    @change="updateStartTime($event, index)"
                                    @click.stop
                                    class="timing-input"
                                    title="Start time (m:ss.ms)"
                                />
                                <span class="timing-arrow">→</span>
                                <input
                                    type="text"
                                    :value="formatTimeEditable(lyric.end_ms)"
                                    @change="updateEndTime($event, index)"
                                    @click.stop
                                    class="timing-input"
                                    title="End time (m:ss.ms)"
                                />
                            </div>
                            <div class="lyric-animation">
                                <select v-model="lyric.animation" class="animation-select" @click.stop>
                                    <option value="fade">Fade</option>
                                    <option value="slide">Slide</option>
                                    <option value="karaoke">Karaoke</option>
                                    <option value="typewriter">Typewriter</option>
                                </select>
                            </div>
                        </div>
                        <div class="lyric-actions">
                            <button
                                v-if="index > 0 && canStitch(index - 1, index)"
                                class="btn-icon stitch"
                                @click.stop="stitchSlides(index - 1, index)"
                                title="Merge with previous"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </button>
                            <button
                                class="btn-icon split"
                                @click.stop="splitSlideAtPlayhead(index)"
                                title="Split slide"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="3" x2="12" y2="21"/><polyline points="8 8 12 3 16 8"/><polyline points="8 16 12 21 16 16"/>
                                </svg>
                            </button>
                            <button class="btn-icon delete" @click.stop="deleteLyric(index)" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Preview -->
            <div class="panel preview-panel">
                <div class="preview-container" :style="previewStyle">
                    <div class="preview-background" :style="backgroundStyle">
                        <!-- Video Background -->
                        <video
                            v-if="style.backgroundVideo"
                            :src="style.backgroundVideo"
                            class="preview-video-bg"
                            autoplay
                            loop
                            muted
                            playsinline
                        ></video>

                        <!-- Lyric Text -->
                        <div class="preview-lyric" :style="lyricStyle" v-if="currentLyric">
                            {{ currentLyric.text }}
                        </div>
                        <div class="preview-placeholder" v-else>
                            Preview will appear here
                        </div>

                        <!-- Logo -->
                        <img
                            v-if="style.logo"
                            :src="style.logo"
                            class="preview-logo"
                            :class="'logo-' + style.logoPosition"
                            :style="{ width: style.logoSize + 'px' }"
                        />
                    </div>
                </div>

                <!-- Playback Controls -->
                <div class="playback-controls">
                    <button class="control-btn" @click="skipBackward" title="Back 5s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="11 19 2 12 11 5 11 19"/>
                            <polygon points="22 19 13 12 22 5 22 19"/>
                        </svg>
                    </button>
                    <button class="control-btn play-btn" @click="togglePlayback">
                        <svg v-if="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
                        </svg>
                    </button>
                    <button class="control-btn" @click="skipForward" title="Forward 5s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 19 22 12 13 5 13 19"/>
                            <polygon points="2 19 11 12 2 5 2 19"/>
                        </svg>
                    </button>
                    <div class="time-display">
                        <span>{{ formatTime(currentTimeMs) }}</span>
                        <span class="time-separator">/</span>
                        <span>{{ formatTime(durationMs) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Styling -->
            <div class="panel style-panel">
                <div class="panel-header">
                    <h3>Style</h3>
                </div>
                <div class="style-sections">
                    <!-- Background -->
                    <div class="style-section">
                        <h4>Background</h4>
                        <div class="style-row">
                            <label>Color</label>
                            <input type="color" v-model="style.backgroundColor" class="color-input" />
                        </div>
                        <div class="style-row">
                            <label>Image</label>
                            <input type="file" accept="image/*" @change="onBackgroundImageChange" class="file-input" />
                        </div>
                        <div class="style-row">
                            <label>Video</label>
                            <input type="file" accept="video/mp4,video/webm,video/ogg,.mp4,.webm,.mov" @change="onBackgroundVideoChange" class="file-input" />
                        </div>
                        <button v-if="style.backgroundImage || style.backgroundVideo" class="btn-sm clear-btn" @click="clearBackground">
                            Clear Background
                        </button>
                    </div>

                    <!-- Logo -->
                    <div class="style-section">
                        <h4>Logo</h4>
                        <div class="style-row">
                            <label>Image</label>
                            <input type="file" accept="image/*" @change="onLogoChange" class="file-input" />
                        </div>
                        <div class="style-row" v-if="style.logo">
                            <label>Position</label>
                            <select v-model="style.logoPosition" class="style-select">
                                <option value="bottom-left">Bottom Left</option>
                                <option value="bottom-right">Bottom Right</option>
                                <option value="top-left">Top Left</option>
                                <option value="top-right">Top Right</option>
                            </select>
                        </div>
                        <div class="style-row" v-if="style.logo">
                            <label>Size</label>
                            <input type="range" v-model.number="style.logoSize" min="40" max="200" class="range-input" />
                            <span class="range-value">{{ style.logoSize }}px</span>
                        </div>
                        <button v-if="style.logo" class="btn-sm clear-btn" @click="clearLogo">
                            Remove Logo
                        </button>
                    </div>

                    <!-- Text Style -->
                    <div class="style-section">
                        <h4>Text</h4>
                        <div class="style-row">
                            <label>Font</label>
                            <select v-model="style.fontFamily" class="style-select">
                                <optgroup label="System Fonts">
                                    <option value="Arial">Arial</option>
                                    <option value="Georgia">Georgia</option>
                                </optgroup>
                                <optgroup label="Google Fonts">
                                    <option value="Inter">Inter</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Poppins">Poppins</option>
                                    <option value="Open Sans">Open Sans</option>
                                    <option value="Lato">Lato</option>
                                    <option value="Oswald">Oswald</option>
                                    <option value="Playfair Display">Playfair Display</option>
                                    <option value="Bebas Neue">Bebas Neue</option>
                                    <option value="Dancing Script">Dancing Script</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="style-row">
                            <label>Size</label>
                            <input type="range" v-model.number="style.fontSize" min="24" max="120" class="range-input" />
                            <span class="range-value">{{ style.fontSize }}px</span>
                        </div>
                        <div class="style-row">
                            <label>Color</label>
                            <input type="color" v-model="style.fontColor" class="color-input" />
                        </div>
                        <div class="style-row">
                            <label>Position</label>
                            <select v-model="style.textPosition" class="style-select">
                                <option value="top">Top</option>
                                <option value="center">Center</option>
                                <option value="bottom">Bottom</option>
                            </select>
                        </div>
                    </div>

                    <!-- Animation -->
                    <div class="style-section">
                        <h4>Animation</h4>
                        <div class="style-row">
                            <label>Effect</label>
                            <select v-model="selectedAnimation" class="style-select">
                                <option value="fade">Fade</option>
                                <option value="slide">Slide Up</option>
                                <option value="karaoke">Karaoke</option>
                                <option value="typewriter">Typewriter</option>
                            </select>
                        </div>
                        <button class="btn-full" @click="applyAnimationToAll">Apply to All</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline-section">
            <div class="timeline-header">
                <div class="timeline-labels">
                    <span class="track-label">Audio</span>
                    <span class="slide-count" v-if="localLyrics.length">{{ localLyrics.length }} slides</span>
                </div>
                <div class="zoom-controls">
                    <button class="btn-sm" @click="zoomToFit" title="Fit all slides">Fit</button>
                    <button class="btn-sm" @click="zoomOut" :disabled="zoomLevel <= 0.25">-</button>
                    <span>{{ Math.round(zoomLevel * 100) }}%</span>
                    <button class="btn-sm" @click="zoomIn" :disabled="zoomLevel >= 5">+</button>
                </div>
            </div>

            <!-- Waveform Track -->
            <div class="timeline-tracks" ref="timelineTracks" @scroll="onTimelineScroll">
                <div class="track-row">
                    <div class="track-content" :style="{ width: waveformWidth + 'px' }">
                        <div ref="waveform" class="waveform"></div>
                    </div>
                </div>

                <!-- Slides Track -->
                <div class="track-row slides-track">
                    <div class="track-label-inline">Slides</div>
                    <div class="track-content" :style="{ width: waveformWidth + 'px' }">
                        <div class="slides-timeline">
                            <template v-for="(lyric, index) in localLyrics" :key="'marker-' + index">
                                <!-- Stitch button between slides -->
                                <button
                                    v-if="index > 0 && canStitch(index - 1, index)"
                                    class="stitch-btn"
                                    :style="getStitchButtonStyle(index)"
                                    @click.stop="stitchSlides(index - 1, index)"
                                    title="Merge slides"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 15l-6-6-6 6"/>
                                    </svg>
                                </button>

                                <!-- Slide marker -->
                                <div
                                    class="lyric-marker"
                                    :class="{ 'active': currentLyricIndex === index, 'selected': selectedLyricIndex === index }"
                                    :style="getLyricMarkerStyle(lyric)"
                                    @mousedown="startDragMarker($event, index, 'block')"
                                    @click="selectLyric(index)"
                                >
                                    <div class="marker-resize left" @mousedown.stop="startDragMarker($event, index, 'start')"></div>
                                    <span class="marker-text">{{ truncateText(lyric.text, 15) }}</span>
                                    <button class="marker-split-btn" @click.stop="splitSlideAtPlayhead(index)" title="Split slide">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="3" x2="12" y2="21"/><line x1="8" y1="8" x2="12" y2="3"/><line x1="16" y1="8" x2="12" y2="3"/><line x1="8" y1="16" x2="12" y2="21"/><line x1="16" y1="16" x2="12" y2="21"/>
                                        </svg>
                                    </button>
                                    <div class="marker-resize right" @mousedown.stop="startDragMarker($event, index, 'end')"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time ruler -->
            <div class="time-ruler" ref="timeRuler">
                <div class="ruler-marks" :style="{ width: waveformWidth + 'px' }">
                    <div
                        v-for="mark in timeMarks"
                        :key="mark.time"
                        class="ruler-mark"
                        :style="{ left: (mark.time / (durationMs / 1000)) * waveformWidth + 'px' }"
                    >
                        <span class="mark-label">{{ mark.label }}</span>
                    </div>
                </div>
            </div>
        </div>
        </template>

        <!-- Processing overlay -->
        <div v-if="isProcessing || isExporting || isUploading" class="processing-overlay">
            <div class="processing-content">
                <div class="spinner"></div>
                <p>{{ processingMessage }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import WaveSurfer from 'wavesurfer.js';

export default {
    name: 'VideoEditor',
    props: {
        audioUrl: { type: String, default: '' },
        lyrics: { type: Array, default: () => [] },
        projectId: { type: [String, Number], default: null },
        csrfToken: { type: String, default: '' },
    },
    data() {
        return {
            wavesurfer: null,
            isPlaying: false,
            currentTimeMs: 0,
            durationMs: 0,
            localLyrics: [],
            localAudioUrl: '',
            audioUrlInput: '',
            isUploading: false,
            uploadProgress: 0,
            selectedLyricIndex: null,
            currentLyricIndex: null,
            zoomLevel: 1,
            waveformWidth: 0,
            outputSize: '1920x1080',
            isProcessing: false,
            isExporting: false,
            processingMessage: '',
            isDark: true,
            projectTitle: 'Lyric Video',
            history: [],
            historyIndex: -1,
            style: {
                backgroundColor: '#000000',
                backgroundImage: null,
                backgroundVideo: null,
                logo: null,
                logoPosition: 'bottom-right',
                logoSize: 80,
                fontFamily: 'Arial',
                fontSize: 48,
                fontColor: '#ffffff',
                textPosition: 'center',
                textShadow: true,
            },
            selectedAnimation: 'fade',
            dragState: null,
        };
    },
    computed: {
        currentLyric() {
            if (this.currentLyricIndex !== null && this.localLyrics[this.currentLyricIndex]) {
                return this.localLyrics[this.currentLyricIndex];
            }
            return null;
        },
        canUndo() {
            return this.historyIndex > 0;
        },
        canRedo() {
            return this.historyIndex < this.history.length - 1;
        },
        previewStyle() {
            return {};
        },
        backgroundStyle() {
            const [width, height] = this.outputSize.split('x').map(Number);
            const styles = {
                backgroundColor: this.style.backgroundColor,
                '--preview-aspect-ratio': `${width}/${height}`,
            };
            if (this.style.backgroundImage) {
                styles.backgroundImage = `url(${this.style.backgroundImage})`;
                styles.backgroundSize = 'cover';
                styles.backgroundPosition = 'center';
            }
            return styles;
        },
        lyricStyle() {
            const positionMap = {
                top: { top: '10%', transform: 'translateX(-50%)' },
                center: { top: '50%', transform: 'translate(-50%, -50%)' },
                bottom: { bottom: '15%', transform: 'translateX(-50%)' },
            };
            return {
                fontFamily: this.style.fontFamily,
                fontSize: `${this.style.fontSize}px`,
                color: this.style.fontColor,
                left: '50%',
                ...positionMap[this.style.textPosition],
                textShadow: this.style.textShadow ? '2px 2px 8px rgba(0,0,0,0.8)' : 'none',
            };
        },
        timeMarks() {
            if (!this.durationMs) return [];
            const marks = [];
            const totalSeconds = this.durationMs / 1000;
            const interval = totalSeconds <= 60 ? 5 : totalSeconds <= 180 ? 10 : 30;

            for (let sec = 0; sec <= totalSeconds; sec += interval) {
                marks.push({
                    time: sec,
                    position: (sec / totalSeconds) * 100,
                    label: this.formatTimeSeconds(sec),
                });
            }
            return marks;
        },
    },
    watch: {
        localLyrics: {
            handler() {
                this.saveToHistory();
            },
            deep: true,
        },
        'style.fontFamily': {
            handler(newFont) {
                this.loadGoogleFont(newFont);
            },
            immediate: true,
        },
    },
    mounted() {
        this.localLyrics = JSON.parse(JSON.stringify(this.lyrics));
        this.localAudioUrl = this.audioUrl;
        this.saveToHistory();

        if (this.localAudioUrl) {
            this.$nextTick(() => this.initWaveSurfer());
        }

        document.addEventListener('mousemove', this.onDragMove);
        document.addEventListener('mouseup', this.onDragEnd);
    },
    beforeUnmount() {
        if (this.wavesurfer) {
            this.wavesurfer.destroy();
        }
        document.removeEventListener('mousemove', this.onDragMove);
        document.removeEventListener('mouseup', this.onDragEnd);
    },
    methods: {
        initWaveSurfer() {
            this.wavesurfer = WaveSurfer.create({
                container: this.$refs.waveform,
                waveColor: '#4a90d9',
                progressColor: '#1e5aa8',
                cursorColor: '#ff6b6b',
                barWidth: 2,
                barRadius: 2,
                responsive: true,
                height: 80,
                normalize: true,
            });

            this.wavesurfer.load(this.localAudioUrl);

            this.wavesurfer.on('ready', () => {
                this.durationMs = this.wavesurfer.getDuration() * 1000;
                this.$nextTick(() => this.zoomToFit());
            });

            this.wavesurfer.on('audioprocess', () => {
                this.currentTimeMs = this.wavesurfer.getCurrentTime() * 1000;
                this.updateCurrentLyric();
            });

            this.wavesurfer.on('seek', () => {
                this.currentTimeMs = this.wavesurfer.getCurrentTime() * 1000;
                this.updateCurrentLyric();
            });

            this.wavesurfer.on('finish', () => {
                this.isPlaying = false;
            });
        },
        togglePlayback() {
            if (this.wavesurfer) {
                this.wavesurfer.playPause();
                this.isPlaying = !this.isPlaying;
            }
        },
        skipBackward() {
            if (this.wavesurfer) {
                const current = this.wavesurfer.getCurrentTime();
                this.wavesurfer.seekTo(Math.max(0, current - 5) / this.wavesurfer.getDuration());
            }
        },
        skipForward() {
            if (this.wavesurfer) {
                const current = this.wavesurfer.getCurrentTime();
                const duration = this.wavesurfer.getDuration();
                this.wavesurfer.seekTo(Math.min(duration, current + 5) / duration);
            }
        },
        updateCurrentLyric() {
            const time = this.currentTimeMs;
            this.currentLyricIndex = this.localLyrics.findIndex(
                lyric => time >= lyric.start_ms && time <= lyric.end_ms
            );
        },
        selectLyric(index) {
            this.selectedLyricIndex = index;
            if (this.wavesurfer && this.localLyrics[index]) {
                const seekPosition = this.localLyrics[index].start_ms / this.durationMs;
                this.wavesurfer.seekTo(seekPosition);
            }
        },
        addLyricLine() {
            const lastLyric = this.localLyrics[this.localLyrics.length - 1];
            const startMs = lastLyric ? lastLyric.end_ms + 500 : this.currentTimeMs;

            this.localLyrics.push({
                order: this.localLyrics.length,
                text: '',
                start_ms: startMs,
                end_ms: startMs + 3000,
                animation: this.selectedAnimation,
            });
        },
        deleteLyric(index) {
            this.localLyrics.splice(index, 1);
            this.localLyrics.forEach((lyric, i) => lyric.order = i);
            if (this.selectedLyricIndex === index) {
                this.selectedLyricIndex = null;
            }
        },
        onDragStart(event, index) {
            event.dataTransfer.setData('text/plain', index);
        },
        onDrop(event, targetIndex) {
            const sourceIndex = parseInt(event.dataTransfer.getData('text/plain'));
            if (sourceIndex !== targetIndex) {
                const [removed] = this.localLyrics.splice(sourceIndex, 1);
                this.localLyrics.splice(targetIndex, 0, removed);
                this.localLyrics.forEach((lyric, i) => lyric.order = i);
            }
        },
        getLyricMarkerStyle(lyric) {
            if (!this.durationMs || !this.waveformWidth) return { display: 'none' };
            const left = (lyric.start_ms / this.durationMs) * this.waveformWidth;
            const width = ((lyric.end_ms - lyric.start_ms) / this.durationMs) * this.waveformWidth;
            return {
                left: `${left}px`,
                width: `${Math.max(30, width)}px`,
            };
        },
        startDragMarker(event, index, type) {
            this.dragState = {
                index,
                type,
                startX: event.clientX,
                originalStart: this.localLyrics[index].start_ms,
                originalEnd: this.localLyrics[index].end_ms,
            };
        },
        onDragMove(event) {
            if (!this.dragState || !this.waveformWidth) return;

            const deltaX = event.clientX - this.dragState.startX;
            const deltaMs = (deltaX / this.waveformWidth) * this.durationMs;

            const lyric = this.localLyrics[this.dragState.index];

            if (this.dragState.type === 'block') {
                const newStart = Math.max(0, this.dragState.originalStart + deltaMs);
                const duration = this.dragState.originalEnd - this.dragState.originalStart;
                lyric.start_ms = Math.round(newStart);
                lyric.end_ms = Math.round(Math.min(this.durationMs, newStart + duration));
            } else if (this.dragState.type === 'start') {
                lyric.start_ms = Math.round(Math.max(0, Math.min(lyric.end_ms - 200, this.dragState.originalStart + deltaMs)));
            } else if (this.dragState.type === 'end') {
                lyric.end_ms = Math.round(Math.min(this.durationMs, Math.max(lyric.start_ms + 200, this.dragState.originalEnd + deltaMs)));
            }
        },
        onDragEnd() {
            this.dragState = null;
        },
        async onAudioFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.isUploading = true;
            this.uploadProgress = 0;
            this.processingMessage = 'Uploading audio...';

            const formData = new FormData();
            formData.append('audio', file);

            try {
                const response = await fetch('/api/video-editor/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success && data.audio_url) {
                    this.localAudioUrl = data.audio_url;
                    this.$nextTick(() => this.initWaveSurfer());
                }
            } catch (error) {
                console.error('Upload failed:', error);
            } finally {
                this.isUploading = false;
            }
        },
        loadAudioFromUrl() {
            if (!this.audioUrlInput) return;
            this.localAudioUrl = this.audioUrlInput;
            this.$nextTick(() => this.initWaveSurfer());
        },
        async autoDetectTimestamps() {
            if (!this.localAudioUrl) return;

            this.isProcessing = true;
            this.processingMessage = 'Detecting lyrics with AI...';

            try {
                const response = await fetch('/api/video-editor/auto-detect', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        audio_url: this.localAudioUrl,
                        project_id: this.projectId,
                    }),
                });

                const data = await response.json();

                if (data.success && data.timestamps) {
                    this.localLyrics = data.timestamps;
                }
            } catch (error) {
                console.error('Auto-detect failed:', error);
            } finally {
                this.isProcessing = false;
            }
        },
        async exportVideo() {
            this.isExporting = true;
            this.processingMessage = 'Rendering video...';

            try {
                const [width, height] = this.outputSize.split('x').map(Number);

                const response = await fetch('/api/video-editor/export', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        project_id: this.projectId,
                        audio_url: this.localAudioUrl,
                        lyrics: this.localLyrics,
                        style: this.style,
                        width,
                        height,
                        duration_ms: this.durationMs,
                    }),
                });

                const data = await response.json();

                if (data.success && data.download_url) {
                    window.open(data.download_url, '_blank');
                }
            } catch (error) {
                console.error('Export failed:', error);
            } finally {
                this.isExporting = false;
            }
        },
        applyAnimationToAll() {
            this.localLyrics.forEach(lyric => {
                lyric.animation = this.selectedAnimation;
            });
        },
        onBackgroundImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.style.backgroundVideo = null;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.style.backgroundImage = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        onBackgroundVideoChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.style.backgroundImage = null;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.style.backgroundVideo = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        onLogoChange(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.style.logo = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        clearBackground() {
            this.style.backgroundImage = null;
            this.style.backgroundVideo = null;
        },
        clearLogo() {
            this.style.logo = null;
        },
        formatTimeEditable(ms) {
            if (!ms && ms !== 0) return '0:00.0';
            const totalSeconds = ms / 1000;
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = Math.floor(totalSeconds % 60);
            const tenths = Math.floor((totalSeconds % 1) * 10);
            return `${minutes}:${seconds.toString().padStart(2, '0')}.${tenths}`;
        },
        parseTimeToMs(timeStr) {
            const match = timeStr.match(/^(\d+):(\d{1,2})(?:\.(\d))?$/);
            if (!match) return null;
            const minutes = parseInt(match[1], 10);
            const seconds = parseInt(match[2], 10);
            const tenths = match[3] ? parseInt(match[3], 10) : 0;
            return (minutes * 60 + seconds) * 1000 + tenths * 100;
        },
        updateStartTime(event, index) {
            const ms = this.parseTimeToMs(event.target.value);
            if (ms !== null && ms >= 0 && ms < this.localLyrics[index].end_ms) {
                this.localLyrics[index].start_ms = ms;
            }
        },
        updateEndTime(event, index) {
            const ms = this.parseTimeToMs(event.target.value);
            if (ms !== null && ms > this.localLyrics[index].start_ms && ms <= this.durationMs) {
                this.localLyrics[index].end_ms = ms;
            }
        },
        zoomIn() {
            this.zoomLevel = Math.min(5, this.zoomLevel + 0.5);
            this.applyZoom();
        },
        zoomOut() {
            this.zoomLevel = Math.max(0.25, this.zoomLevel - 0.5);
            this.applyZoom();
        },
        zoomToFit() {
            if (!this.durationMs || !this.$refs.timelineTracks) return;

            const containerWidth = this.$refs.timelineTracks.offsetWidth - 60;
            const durationSec = this.durationMs / 1000;
            const targetPxPerSec = containerWidth / durationSec;
            this.zoomLevel = Math.max(0.25, Math.min(5, targetPxPerSec / 50));
            this.applyZoom();

            this.$nextTick(() => {
                if (this.$refs.timelineTracks) {
                    this.$refs.timelineTracks.scrollLeft = 0;
                }
            });
        },
        applyZoom() {
            if (this.wavesurfer) {
                const pxPerSec = this.zoomLevel * 50;
                this.wavesurfer.zoom(pxPerSec);
                this.updateWaveformWidth();
            }
        },
        updateWaveformWidth() {
            if (this.durationMs) {
                const pxPerSec = this.zoomLevel * 50;
                const durationSec = this.durationMs / 1000;
                this.waveformWidth = Math.max(
                    durationSec * pxPerSec,
                    this.$refs.timelineTracks?.offsetWidth || 800
                );
            } else if (this.$refs.timelineTracks) {
                this.waveformWidth = this.$refs.timelineTracks.offsetWidth;
            }
        },
        onTimelineScroll(event) {
            if (this.$refs.timeRuler) {
                this.$refs.timeRuler.scrollLeft = event.target.scrollLeft;
            }
        },
        saveToHistory() {
            const state = JSON.stringify(this.localLyrics);
            if (this.history[this.historyIndex] !== state) {
                this.history = this.history.slice(0, this.historyIndex + 1);
                this.history.push(state);
                this.historyIndex = this.history.length - 1;
            }
        },
        undo() {
            if (this.canUndo) {
                this.historyIndex--;
                this.localLyrics = JSON.parse(this.history[this.historyIndex]);
            }
        },
        redo() {
            if (this.canRedo) {
                this.historyIndex++;
                this.localLyrics = JSON.parse(this.history[this.historyIndex]);
            }
        },
        formatTime(ms) {
            if (!ms && ms !== 0) return '0:00';
            const totalSeconds = Math.floor(ms / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        },
        formatTimeSeconds(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },
        truncateText(text, maxLength) {
            if (!text) return '';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        },
        loadGoogleFont(fontFamily) {
            const systemFonts = ['Arial', 'Georgia', 'Times New Roman', 'Courier New', 'Verdana'];
            if (systemFonts.includes(fontFamily)) return;

            const fontId = `google-font-${fontFamily.replace(/\s+/g, '-').toLowerCase()}`;
            if (document.getElementById(fontId)) return;

            const link = document.createElement('link');
            link.id = fontId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${encodeURIComponent(fontFamily)}:wght@400;500;600;700&display=swap`;
            document.head.appendChild(link);
        },
        countWords(text) {
            if (!text) return 0;
            return text.trim().split(/\s+/).filter(w => w.length > 0).length;
        },
        splitLyric(index) {
            const lyric = this.localLyrics[index];
            const words = lyric.text.trim().split(/\s+/);
            if (words.length <= 4) return;

            const midpoint = Math.ceil(words.length / 2);
            const firstHalf = words.slice(0, midpoint).join(' ');
            const secondHalf = words.slice(midpoint).join(' ');

            const totalDuration = lyric.end_ms - lyric.start_ms;
            const midTime = lyric.start_ms + Math.floor(totalDuration / 2);

            lyric.text = firstHalf;
            lyric.end_ms = midTime;

            const newLyric = {
                order: index + 1,
                text: secondHalf,
                start_ms: midTime,
                end_ms: lyric.start_ms + totalDuration,
                animation: lyric.animation,
            };

            this.localLyrics.splice(index + 1, 0, newLyric);
            this.localLyrics.forEach((l, i) => l.order = i);
        },
        splitSlideAtPlayhead(index) {
            const lyric = this.localLyrics[index];
            const duration = lyric.end_ms - lyric.start_ms;

            if (duration < 400) return;

            let splitTime;
            if (this.currentTimeMs >= lyric.start_ms && this.currentTimeMs <= lyric.end_ms) {
                splitTime = this.currentTimeMs;
            } else {
                splitTime = lyric.start_ms + Math.floor(duration / 2);
            }

            if (splitTime - lyric.start_ms < 200 || lyric.end_ms - splitTime < 200) {
                splitTime = lyric.start_ms + Math.floor(duration / 2);
            }

            const words = lyric.text.trim().split(/\s+/);
            const ratio = (splitTime - lyric.start_ms) / duration;
            const splitWordIndex = Math.max(1, Math.floor(words.length * ratio));

            const firstHalf = words.slice(0, splitWordIndex).join(' ') || lyric.text;
            const secondHalf = words.slice(splitWordIndex).join(' ') || '...';

            const originalEnd = lyric.end_ms;
            lyric.text = firstHalf;
            lyric.end_ms = splitTime;

            const newLyric = {
                order: index + 1,
                text: secondHalf,
                start_ms: splitTime,
                end_ms: originalEnd,
                animation: lyric.animation,
            };

            this.localLyrics.splice(index + 1, 0, newLyric);
            this.localLyrics.forEach((l, i) => l.order = i);
        },
        canStitch(index1, index2) {
            if (index1 < 0 || index2 >= this.localLyrics.length) return false;
            const slide1 = this.localLyrics[index1];
            const slide2 = this.localLyrics[index2];
            const gap = slide2.start_ms - slide1.end_ms;
            return gap <= 500;
        },
        getStitchButtonStyle(index) {
            const prevSlide = this.localLyrics[index - 1];
            const currSlide = this.localLyrics[index];
            const midPoint = (prevSlide.end_ms + currSlide.start_ms) / 2;
            const left = (midPoint / this.durationMs) * this.waveformWidth;
            return {
                left: `${left}px`,
            };
        },
        stitchSlides(index1, index2) {
            const slide1 = this.localLyrics[index1];
            const slide2 = this.localLyrics[index2];

            slide1.text = (slide1.text + ' ' + slide2.text).trim();
            slide1.end_ms = slide2.end_ms;

            this.localLyrics.splice(index2, 1);
            this.localLyrics.forEach((l, i) => l.order = i);

            if (this.selectedLyricIndex === index2) {
                this.selectedLyricIndex = index1;
            } else if (this.selectedLyricIndex > index2) {
                this.selectedLyricIndex--;
            }
        },
    },
};
</script>

<style scoped>
.video-editor {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background: #1a1a2e;
    color: #e0e0e0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Toolbar */
.editor-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 1rem;
    background: #16213e;
    border-bottom: 1px solid #0f3460;
}

.toolbar-left, .toolbar-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.toolbar-center {
    font-weight: 600;
    font-size: 1rem;
}

.toolbar-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border: none;
    border-radius: 6px;
    background: #0f3460;
    color: #e0e0e0;
    cursor: pointer;
    transition: all 0.2s;
}

.toolbar-btn:hover:not(:disabled) {
    background: #1a4a7a;
}

.toolbar-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.toolbar-btn.primary {
    background: #4a90d9;
    color: white;
}

.toolbar-btn.primary:hover:not(:disabled) {
    background: #3a7fc8;
}

.toolbar-divider {
    width: 1px;
    height: 24px;
    background: #0f3460;
    margin: 0 0.5rem;
}

.icon {
    width: 18px;
    height: 18px;
}

.size-select {
    padding: 0.5rem;
    border-radius: 6px;
    border: 1px solid #0f3460;
    background: #16213e;
    color: #e0e0e0;
}

/* Main Content */
.editor-content {
    display: flex;
    flex: 1;
    overflow: hidden;
}

.panel {
    background: #16213e;
    border: 1px solid #0f3460;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #0f3460;
}

.panel-header h3 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Lyrics Panel */
.lyrics-panel {
    width: 280px;
    display: flex;
    flex-direction: column;
}

.lyrics-list {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem;
}

.lyric-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    margin-bottom: 0.25rem;
    border-radius: 6px;
    background: #1a1a2e;
    cursor: pointer;
    transition: all 0.2s;
}

.lyric-item:hover {
    background: #0f3460;
}

.lyric-item.active {
    background: #4a90d9;
}

.lyric-item.selected {
    outline: 2px solid #4a90d9;
}

.lyric-handle {
    color: #666;
    cursor: grab;
}

.lyric-content {
    flex: 1;
    min-width: 0;
}

.lyric-text-input {
    width: 100%;
    padding: 0.25rem;
    border: none;
    border-radius: 4px;
    background: transparent;
    color: #e0e0e0;
    font-size: 0.875rem;
}

.lyric-text-input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.1);
}

.lyric-timing {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #888;
}

.timing-badge {
    padding: 0.125rem 0.375rem;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 3px;
}

.timing-input {
    width: 55px;
    padding: 0.125rem 0.25rem;
    border: 1px solid transparent;
    border-radius: 3px;
    background: rgba(0, 0, 0, 0.3);
    color: #e0e0e0;
    font-size: 0.75rem;
    font-family: monospace;
    text-align: center;
}

.timing-input:focus {
    outline: none;
    border-color: #4a90d9;
    background: rgba(0, 0, 0, 0.5);
}

.lyric-animation {
    margin-top: 0.25rem;
}

.animation-select {
    width: 100%;
    padding: 0.125rem 0.25rem;
    border: 1px solid transparent;
    border-radius: 3px;
    background: rgba(0, 0, 0, 0.3);
    color: #aaa;
    font-size: 0.7rem;
    cursor: pointer;
}

.animation-select:focus {
    outline: none;
    border-color: #4a90d9;
}

.btn-icon {
    padding: 0.25rem;
    border: none;
    border-radius: 4px;
    background: transparent;
    color: #888;
    cursor: pointer;
}

.btn-icon:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
}

.btn-icon.delete:hover {
    color: #ff6b6b;
}

.lyric-actions {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.btn-icon.split {
    color: #4a90d9;
}

.btn-icon.split:hover {
    color: #6ab0f9;
    background: rgba(74, 144, 217, 0.2);
}

.btn-icon.stitch {
    color: #27ae60;
}

.btn-icon.stitch:hover {
    color: #2ecc71;
    background: rgba(39, 174, 96, 0.2);
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 4px;
    background: #0f3460;
    color: #e0e0e0;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-sm:hover {
    background: #1a4a7a;
}

/* Preview Panel */
.preview-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    min-width: 0;
}

.preview-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #111;
    border-radius: 8px;
    overflow: hidden;
    padding: 1rem;
}

.preview-background {
    width: 100%;
    max-width: 100%;
    aspect-ratio: var(--preview-aspect-ratio, 16/9);
    max-height: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 4px;
    overflow: hidden;
}

.preview-lyric {
    position: absolute;
    text-align: center;
    max-width: 80%;
    word-wrap: break-word;
    z-index: 5;
}

.preview-placeholder {
    color: #666;
    font-style: italic;
}

.preview-video-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.preview-logo {
    position: absolute;
    z-index: 10;
    opacity: 0.9;
}

.logo-bottom-left {
    bottom: 5%;
    left: 5%;
}

.logo-bottom-right {
    bottom: 5%;
    right: 5%;
}

.logo-top-left {
    top: 5%;
    left: 5%;
}

.logo-top-right {
    top: 5%;
    right: 5%;
}

.clear-btn {
    width: 100%;
    margin-top: 0.5rem;
    background: #4a2020;
}

.clear-btn:hover {
    background: #6a3030;
}

/* Playback Controls */
.playback-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 1rem 0 0;
}

.control-btn {
    padding: 0.5rem;
    border: none;
    border-radius: 50%;
    background: #0f3460;
    color: #e0e0e0;
    cursor: pointer;
    transition: all 0.2s;
}

.control-btn:hover {
    background: #1a4a7a;
}

.play-btn {
    padding: 0.75rem;
    background: #4a90d9;
}

.play-btn:hover {
    background: #3a7fc8;
}

.time-display {
    font-family: monospace;
    font-size: 0.875rem;
    color: #888;
}

.time-separator {
    margin: 0 0.25rem;
}

/* Style Panel */
.style-panel {
    width: 280px;
    display: flex;
    flex-direction: column;
}

.style-sections {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem;
}

.style-section {
    margin-bottom: 1rem;
}

.style-section h4 {
    margin: 0 0 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
}

.style-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.style-row label {
    width: 60px;
    font-size: 0.875rem;
    color: #aaa;
}

.color-input {
    width: 40px;
    height: 30px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.style-select {
    flex: 1;
    padding: 0.375rem;
    border: 1px solid #0f3460;
    border-radius: 4px;
    background: #1a1a2e;
    color: #e0e0e0;
}

.range-input {
    flex: 1;
}

.range-value {
    width: 50px;
    text-align: right;
    font-size: 0.75rem;
    color: #888;
}

.file-input {
    flex: 1;
    font-size: 0.75rem;
}

.btn-full {
    width: 100%;
    padding: 0.5rem;
    border: none;
    border-radius: 6px;
    background: #0f3460;
    color: #e0e0e0;
    cursor: pointer;
    margin-top: 0.5rem;
}

.btn-full:hover {
    background: #1a4a7a;
}

/* Timeline Section */
.timeline-section {
    background: #16213e;
    border-top: 1px solid #0f3460;
    padding: 0.5rem 0;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0 1rem;
}

.timeline-labels {
    display: flex;
    align-items: center;
}

.track-label {
    font-size: 0.75rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.slide-count {
    font-size: 0.75rem;
    color: #4a90d9;
    margin-left: 1rem;
    padding: 0.125rem 0.5rem;
    background: rgba(74, 144, 217, 0.15);
    border-radius: 4px;
}

.zoom-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #888;
}

.timeline-tracks {
    overflow-x: auto;
    overflow-y: hidden;
    background: #1a1a2e;
    border-radius: 6px;
    margin: 0 1rem;
}

.track-row {
    position: relative;
    min-height: 80px;
    border-bottom: 1px solid #0f3460;
}

.track-row:last-child {
    border-bottom: none;
}

.track-content {
    min-width: 100%;
    position: relative;
}

.slides-track {
    min-height: 60px;
    background: rgba(0, 0, 0, 0.2);
}

.track-label-inline {
    position: absolute;
    left: 8px;
    top: 4px;
    font-size: 0.625rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 5;
}

.slides-timeline {
    position: relative;
    height: 100%;
    min-height: 60px;
}

.waveform {
    min-width: 100%;
}

.lyric-marker {
    position: absolute;
    top: 8px;
    bottom: 8px;
    height: auto;
    background: rgba(74, 144, 217, 0.5);
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: move;
    transition: background 0.15s, box-shadow 0.15s;
    min-width: 40px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.lyric-marker:hover {
    background: rgba(74, 144, 217, 0.65);
    box-shadow: 0 2px 8px rgba(74, 144, 217, 0.3);
}

.lyric-marker.selected {
    background: rgba(74, 144, 217, 0.7);
    box-shadow: 0 0 0 2px #4a90d9, 0 2px 8px rgba(74, 144, 217, 0.4);
}

.lyric-marker.active {
    background: rgba(74, 144, 217, 0.6);
}

.marker-text {
    font-size: 0.625rem;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 0 16px;
}

.marker-resize {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 36px;
    cursor: ew-resize;
    background: #4a90d9;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    transition: background 0.15s, transform 0.15s;
    pointer-events: all;
    z-index: 10;
}

.marker-resize::before {
    content: '';
    width: 2px;
    height: 16px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 1px;
}

.marker-resize::after {
    content: '';
    position: absolute;
    width: 2px;
    height: 16px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 1px;
    margin-left: 4px;
}

.marker-resize.left {
    left: -7px;
}

.marker-resize.right {
    right: -7px;
}

.marker-resize:hover {
    background: #6ab0f9;
    transform: translateY(-50%) scale(1.1);
}

/* Split button on marker */
.marker-split-btn {
    position: absolute;
    top: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 18px;
    height: 18px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.15s, background 0.15s;
    z-index: 15;
}

.lyric-marker:hover .marker-split-btn {
    opacity: 1;
}

.marker-split-btn:hover {
    background: #4a90d9;
    color: white;
}

/* Stitch button between markers */
.stitch-btn {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 50%;
    background: #27ae60;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 20;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    transition: transform 0.15s, background 0.15s;
}

.stitch-btn:hover {
    background: #2ecc71;
    transform: translate(-50%, -50%) scale(1.2);
}

.stitch-btn svg {
    transform: rotate(180deg);
}

/* Time Ruler */
.time-ruler {
    height: 24px;
    position: relative;
    margin: 4px 1rem 0;
    overflow-x: hidden;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}

.ruler-marks {
    position: relative;
    height: 100%;
    min-width: 100%;
}

.ruler-mark {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.ruler-mark::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 50%;
    width: 1px;
    height: 6px;
    background: #444;
}

.mark-label {
    font-size: 0.625rem;
    color: #888;
    font-family: monospace;
}

/* Processing Overlay */
.processing-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.processing-content {
    text-align: center;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #333;
    border-top-color: #4a90d9;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.spinner.large {
    width: 64px;
    height: 64px;
}

/* Upload Screen */
.upload-screen {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.upload-container {
    text-align: center;
    max-width: 480px;
    padding: 3rem;
}

.upload-icon {
    color: #4a90d9;
    margin-bottom: 1.5rem;
}

.upload-container h2 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #e0e0e0;
}

.upload-container > p {
    color: #888;
    margin-bottom: 2rem;
}

.upload-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
}

.upload-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.upload-btn.primary {
    background: #4a90d9;
    color: white;
    width: 100%;
}

.upload-btn.primary:hover {
    background: #3a7fc8;
    transform: translateY(-1px);
}

.upload-btn.secondary {
    background: #0f3460;
    color: #e0e0e0;
    padding: 0.75rem 1.5rem;
}

.upload-btn.secondary:hover:not(:disabled) {
    background: #1a4a7a;
}

.upload-btn.secondary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.hidden-input {
    display: none;
}

.upload-divider {
    display: flex;
    align-items: center;
    width: 100%;
    margin: 0.5rem 0;
}

.upload-divider span {
    padding: 0 1rem;
    color: #666;
    font-size: 0.875rem;
}

.upload-divider::before,
.upload-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #333;
}

.url-input-group {
    display: flex;
    gap: 0.5rem;
    width: 100%;
}

.url-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #333;
    border-radius: 8px;
    background: #1a1a2e;
    color: #e0e0e0;
    font-size: 0.875rem;
}

.url-input:focus {
    outline: none;
    border-color: #4a90d9;
}

.url-input::placeholder {
    color: #666;
}

.upload-hints {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #333;
}

.upload-hints p {
    font-size: 0.75rem;
    color: #666;
    margin: 0.25rem 0;
}
</style>
