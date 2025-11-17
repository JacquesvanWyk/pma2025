# Database Schema Design Document
## PMA Sermon Management System

### Table of Contents
1. [Overview & Architecture](#overview--architecture)
2. [Database Tables](#database-tables)
3. [Relationships & Constraints](#relationships--constraints)
4. [Indexes & Performance](#indexes--performance)
5. [Sample Data & JSON Formats](#sample-data--json-formats)

---

## 1. Overview & Architecture

### Database Type
- **Engine**: MySQL 8.0+ / PostgreSQL 14+
- **Character Set**: utf8mb4 (for multi-language support)
- **Collation**: utf8mb4_unicode_ci

### Key Design Principles
- **Multi-language Support**: All content tables support multiple languages
- **Soft Deletes**: Maintain data history with deleted_at timestamps
- **UUID Support**: Optional UUID fields for external API references
- **Audit Trail**: Created/updated timestamps on all tables
- **Optimized for Search**: Full-text indexes on searchable content

---

## 2. Database Tables

### 2.1 Users & Authentication

#### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'senior_pastor', 'pastor', 'member') DEFAULT 'member',
    language_preference VARCHAR(10) DEFAULT 'en',
    timezone VARCHAR(50) DEFAULT 'Africa/Johannesburg',
    avatar_url VARCHAR(500) NULL,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_deleted_at (deleted_at)
);
```

#### personal_access_tokens
```sql
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_tokenable (tokenable_type, tokenable_id),
    INDEX idx_token (token)
);
```

### 2.2 Sermons & Series

#### sermon_series
```sql
CREATE TABLE sermon_series (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,
    thumbnail_url VARCHAR(500) NULL,
    theme_color VARCHAR(7) DEFAULT '#000000',
    created_by BIGINT UNSIGNED NOT NULL,
    status ENUM('draft', 'active', 'completed', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_deleted_at (deleted_at)
);
```

#### sermons
```sql
CREATE TABLE sermons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) UNIQUE NOT NULL,
    series_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT NULL,
    sermon_date DATE NOT NULL,
    sermon_time TIME NULL,
    duration_minutes INT UNSIGNED NULL,
    primary_scripture VARCHAR(255) NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    template_id BIGINT UNSIGNED NULL,
    language VARCHAR(10) DEFAULT 'en',
    status ENUM('draft', 'review', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    views_count INT UNSIGNED DEFAULT 0,
    downloads_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (series_id) REFERENCES sermon_series(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES sermon_templates(id) ON DELETE SET NULL,
    INDEX idx_sermon_date (sermon_date),
    INDEX idx_status (status),
    INDEX idx_language (language),
    INDEX idx_author (author_id),
    INDEX idx_series (series_id),
    FULLTEXT idx_fulltext_search (title, subtitle, content, excerpt)
);
```

#### sermon_translations
```sql
CREATE TABLE sermon_translations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    language VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT NULL,
    translated_by_ai BOOLEAN DEFAULT TRUE,
    verified_by_user_id BIGINT UNSIGNED NULL,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_sermon_language (sermon_id, language),
    INDEX idx_language (language),
    FULLTEXT idx_fulltext_search (title, subtitle, content, excerpt)
);
```

#### sermon_templates
```sql
CREATE TABLE sermon_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    structure JSON NOT NULL,
    sections JSON NOT NULL,
    default_duration_minutes INT UNSIGNED DEFAULT 30,
    created_by BIGINT UNSIGNED NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    usage_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_is_public (is_public),
    INDEX idx_created_by (created_by)
);
```

### 2.3 Bible Integration

#### bible_verses
```sql
CREATE TABLE bible_verses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book VARCHAR(50) NOT NULL,
    chapter INT UNSIGNED NOT NULL,
    verse INT UNSIGNED NOT NULL,
    translation VARCHAR(20) NOT NULL,
    language VARCHAR(10) NOT NULL,
    text TEXT NOT NULL,
    formatted_reference VARCHAR(100) NOT NULL,
    api_source VARCHAR(50) DEFAULT 'api.bible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_verse (book, chapter, verse, translation),
    INDEX idx_reference (book, chapter, verse),
    INDEX idx_translation_language (translation, language),
    FULLTEXT idx_verse_text (text)
);
```

#### sermon_verses
```sql
CREATE TABLE sermon_verses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    verse_id BIGINT UNSIGNED NOT NULL,
    position INT UNSIGNED DEFAULT 0,
    context_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES bible_verses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sermon_verse (sermon_id, verse_id),
    INDEX idx_sermon_position (sermon_id, position)
);
```

### 2.4 AI Integration

#### ai_conversations
```sql
CREATE TABLE ai_conversations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    sermon_id BIGINT UNSIGNED NULL,
    session_id VARCHAR(100) NOT NULL,
    provider VARCHAR(50) NOT NULL,
    model VARCHAR(100) NOT NULL,
    title VARCHAR(255) NULL,
    total_tokens_used INT UNSIGNED DEFAULT 0,
    estimated_cost DECIMAL(10, 4) DEFAULT 0.0000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    INDEX idx_user_session (user_id, session_id),
    INDEX idx_sermon (sermon_id),
    INDEX idx_created_at (created_at)
);
```

#### ai_messages
```sql
CREATE TABLE ai_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conversation_id BIGINT UNSIGNED NOT NULL,
    role ENUM('user', 'assistant', 'system') NOT NULL,
    content TEXT NOT NULL,
    tokens_used INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (conversation_id) REFERENCES ai_conversations(id) ON DELETE CASCADE,
    INDEX idx_conversation (conversation_id),
    INDEX idx_created_at (created_at)
);
```

#### ai_suggestions
```sql
CREATE TABLE ai_suggestions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    type ENUM('outline', 'illustration', 'application', 'verse', 'general') NOT NULL,
    content TEXT NOT NULL,
    applied BOOLEAN DEFAULT FALSE,
    applied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    INDEX idx_sermon_type (sermon_id, type),
    INDEX idx_applied (applied)
);
```

### 2.5 Media & Files

#### media_files
```sql
CREATE TABLE media_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) UNIQUE NOT NULL,
    sermon_id BIGINT UNSIGNED NULL,
    type ENUM('thumbnail', 'powerpoint', 'pdf', 'audio', 'video', 'image') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size_bytes BIGINT UNSIGNED NOT NULL,
    storage_path VARCHAR(500) NOT NULL,
    public_url VARCHAR(500) NULL,
    metadata JSON NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sermon_type (sermon_id, type),
    INDEX idx_type (type),
    INDEX idx_uploaded_by (uploaded_by)
);
```

#### generated_thumbnails
```sql
CREATE TABLE generated_thumbnails (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    media_file_id BIGINT UNSIGNED NOT NULL,
    prompt TEXT NOT NULL,
    style_preset VARCHAR(100) NULL,
    provider VARCHAR(50) DEFAULT 'replicate',
    model VARCHAR(100) NOT NULL,
    generation_time_seconds INT UNSIGNED NOT NULL,
    cost DECIMAL(10, 4) DEFAULT 0.0000,
    selected BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (media_file_id) REFERENCES media_files(id) ON DELETE CASCADE,
    INDEX idx_sermon_selected (sermon_id, selected)
);
```

#### powerpoint_presentations
```sql
CREATE TABLE powerpoint_presentations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    media_file_id BIGINT UNSIGNED NOT NULL,
    template_name VARCHAR(100) NULL,
    slide_count INT UNSIGNED NOT NULL,
    includes_animations BOOLEAN DEFAULT FALSE,
    auto_generated BOOLEAN DEFAULT TRUE,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (media_file_id) REFERENCES media_files(id) ON DELETE CASCADE,
    INDEX idx_sermon (sermon_id)
);
```

### 2.6 Social Media Integration

#### social_media_posts
```sql
CREATE TABLE social_media_posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    platform ENUM('facebook', 'whatsapp', 'instagram', 'twitter') NOT NULL,
    content TEXT NOT NULL,
    media_file_id BIGINT UNSIGNED NULL,
    language VARCHAR(10) NOT NULL,
    status ENUM('draft', 'approved', 'scheduled', 'published', 'failed') DEFAULT 'draft',
    scheduled_for TIMESTAMP NULL,
    published_at TIMESTAMP NULL,
    platform_post_id VARCHAR(255) NULL,
    engagement_data JSON NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (media_file_id) REFERENCES media_files(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_sermon_platform (sermon_id, platform),
    INDEX idx_status (status),
    INDEX idx_scheduled_for (scheduled_for)
);
```

### 2.7 Analytics & Tracking

#### sermon_analytics
```sql
CREATE TABLE sermon_analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    views INT UNSIGNED DEFAULT 0,
    unique_views INT UNSIGNED DEFAULT 0,
    downloads INT UNSIGNED DEFAULT 0,
    shares INT UNSIGNED DEFAULT 0,
    average_read_time_seconds INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sermon_date (sermon_id, date),
    INDEX idx_date (date)
);
```

#### sermon_topics
```sql
CREATE TABLE sermon_topics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    usage_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_usage_count (usage_count)
);
```

#### sermon_topic_mappings
```sql
CREATE TABLE sermon_topic_mappings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sermon_id BIGINT UNSIGNED NOT NULL,
    topic_id BIGINT UNSIGNED NOT NULL,
    relevance_score DECIMAL(3, 2) DEFAULT 1.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sermon_id) REFERENCES sermons(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES sermon_topics(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sermon_topic (sermon_id, topic_id),
    INDEX idx_topic_relevance (topic_id, relevance_score)
);
```

### 2.8 Church Assets & Configuration

#### church_assets
```sql
CREATE TABLE church_assets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('logo', 'watermark', 'background', 'slide_template') NOT NULL,
    name VARCHAR(255) NOT NULL,
    media_file_id BIGINT UNSIGNED NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    settings JSON NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (media_file_id) REFERENCES media_files(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_type_default (type, is_default)
);
```

#### system_settings
```sql
CREATE TABLE system_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group VARCHAR(100) NOT NULL,
    key VARCHAR(100) NOT NULL,
    value TEXT NULL,
    type ENUM('string', 'integer', 'boolean', 'json', 'array') DEFAULT 'string',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_group_key (group, key),
    INDEX idx_group (group)
);
```

---

## 3. Relationships & Constraints

### Primary Relationships
1. **Users → Sermons** (One-to-Many): A user can create many sermons
2. **Sermon Series → Sermons** (One-to-Many): A series can contain many sermons
3. **Sermons → Translations** (One-to-Many): A sermon can have many translations
4. **Sermons → Bible Verses** (Many-to-Many): Through sermon_verses pivot table
5. **Sermons → Media Files** (One-to-Many): A sermon can have many media files
6. **Sermons → Social Media Posts** (One-to-Many): A sermon can have many social posts
7. **Sermons → AI Conversations** (One-to-Many): A sermon can have many AI conversations
8. **Sermons → Topics** (Many-to-Many): Through sermon_topic_mappings pivot table

### Cascade Rules
- **User Deletion**: Cascades to sermons, conversations, and uploads
- **Sermon Deletion**: Cascades to translations, media, social posts, analytics
- **Series Deletion**: Sets sermon series_id to NULL (soft relationship)

### Unique Constraints
- **Users**: email must be unique
- **Sermon Translations**: (sermon_id, language) combination must be unique
- **Bible Verses**: (book, chapter, verse, translation) combination must be unique
- **System Settings**: (group, key) combination must be unique

---

## 4. Indexes & Performance

### Primary Performance Indexes
1. **Full-Text Search Indexes**:
   - sermons: (title, subtitle, content, excerpt)
   - sermon_translations: (title, subtitle, content, excerpt)
   - bible_verses: (text)

2. **Foreign Key Indexes**: All foreign keys are indexed automatically

3. **Composite Indexes**:
   - sermon_verses: (sermon_id, position) for ordered verse retrieval
   - sermon_analytics: (sermon_id, date) for time-series queries
   - social_media_posts: (sermon_id, platform) for platform-specific queries

4. **Date/Time Indexes**:
   - sermons: sermon_date for calendar queries
   - social_media_posts: scheduled_for for scheduling queries
   - ai_conversations: created_at for recent activity queries

### Query Optimization Considerations
- **Pagination**: Use cursor-based pagination for large result sets
- **Eager Loading**: Use with() for related data to avoid N+1 queries
- **Caching**: Cache frequently accessed data (verses, templates, settings)
- **Partitioning**: Consider partitioning analytics tables by date for large datasets

---

## 5. Sample Data & JSON Formats

### 5.1 Sermon Response Format
```json
{
  "id": 1,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "title": "Walking in Faith",
  "subtitle": "Understanding John 3:16",
  "content": "<h2>Introduction</h2><p>Today we explore...</p>",
  "excerpt": "A deep dive into God's love for humanity...",
  "sermon_date": "2025-01-19",
  "sermon_time": "10:00:00",
  "duration_minutes": 45,
  "primary_scripture": "John 3:16",
  "language": "en",
  "status": "published",
  "published_at": "2025-01-19T08:00:00Z",
  "views_count": 342,
  "downloads_count": 56,
  "author": {
    "id": 1,
    "name": "Pastor John Smith",
    "role": "senior_pastor",
    "avatar_url": "https://..."
  },
  "series": {
    "id": 5,
    "uuid": "660e8400-e29b-41d4-a716-446655440001",
    "title": "Gospel Foundations",
    "theme_color": "#3B82F6"
  },
  "translations": [
    {
      "language": "af",
      "title": "Loop in Geloof",
      "subtitle": "Verstaan Johannes 3:16",
      "excerpt": "'n Diep duik in God se liefde vir die mensdom...",
      "translated_by_ai": true,
      "verified": false
    },
    {
      "language": "xh",
      "title": "Ukuhamba Ngokholo",
      "subtitle": "Ukuqonda uYohane 3:16",
      "verified": true,
      "verified_by": {
        "id": 3,
        "name": "Rev. Sipho Ndlovu"
      }
    }
  ],
  "verses": [
    {
      "id": 1001,
      "book": "John",
      "chapter": 3,
      "verse": 16,
      "translation": "NIV",
      "language": "en",
      "text": "For God so loved the world that he gave his one and only Son...",
      "formatted_reference": "John 3:16 (NIV)",
      "context_notes": "Central theme of the sermon"
    }
  ],
  "media": {
    "thumbnail": {
      "id": 101,
      "uuid": "770e8400-e29b-41d4-a716-446655440002",
      "type": "thumbnail",
      "filename": "sermon-20250119-thumbnail.jpg",
      "public_url": "https://cdn.example.com/thumbnails/...",
      "size_bytes": 245760
    },
    "powerpoint": {
      "id": 102,
      "uuid": "880e8400-e29b-41d4-a716-446655440003",
      "type": "powerpoint",
      "filename": "sermon-20250119-slides.pptx",
      "public_url": "https://cdn.example.com/presentations/...",
      "size_bytes": 5242880,
      "slide_count": 15
    },
    "audio": null,
    "video": null
  },
  "topics": [
    {
      "id": 10,
      "name": "Faith",
      "slug": "faith",
      "relevance_score": 0.95
    },
    {
      "id": 15,
      "name": "Salvation",
      "slug": "salvation",
      "relevance_score": 0.85
    }
  ],
  "social_media": {
    "facebook": {
      "id": 201,
      "status": "published",
      "published_at": "2025-01-19T12:00:00Z",
      "platform_post_id": "fb_123456789",
      "engagement": {
        "likes": 45,
        "shares": 12,
        "comments": 8
      }
    },
    "whatsapp": {
      "id": 202,
      "status": "scheduled",
      "scheduled_for": "2025-01-19T14:00:00Z"
    }
  },
  "ai_usage": {
    "conversations_count": 3,
    "suggestions_count": 12,
    "suggestions_applied": 5,
    "total_tokens_used": 4532,
    "estimated_cost": 0.0453
  },
  "created_at": "2025-01-15T10:30:00Z",
  "updated_at": "2025-01-19T07:45:00Z"
}
```

### 5.2 Bible Verse Search Response
```json
{
  "query": "love",
  "translation": "NIV",
  "language": "en",
  "results": [
    {
      "id": 1001,
      "book": "John",
      "chapter": 3,
      "verse": 16,
      "text": "For God so loved the world that he gave his one and only Son...",
      "formatted_reference": "John 3:16 (NIV)",
      "relevance_score": 0.98
    },
    {
      "id": 1245,
      "book": "1 Corinthians",
      "chapter": 13,
      "verse": 4,
      "text": "Love is patient, love is kind. It does not envy...",
      "formatted_reference": "1 Corinthians 13:4 (NIV)",
      "relevance_score": 0.95
    }
  ],
  "total_results": 156,
  "page": 1,
  "per_page": 20
}
```

### 5.3 AI Conversation Format
```json
{
  "id": 501,
  "session_id": "sess_abc123",
  "sermon_id": 1,
  "provider": "openai",
  "model": "gpt-4",
  "title": "Research on sacrificial love",
  "messages": [
    {
      "id": 5001,
      "role": "user",
      "content": "Give me examples of sacrificial love from everyday life",
      "created_at": "2025-01-15T10:35:00Z"
    },
    {
      "id": 5002,
      "role": "assistant",
      "content": "Here are some powerful examples of sacrificial love...",
      "tokens_used": 245,
      "created_at": "2025-01-15T10:35:05Z"
    }
  ],
  "total_tokens_used": 892,
  "estimated_cost": 0.0089,
  "created_at": "2025-01-15T10:35:00Z",
  "ended_at": "2025-01-15T10:45:00Z"
}
```

### 5.4 Social Media Post Format
```json
{
  "id": 201,
  "sermon_id": 1,
  "platform": "facebook",
  "content": "🙏 This Sunday: Walking in Faith - Understanding John 3:16\n\nJoin us as we explore God's incredible love for humanity...\n\n📅 Sunday, Jan 19 @ 10:00 AM\n📍 PMA Church\n\n#Faith #Gospel #Sunday",
  "language": "en",
  "status": "approved",
  "scheduled_for": "2025-01-19T12:00:00Z",
  "media": {
    "id": 101,
    "type": "thumbnail",
    "public_url": "https://cdn.example.com/thumbnails/..."
  },
  "created_by": {
    "id": 1,
    "name": "Pastor John Smith"
  },
  "approved_by": {
    "id": 2,
    "name": "Admin User"
  },
  "approved_at": "2025-01-18T15:00:00Z"
}
```

### 5.5 Analytics Summary Format
```json
{
  "sermon_id": 1,
  "period": "last_30_days",
  "metrics": {
    "total_views": 1245,
    "unique_views": 892,
    "total_downloads": 156,
    "total_shares": 45,
    "average_read_time_seconds": 420,
    "by_language": {
      "en": {
        "views": 650,
        "downloads": 89
      },
      "af": {
        "views": 345,
        "downloads": 45
      },
      "xh": {
        "views": 178,
        "downloads": 15
      },
      "ny": {
        "views": 72,
        "downloads": 7
      }
    },
    "by_platform": {
      "web": 780,
      "mobile": 465
    },
    "social_engagement": {
      "facebook": {
        "likes": 145,
        "shares": 34,
        "comments": 23
      },
      "whatsapp": {
        "forwards": 89
      }
    }
  },
  "trend": {
    "views": "+12.5%",
    "downloads": "+8.3%",
    "engagement": "+15.2%"
  }
}
```

---

## Migration Order

To ensure proper foreign key constraints, create tables in this order:

1. **Independent Tables** (no foreign keys):
   - users
   - sermon_topics
   - system_settings

2. **First Level Dependencies**:
   - personal_access_tokens (depends on users)
   - sermon_series (depends on users)
   - sermon_templates (depends on users)
   - bible_verses

3. **Second Level Dependencies**:
   - sermons (depends on users, sermon_series, sermon_templates)
   - ai_conversations (depends on users)
   - media_files (depends on users)

4. **Third Level Dependencies**:
   - sermon_translations (depends on sermons, users)
   - sermon_verses (depends on sermons, bible_verses)
   - ai_messages (depends on ai_conversations)
   - ai_suggestions (depends on sermons)
   - generated_thumbnails (depends on sermons, media_files)
   - powerpoint_presentations (depends on sermons, media_files)
   - social_media_posts (depends on sermons, media_files, users)
   - sermon_analytics (depends on sermons)
   - sermon_topic_mappings (depends on sermons, sermon_topics)
   - church_assets (depends on media_files, users)

---

## Additional Considerations

### Data Retention Policy
- Soft delete sermons (keep for historical reference)
- Hard delete AI conversations after 90 days (privacy)
- Archive analytics data after 2 years
- Keep Bible verse cache indefinitely

### Backup Strategy
- Daily incremental backups
- Weekly full backups
- Store sermon content in version control
- Export critical data to cold storage monthly

### Security Considerations
- Encrypt sensitive data at rest
- Use row-level security for multi-tenant scenarios
- Audit log for all data modifications
- Regular security scans for SQL injection vulnerabilities

---

This database schema provides a comprehensive foundation for both the Laravel backend and Nuxt frontend development teams, ensuring consistent data structures and clear relationships throughout the application.