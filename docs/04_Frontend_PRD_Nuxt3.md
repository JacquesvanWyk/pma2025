# Frontend Product Requirements Document (PRD)
## PMA Sermon Management System - Nuxt 3 Frontend

### Table of Contents
1. [Executive Summary](#executive-summary)
2. [Technical Architecture](#technical-architecture)
3. [User Interface Requirements](#user-interface-requirements)
4. [Component Specifications](#component-specifications)
5. [State Management](#state-management)
6. [API Integration](#api-integration)
7. [Performance Requirements](#performance-requirements)
8. [Accessibility & Internationalization](#accessibility--internationalization)
9. [Testing Requirements](#testing-requirements)
10. [Deployment & Build Process](#deployment--build-process)

---

## 1. Executive Summary

### Project Overview
Build a modern, responsive Nuxt 3 frontend application for the PMA Sermon Management System that provides an intuitive interface for sermon creation, AI-powered assistance, media management, and public content access for South African churches.

### Technology Stack
- **Framework**: Nuxt 3 (Vue 3)
- **Language**: TypeScript
- **Styling**: TailwindCSS + Headless UI
- **State Management**: Pinia
- **API Client**: ofetch (Nuxt native)
- **Rich Text Editor**: Tiptap 2
- **Icons**: Heroicons + Lucide
- **Forms**: VeeValidate + Yup
- **Real-time**: Socket.io Client
- **PWA**: @vite-pwa/nuxt

### Key Deliverables
1. Admin dashboard for sermon management
2. Rich sermon editor with AI integration
3. Bible search and integration interface
4. Media generation and management
5. Public sermon archive
6. Mobile-responsive design
7. Multi-language support (EN, AF, XH, NY)

---

## 2. Technical Architecture

### Directory Structure
```
├── components/
│   ├── admin/
│   │   ├── Dashboard/
│   │   │   ├── StatsCard.vue
│   │   │   ├── RecentSermons.vue
│   │   │   └── QuickActions.vue
│   │   ├── Layout/
│   │   │   ├── AdminHeader.vue
│   │   │   ├── AdminSidebar.vue
│   │   │   └── AdminFooter.vue
│   │   └── Tables/
│   │       ├── SermonTable.vue
│   │       └── DataTable.vue
│   ├── sermon/
│   │   ├── Editor/
│   │   │   ├── SermonEditor.vue
│   │   │   ├── EditorToolbar.vue
│   │   │   └── EditorExtensions.ts
│   │   ├── Forms/
│   │   │   ├── SermonForm.vue
│   │   │   ├── SeriesSelector.vue
│   │   │   └── TemplateSelector.vue
│   │   └── Cards/
│   │       ├── SermonCard.vue
│   │       └── SeriesCard.vue
│   ├── bible/
│   │   ├── BibleSearch.vue
│   │   ├── VerseSelector.vue
│   │   ├── TranslationPicker.vue
│   │   └── VerseDisplay.vue
│   ├── ai/
│   │   ├── AIAssistant.vue
│   │   ├── ChatInterface.vue
│   │   ├── SuggestionCard.vue
│   │   └── TokenUsage.vue
│   ├── media/
│   │   ├── ThumbnailGenerator.vue
│   │   ├── PowerPointPreview.vue
│   │   ├── MediaUploader.vue
│   │   └── ImageEditor.vue
│   ├── social/
│   │   ├── PostComposer.vue
│   │   ├── PlatformSelector.vue
│   │   └── ScheduleCalendar.vue
│   └── ui/
│       ├── BaseButton.vue
│       ├── BaseInput.vue
│       ├── BaseModal.vue
│       ├── BaseAlert.vue
│       └── LoadingSpinner.vue
├── composables/
│   ├── useAuth.ts
│   ├── useSermons.ts
│   ├── useBible.ts
│   ├── useAI.ts
│   ├── useMedia.ts
│   └── useAnalytics.ts
├── layouts/
│   ├── admin.vue
│   ├── default.vue
│   ├── auth.vue
│   └── public.vue
├── middleware/
│   ├── auth.ts
│   ├── guest.ts
│   └── role.ts
├── pages/
│   ├── admin/
│   │   ├── index.vue
│   │   ├── sermons/
│   │   │   ├── index.vue
│   │   │   ├── create.vue
│   │   │   └── [id]/
│   │   │       ├── index.vue
│   │   │       └── edit.vue
│   │   ├── series/
│   │   ├── media/
│   │   ├── analytics/
│   │   └── settings/
│   ├── sermons/
│   │   ├── index.vue
│   │   └── [id].vue
│   ├── auth/
│   │   ├── login.vue
│   │   ├── register.vue
│   │   └── forgot-password.vue
│   └── index.vue
├── plugins/
│   ├── api.ts
│   ├── dayjs.ts
│   ├── socket.client.ts
│   └── tiptap.client.ts
├── stores/
│   ├── auth.ts
│   ├── sermons.ts
│   ├── bible.ts
│   ├── ai.ts
│   ├── media.ts
│   └── ui.ts
├── types/
│   ├── api.ts
│   ├── models.ts
│   └── components.ts
└── utils/
    ├── api-client.ts
    ├── formatters.ts
    ├── validators.ts
    └── constants.ts
```

### Component Architecture

#### Atomic Design Principles
- **Atoms**: Base UI components (buttons, inputs)
- **Molecules**: Simple combinations (form fields)
- **Organisms**: Complex components (editor, chat)
- **Templates**: Page layouts
- **Pages**: Route components

#### Component Naming Convention
```typescript
// Base components
<BaseButton />
<BaseInput />
<BaseModal />

// Feature components
<SermonEditor />
<BibleSearch />
<AIAssistant />

// Layout components
<AdminHeader />
<PublicFooter />
```

---

## 3. User Interface Requirements

### 3.1 Design System

#### Color Palette
```scss
// Primary Colors
$primary-50: #EFF6FF;
$primary-500: #3B82F6;
$primary-900: #1E3A8A;

// Semantic Colors
$success: #10B981;
$warning: #F59E0B;
$error: #EF4444;
$info: #3B82F6;

// Neutral Colors
$gray-50: #F9FAFB;
$gray-900: #111827;
```

#### Typography
```scss
// Font Families
$font-sans: 'Inter', system-ui, -apple-system, sans-serif;
$font-serif: 'Merriweather', Georgia, serif;
$font-mono: 'Fira Code', monospace;

// Font Sizes
$text-xs: 0.75rem;    // 12px
$text-sm: 0.875rem;   // 14px
$text-base: 1rem;     // 16px
$text-lg: 1.125rem;   // 18px
$text-xl: 1.25rem;    // 20px
$text-2xl: 1.5rem;    // 24px
$text-3xl: 1.875rem;  // 30px
```

#### Spacing System
```scss
// Based on 4px grid
$space-1: 0.25rem;  // 4px
$space-2: 0.5rem;   // 8px
$space-3: 0.75rem;  // 12px
$space-4: 1rem;     // 16px
$space-6: 1.5rem;   // 24px
$space-8: 2rem;     // 32px
$space-12: 3rem;    // 48px
```

### 3.2 Responsive Design

#### Breakpoints
```typescript
// tailwind.config.js
module.exports = {
  theme: {
    screens: {
      'sm': '640px',   // Mobile landscape
      'md': '768px',   // Tablet
      'lg': '1024px',  // Desktop
      'xl': '1280px',  // Large desktop
      '2xl': '1536px', // Extra large
    }
  }
}
```

#### Mobile-First Approach
```vue
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Responsive grid layout -->
  </div>
</template>
```

---

## 4. Component Specifications

### 4.1 Sermon Editor Component

```vue
<!-- components/sermon/Editor/SermonEditor.vue -->
<template>
  <div class="sermon-editor">
    <!-- Toolbar -->
    <EditorToolbar 
      :editor="editor"
      @insert-verse="showBibleSearch"
      @ai-assist="toggleAIAssistant"
      @save="saveSermon"
    />
    
    <!-- Main Editor -->
    <div class="editor-container">
      <div class="editor-content">
        <EditorContent :editor="editor" />
      </div>
      
      <!-- Side Panels -->
      <div class="editor-sidebar">
        <!-- AI Assistant -->
        <AIAssistant 
          v-if="showAI"
          :sermon-id="sermonId"
          @insert-suggestion="insertSuggestion"
        />
        
        <!-- Bible Search -->
        <BibleSearch 
          v-if="showBible"
          @insert-verse="insertVerse"
        />
        
        <!-- Related Sermons -->
        <RelatedSermons 
          :current-content="editorContent"
          @open-sermon="openRelatedSermon"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useEditor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Highlight from '@tiptap/extension-highlight'
import { BibleVerse } from './extensions/BibleVerse'
import { AIHighlight } from './extensions/AIHighlight'

const editor = useEditor({
  extensions: [
    StarterKit,
    Highlight,
    BibleVerse,
    AIHighlight,
  ],
  content: props.initialContent,
  autofocus: true,
  editorProps: {
    attributes: {
      class: 'prose prose-lg max-w-none focus:outline-none',
    },
  },
})

// Auto-save functionality
const { $api } = useNuxtApp()
const debouncedSave = useDebounceFn(async () => {
  await $api.sermons.update(sermonId.value, {
    content: editor.value?.getHTML()
  })
}, 5000)

watch(() => editor.value?.getHTML(), () => {
  debouncedSave()
})
</script>
```

### 4.2 AI Assistant Component

```vue
<!-- components/ai/AIAssistant.vue -->
<template>
  <div class="ai-assistant">
    <div class="ai-header">
      <h3 class="text-lg font-semibold">AI Research Assistant</h3>
      <TokenUsage :used="tokensUsed" :limit="tokenLimit" />
    </div>
    
    <div class="ai-chat-container">
      <div ref="chatContainer" class="ai-messages">
        <ChatMessage 
          v-for="message in messages"
          :key="message.id"
          :message="message"
          @copy="copyToClipboard"
          @insert="insertIntoSermon"
        />
        
        <div v-if="isTyping" class="ai-typing">
          <TypingIndicator />
        </div>
      </div>
      
      <form @submit.prevent="sendMessage" class="ai-input">
        <input
          v-model="userInput"
          type="text"
          placeholder="Ask about theology, illustrations, or applications..."
          class="flex-1"
          :disabled="isTyping"
        />
        <button 
          type="submit" 
          :disabled="!userInput.trim() || isTyping"
          class="ai-send-button"
        >
          <PaperAirplaneIcon class="w-5 h-5" />
        </button>
      </form>
    </div>
    
    <div class="ai-suggestions">
      <h4 class="text-sm font-medium mb-2">Quick Actions</h4>
      <div class="grid grid-cols-2 gap-2">
        <button 
          v-for="action in quickActions"
          :key="action.id"
          @click="executeAction(action)"
          class="text-sm px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200"
        >
          {{ action.label }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { $api, $socket } = useNuxtApp()
const aiStore = useAIStore()

// Real-time streaming
const streamResponse = async (conversationId: string, message: string) => {
  isTyping.value = true
  currentResponse.value = ''
  
  // Subscribe to streaming events
  $socket.emit('ai:stream:start', { conversationId, message })
  
  $socket.on(`ai:stream:${conversationId}`, (data) => {
    if (data.chunk) {
      currentResponse.value += data.chunk
    }
    if (data.done) {
      messages.value.push({
        id: Date.now(),
        role: 'assistant',
        content: currentResponse.value,
        tokens: data.totalTokens
      })
      isTyping.value = false
      tokensUsed.value += data.totalTokens
    }
  })
}
</script>
```

### 4.3 Bible Search Component

```vue
<!-- components/bible/BibleSearch.vue -->
<template>
  <div class="bible-search">
    <div class="search-header">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search verses..."
        class="bible-search-input"
        @input="debouncedSearch"
      />
      
      <TranslationPicker 
        v-model="selectedTranslation"
        :available-translations="availableTranslations"
      />
    </div>
    
    <div class="search-filters">
      <label class="inline-flex items-center">
        <input 
          v-model="filters.oldTestament" 
          type="checkbox" 
          class="rounded"
        />
        <span class="ml-2 text-sm">Old Testament</span>
      </label>
      <label class="inline-flex items-center">
        <input 
          v-model="filters.newTestament" 
          type="checkbox" 
          class="rounded"
        />
        <span class="ml-2 text-sm">New Testament</span>
      </label>
    </div>
    
    <div class="search-results">
      <div v-if="isSearching" class="text-center py-4">
        <LoadingSpinner />
      </div>
      
      <div v-else-if="searchResults.length === 0 && searchQuery" class="text-center py-4 text-gray-500">
        No verses found matching "{{ searchQuery }}"
      </div>
      
      <TransitionGroup
        v-else
        name="verse-list"
        tag="div"
        class="space-y-2"
      >
        <VerseCard
          v-for="verse in searchResults"
          :key="`${verse.book}-${verse.chapter}-${verse.verse}`"
          :verse="verse"
          @insert="insertVerse"
          @view-context="viewContext"
        />
      </TransitionGroup>
    </div>
    
    <div v-if="hasMore" class="text-center mt-4">
      <button 
        @click="loadMore" 
        class="text-primary-600 hover:text-primary-700"
      >
        Load more results
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const bibleStore = useBibleStore()
const { searchVerses } = bibleStore

const debouncedSearch = useDebounceFn(async () => {
  if (!searchQuery.value.trim()) {
    searchResults.value = []
    return
  }
  
  isSearching.value = true
  
  try {
    const results = await searchVerses({
      query: searchQuery.value,
      translation: selectedTranslation.value,
      testament: getTestamentFilter(),
      limit: 20,
      offset: currentOffset.value
    })
    
    searchResults.value = results.data
    hasMore.value = results.hasMore
  } finally {
    isSearching.value = false
  }
}, 500)
</script>
```

### 4.4 Media Generation Components

```vue
<!-- components/media/ThumbnailGenerator.vue -->
<template>
  <div class="thumbnail-generator">
    <h3 class="text-lg font-semibold mb-4">Generate Sermon Thumbnail</h3>
    
    <div class="generator-form space-y-4">
      <!-- Style Selection -->
      <div>
        <label class="block text-sm font-medium mb-2">Style</label>
        <div class="grid grid-cols-3 gap-2">
          <button
            v-for="style in thumbnailStyles"
            :key="style.id"
            @click="selectedStyle = style"
            :class="[
              'relative overflow-hidden rounded-lg border-2 p-2',
              selectedStyle?.id === style.id
                ? 'border-primary-500'
                : 'border-gray-200'
            ]"
          >
            <img 
              :src="style.preview" 
              :alt="style.name"
              class="w-full h-20 object-cover rounded"
            />
            <span class="text-xs">{{ style.name }}</span>
          </button>
        </div>
      </div>
      
      <!-- Custom Prompt -->
      <div>
        <label class="block text-sm font-medium mb-2">
          Custom Elements (Optional)
        </label>
        <textarea
          v-model="customPrompt"
          rows="3"
          placeholder="E.g., sunrise over mountains, dove in flight, open Bible..."
          class="w-full rounded-lg border-gray-300"
        />
      </div>
      
      <!-- Text Overlay -->
      <div>
        <label class="inline-flex items-center">
          <input 
            v-model="includeText" 
            type="checkbox" 
            class="rounded"
          />
          <span class="ml-2 text-sm">Include sermon title and date</span>
        </label>
      </div>
      
      <!-- Generate Button -->
      <button
        @click="generateThumbnails"
        :disabled="isGenerating"
        class="w-full btn-primary"
      >
        <span v-if="!isGenerating">Generate Thumbnails</span>
        <span v-else class="inline-flex items-center">
          <LoadingSpinner class="w-4 h-4 mr-2" />
          Generating...
        </span>
      </button>
    </div>
    
    <!-- Generated Thumbnails -->
    <div v-if="generatedThumbnails.length > 0" class="mt-6">
      <h4 class="text-sm font-medium mb-3">Generated Options</h4>
      <div class="grid grid-cols-2 gap-3">
        <div
          v-for="thumbnail in generatedThumbnails"
          :key="thumbnail.id"
          class="relative group"
        >
          <img
            :src="thumbnail.url"
            alt="Generated thumbnail"
            class="w-full rounded-lg"
          />
          <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center space-x-2">
            <button
              @click="selectThumbnail(thumbnail)"
              class="btn-white btn-sm"
            >
              Select
            </button>
            <button
              @click="editThumbnail(thumbnail)"
              class="btn-white btn-sm"
            >
              Edit
            </button>
          </div>
        </div>
      </div>
      
      <button
        @click="generateMore"
        class="mt-3 text-sm text-primary-600 hover:text-primary-700"
      >
        Generate more variations
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const mediaStore = useMediaStore()
const { generateThumbnail } = mediaStore

const generateThumbnails = async () => {
  isGenerating.value = true
  
  try {
    const results = await generateThumbnail({
      sermonId: props.sermonId,
      style: selectedStyle.value,
      customPrompt: customPrompt.value,
      includeText: includeText.value,
      variations: 4
    })
    
    generatedThumbnails.value = results
  } finally {
    isGenerating.value = false
  }
}
</script>
```

### 4.5 Public Sermon Archive

```vue
<!-- pages/sermons/index.vue -->
<template>
  <div class="sermon-archive">
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16">
      <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4">Sermon Archive</h1>
        <p class="text-xl opacity-90">
          Explore our collection of sermons in multiple languages
        </p>
      </div>
    </section>
    
    <!-- Filters -->
    <section class="filters-section bg-gray-50 py-6 sticky top-0 z-10">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap gap-4">
          <!-- Search -->
          <div class="flex-1 min-w-[200px]">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search sermons..."
              class="w-full rounded-lg border-gray-300"
            />
          </div>
          
          <!-- Language Filter -->
          <select 
            v-model="filters.language"
            class="rounded-lg border-gray-300"
          >
            <option value="">All Languages</option>
            <option value="en">English</option>
            <option value="af">Afrikaans</option>
            <option value="xh">Xhosa</option>
            <option value="ny">Chichewa</option>
          </select>
          
          <!-- Series Filter -->
          <select 
            v-model="filters.series"
            class="rounded-lg border-gray-300"
          >
            <option value="">All Series</option>
            <option 
              v-for="series in availableSeries"
              :key="series.id"
              :value="series.id"
            >
              {{ series.title }}
            </option>
          </select>
          
          <!-- Date Range -->
          <input
            v-model="filters.dateFrom"
            type="date"
            class="rounded-lg border-gray-300"
          />
          <input
            v-model="filters.dateTo"
            type="date"
            class="rounded-lg border-gray-300"
          />
        </div>
      </div>
    </section>
    
    <!-- Sermon Grid -->
    <section class="sermons-section py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <SermonCard
            v-for="sermon in sermons"
            :key="sermon.id"
            :sermon="sermon"
            @click="navigateTo(`/sermons/${sermon.id}`)"
          />
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
          <Pagination
            v-model="currentPage"
            :total-pages="totalPages"
            @update:model-value="loadSermons"
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: 'Sermon Archive - PMA Church',
  meta: [
    {
      name: 'description',
      content: 'Browse our collection of sermons in English, Afrikaans, Xhosa, and Chichewa'
    }
  ]
})

// Data fetching
const { $api } = useNuxtApp()
const filters = reactive({
  search: '',
  language: '',
  series: '',
  dateFrom: '',
  dateTo: ''
})

const { data: sermons, pending, refresh } = await useAsyncData(
  'sermons',
  () => $api.sermons.list({
    ...filters,
    page: currentPage.value,
    perPage: 12
  }),
  {
    watch: [filters, currentPage]
  }
)
</script>
```

---

## 5. State Management

### 5.1 Pinia Store Architecture

```typescript
// stores/auth.ts
export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isAuthenticated = computed(() => !!token.value)
  
  // Actions
  const login = async (credentials: LoginCredentials) => {
    const { data } = await $api.auth.login(credentials)
    user.value = data.user
    token.value = data.token
    
    // Store token in cookie for SSR
    const tokenCookie = useCookie('auth-token', {
      httpOnly: true,
      secure: true,
      sameSite: 'lax',
      maxAge: 60 * 60 * 24 * 7 // 7 days
    })
    tokenCookie.value = data.token
    
    await navigateTo('/admin')
  }
  
  const logout = async () => {
    await $api.auth.logout()
    user.value = null
    token.value = null
    await navigateTo('/auth/login')
  }
  
  const fetchUser = async () => {
    if (!token.value) return
    
    try {
      const { data } = await $api.auth.user()
      user.value = data
    } catch (error) {
      await logout()
    }
  }
  
  return {
    user: readonly(user),
    token: readonly(token),
    isAuthenticated,
    login,
    logout,
    fetchUser
  }
})
```

### 5.2 Sermon Store

```typescript
// stores/sermons.ts
export const useSermonsStore = defineStore('sermons', () => {
  // State
  const sermons = ref<Sermon[]>([])
  const currentSermon = ref<Sermon | null>(null)
  const isLoading = ref(false)
  const filters = reactive({
    search: '',
    status: '',
    language: '',
    authorId: null,
    seriesId: null
  })
  
  // Getters
  const publishedSermons = computed(() => 
    sermons.value.filter(s => s.status === 'published')
  )
  
  const draftSermons = computed(() => 
    sermons.value.filter(s => s.status === 'draft')
  )
  
  // Actions
  const fetchSermons = async (options?: FetchOptions) => {
    isLoading.value = true
    try {
      const { data } = await $api.sermons.list({
        ...filters,
        ...options
      })
      sermons.value = data.data
      return data
    } finally {
      isLoading.value = false
    }
  }
  
  const createSermon = async (data: CreateSermonData) => {
    const { data: sermon } = await $api.sermons.create(data)
    sermons.value.unshift(sermon)
    return sermon
  }
  
  const updateSermon = async (id: number, data: UpdateSermonData) => {
    const { data: updated } = await $api.sermons.update(id, data)
    const index = sermons.value.findIndex(s => s.id === id)
    if (index !== -1) {
      sermons.value[index] = updated
    }
    if (currentSermon.value?.id === id) {
      currentSermon.value = updated
    }
    return updated
  }
  
  return {
    sermons: readonly(sermons),
    currentSermon,
    isLoading: readonly(isLoading),
    filters,
    publishedSermons,
    draftSermons,
    fetchSermons,
    createSermon,
    updateSermon
  }
})
```

---

## 6. API Integration

### 6.1 API Client Configuration

```typescript
// plugins/api.ts
export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  
  const api = $fetch.create({
    baseURL: config.public.apiBase || 'http://localhost:8000/api/v1',
    onRequest({ request, options }) {
      // Add auth token
      const token = useCookie('auth-token')
      if (token.value) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token.value}`
        }
      }
      
      // Add locale
      const locale = useNuxtData('locale')
      if (locale.value) {
        options.headers = {
          ...options.headers,
          'Accept-Language': locale.value
        }
      }
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        // Handle unauthorized
        return navigateTo('/auth/login')
      }
      
      if (response.status === 429) {
        // Handle rate limiting
        const retryAfter = response.headers.get('Retry-After')
        useToast().error(`Rate limit exceeded. Try again in ${retryAfter} seconds`)
      }
    }
  })
  
  // Create API modules
  return {
    provide: {
      api: {
        auth: createAuthAPI(api),
        sermons: createSermonsAPI(api),
        bible: createBibleAPI(api),
        ai: createAIAPI(api),
        media: createMediaAPI(api),
        social: createSocialAPI(api),
        analytics: createAnalyticsAPI(api)
      }
    }
  }
})
```

### 6.2 Real-time Integration

```typescript
// plugins/socket.client.ts
export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  const authStore = useAuthStore()
  
  const socket = io(config.public.wsUrl || 'ws://localhost:8000', {
    autoConnect: false,
    auth: {
      token: authStore.token
    }
  })
  
  // Auto-connect when authenticated
  watch(() => authStore.isAuthenticated, (isAuth) => {
    if (isAuth) {
      socket.auth = { token: authStore.token }
      socket.connect()
    } else {
      socket.disconnect()
    }
  }, { immediate: true })
  
  // Global event handlers
  socket.on('connect', () => {
    console.log('WebSocket connected')
  })
  
  socket.on('error', (error) => {
    console.error('WebSocket error:', error)
  })
  
  return {
    provide: {
      socket
    }
  }
})
```

---

## 7. Performance Requirements

### 7.1 Core Web Vitals Targets
- **Largest Contentful Paint (LCP)**: < 2.5s
- **First Input Delay (FID)**: < 100ms
- **Cumulative Layout Shift (CLS)**: < 0.1
- **Time to Interactive (TTI)**: < 3.5s

### 7.2 Optimization Strategies

#### Code Splitting
```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  nitro: {
    prerender: {
      routes: ['/sermons', '/']
    }
  },
  
  vite: {
    build: {
      rollupOptions: {
        output: {
          manualChunks: {
            'editor': ['@tiptap/vue-3', '@tiptap/starter-kit'],
            'ui': ['@headlessui/vue', '@heroicons/vue'],
            'forms': ['vee-validate', 'yup']
          }
        }
      }
    }
  }
})
```

#### Image Optimization
```vue
<template>
  <NuxtImg
    :src="sermon.thumbnailUrl"
    :alt="sermon.title"
    loading="lazy"
    :width="400"
    :height="225"
    format="webp"
    quality="80"
    :modifiers="{ fit: 'cover' }"
  />
</template>
```

#### Data Caching
```typescript
// composables/useSermons.ts
export const useSermons = () => {
  const nuxtApp = useNuxtApp()
  
  return useAsyncData(
    'sermons',
    () => $api.sermons.list(),
    {
      // Cache for 5 minutes
      getCachedData(key) {
        const data = nuxtApp.payload.data[key] || nuxtApp.static.data[key]
        if (!data) return
        
        const expirationDate = new Date(data.fetchedAt)
        expirationDate.setTime(expirationDate.getTime() + 5 * 60 * 1000)
        
        if (new Date() < expirationDate) {
          return data
        }
      }
    }
  )
}
```

---

## 8. Accessibility & Internationalization

### 8.1 Accessibility Requirements

#### WCAG 2.1 AA Compliance
```vue
<!-- components/ui/BaseButton.vue -->
<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :aria-label="ariaLabel"
    :aria-busy="loading"
    :aria-pressed="pressed"
    :class="buttonClasses"
    @click="handleClick"
  >
    <LoadingSpinner v-if="loading" class="mr-2" />
    <slot />
  </button>
</template>

<script setup lang="ts">
// Keyboard navigation support
const handleClick = (event: MouseEvent | KeyboardEvent) => {
  if (disabled.value || loading.value) return
  
  // Ensure button behaves correctly with keyboard
  if (event instanceof KeyboardEvent) {
    if (event.key !== 'Enter' && event.key !== ' ') return
    event.preventDefault()
  }
  
  emit('click', event)
}
</script>
```

### 8.2 Internationalization Setup

```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  modules: ['@nuxtjs/i18n'],
  
  i18n: {
    locales: [
      { code: 'en', iso: 'en-US', file: 'en.json', name: 'English' },
      { code: 'af', iso: 'af-ZA', file: 'af.json', name: 'Afrikaans' },
      { code: 'xh', iso: 'xh-ZA', file: 'xh.json', name: 'Xhosa' },
      { code: 'ny', iso: 'ny-MW', file: 'ny.json', name: 'Chichewa' }
    ],
    defaultLocale: 'en',
    strategy: 'prefix_except_default',
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_locale',
      redirectOn: 'root'
    }
  }
})
```

#### Translation Files
```json
// locales/en.json
{
  "common": {
    "save": "Save",
    "cancel": "Cancel",
    "delete": "Delete",
    "edit": "Edit",
    "search": "Search",
    "loading": "Loading..."
  },
  "sermon": {
    "title": "Sermon Title",
    "content": "Sermon Content",
    "date": "Sermon Date",
    "series": "Sermon Series",
    "create": "Create New Sermon",
    "publish": "Publish Sermon",
    "save_draft": "Save as Draft"
  },
  "bible": {
    "search": "Search Bible",
    "book": "Book",
    "chapter": "Chapter",
    "verse": "Verse",
    "translation": "Translation"
  }
}
```

---

## 9. Testing Requirements

### 9.1 Unit Tests

```typescript
// components/sermon/SermonCard.spec.ts
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import SermonCard from './SermonCard.vue'

describe('SermonCard', () => {
  const mockSermon = {
    id: 1,
    title: 'Test Sermon',
    excerpt: 'Test excerpt',
    sermonDate: '2025-01-19',
    author: { name: 'John Doe' },
    thumbnailUrl: '/test.jpg'
  }
  
  it('renders sermon information correctly', () => {
    const wrapper = mount(SermonCard, {
      props: { sermon: mockSermon }
    })
    
    expect(wrapper.find('h3').text()).toBe('Test Sermon')
    expect(wrapper.find('.excerpt').text()).toBe('Test excerpt')
    expect(wrapper.find('.author').text()).toContain('John Doe')
  })
  
  it('emits click event when clicked', async () => {
    const wrapper = mount(SermonCard, {
      props: { sermon: mockSermon }
    })
    
    await wrapper.trigger('click')
    expect(wrapper.emitted('click')).toHaveLength(1)
  })
})
```

### 9.2 Integration Tests

```typescript
// tests/integration/sermon-editor.spec.ts
import { test, expect } from '@playwright/test'

test.describe('Sermon Editor', () => {
  test.beforeEach(async ({ page }) => {
    // Login
    await page.goto('/auth/login')
    await page.fill('[name="email"]', 'test@example.com')
    await page.fill('[name="password"]', 'password')
    await page.click('button[type="submit"]')
    
    // Navigate to editor
    await page.goto('/admin/sermons/create')
  })
  
  test('should create a new sermon', async ({ page }) => {
    // Fill sermon details
    await page.fill('[name="title"]', 'Test Sermon')
    await page.fill('.tiptap-editor', 'This is test content')
    await page.selectOption('[name="language"]', 'en')
    
    // Save draft
    await page.click('button:has-text("Save Draft")')
    
    // Verify success
    await expect(page.locator('.toast-success')).toBeVisible()
    await expect(page).toHaveURL(/\/admin\/sermons\/\d+\/edit/)
  })
  
  test('should insert Bible verse', async ({ page }) => {
    // Open Bible search
    await page.click('button:has-text("Insert Verse")')
    
    // Search for verse
    await page.fill('.bible-search-input', 'John 3:16')
    await page.waitForSelector('.verse-card')
    
    // Insert verse
    await page.click('.verse-card button:has-text("Insert")')
    
    // Verify insertion
    const editorContent = await page.locator('.tiptap-editor').textContent()
    expect(editorContent).toContain('For God so loved')
  })
})
```

---

## 10. Deployment & Build Process

### 10.1 Build Configuration

```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  nitro: {
    preset: 'vercel',
    compressPublicAssets: true
  },
  
  build: {
    transpile: ['@headlessui/vue', '@heroicons/vue']
  },
  
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api/v1',
      wsUrl: process.env.NUXT_PUBLIC_WS_URL || 'ws://localhost:8000',
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000'
    }
  },
  
  sourcemap: {
    server: false,
    client: process.env.NODE_ENV === 'development'
  }
})
```

### 10.2 CI/CD Pipeline

```yaml
# .github/workflows/deploy.yml
name: Deploy Frontend

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          
      - name: Install dependencies
        run: npm ci
        
      - name: Run tests
        run: npm test
        
      - name: Run type check
        run: npm run typecheck
        
      - name: Run linter
        run: npm run lint
        
  build-and-deploy:
    needs: test
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          
      - name: Install dependencies
        run: npm ci
        
      - name: Build application
        run: npm run build
        env:
          NUXT_PUBLIC_API_BASE: ${{ secrets.API_BASE_URL }}
          NUXT_PUBLIC_WS_URL: ${{ secrets.WS_URL }}
          
      - name: Deploy to Vercel
        uses: amondnet/vercel-action@v20
        with:
          vercel-token: ${{ secrets.VERCEL_TOKEN }}
          vercel-args: '--prod'
          vercel-org-id: ${{ secrets.VERCEL_ORG_ID }}
          vercel-project-id: ${{ secrets.VERCEL_PROJECT_ID }}
```

### 10.3 Environment Variables

```env
# .env.production
NUXT_PUBLIC_API_BASE=https://api.pma-church.com/v1
NUXT_PUBLIC_WS_URL=wss://api.pma-church.com
NUXT_PUBLIC_SITE_URL=https://pma-church.com

# Analytics
NUXT_PUBLIC_GA_ID=G-XXXXXXXXXX
NUXT_PUBLIC_SENTRY_DSN=https://xxx@sentry.io/xxx

# Feature Flags
NUXT_PUBLIC_ENABLE_AI=true
NUXT_PUBLIC_ENABLE_SOCIAL=true
NUXT_PUBLIC_ENABLE_ANALYTICS=true
```

---

## Success Metrics

### Technical Metrics
- Page load time < 3s on 3G connection
- 95+ Lighthouse performance score
- Zero accessibility violations
- 80%+ test coverage
- TypeScript strict mode compliance

### User Experience Metrics
- Task completion rate > 90%
- User error rate < 5%
- Mobile usage > 40%
- Average session duration > 10 minutes
- Feature adoption rate > 70%

---

This Frontend PRD provides comprehensive requirements for the Nuxt 3 development, ensuring a modern, performant, and user-friendly interface that integrates seamlessly with the Laravel API backend.