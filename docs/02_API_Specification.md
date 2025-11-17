# API Specification Document
## PMA Sermon Management System

### Table of Contents
1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Common Response Formats](#common-response-formats)
4. [API Endpoints](#api-endpoints)
   - [Authentication Endpoints](#authentication-endpoints)
   - [Sermon Management](#sermon-management)
   - [Bible Integration](#bible-integration)
   - [AI Assistant](#ai-assistant)
   - [Media & Files](#media--files)
   - [Social Media](#social-media)
   - [Analytics](#analytics)
5. [Error Handling](#error-handling)
6. [Rate Limiting](#rate-limiting)

---

## 1. Overview

### Base URL
```
Production: https://api.pma-church.com/v1
Development: http://localhost:8000/api/v1
```

### Request Headers
```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
X-Requested-With: XMLHttpRequest
Accept-Language: en,af,xh,ny
```

### Supported HTTP Methods
- `GET` - Retrieve resources
- `POST` - Create new resources
- `PUT/PATCH` - Update resources
- `DELETE` - Delete resources

---

## 2. Authentication

### Token-Based Authentication (Laravel Sanctum)

#### Login
```http
POST /auth/login
```

**Request:**
```json
{
  "email": "pastor@example.com",
  "password": "password123",
  "remember": true
}
```

**Response (200 OK):**
```json
{
  "user": {
    "id": 1,
    "name": "Pastor John Smith",
    "email": "pastor@example.com",
    "role": "senior_pastor",
    "language_preference": "en",
    "timezone": "Africa/Johannesburg",
    "avatar_url": "https://..."
  },
  "token": "1|laravel_sanctum_token_here...",
  "token_type": "Bearer",
  "expires_at": "2025-02-01T10:00:00Z"
}
```

---

## 3. Common Response Formats

### Success Response
```json
{
  "data": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 156,
    "last_page": 8
  },
  "links": {
    "first": "https://api.pma-church.com/v1/sermons?page=1",
    "last": "https://api.pma-church.com/v1/sermons?page=8",
    "prev": null,
    "next": "https://api.pma-church.com/v1/sermons?page=2"
  }
}
```

### Error Response
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "sermon_date": ["The sermon date must be a valid date."]
  }
}
```

---

## 4. API Endpoints

### Authentication Endpoints

#### Register
```http
POST /auth/register
```

**Request:**
```json
{
  "name": "New Pastor",
  "email": "newpastor@example.com",
  "password": "SecurePassword123!",
  "password_confirmation": "SecurePassword123!",
  "language_preference": "en",
  "timezone": "Africa/Johannesburg"
}
```

#### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

#### Get Current User
```http
GET /auth/user
Authorization: Bearer {token}
```

#### Update Profile
```http
PUT /auth/user
Authorization: Bearer {token}
```

**Request:**
```json
{
  "name": "Updated Name",
  "language_preference": "af",
  "timezone": "Africa/Cape_Town"
}
```

---

### Sermon Management

#### List Sermons
```http
GET /sermons
```

**Query Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page (max: 100)
- `search` (string): Search in title, content, excerpt
- `series_id` (integer): Filter by series
- `author_id` (integer): Filter by author
- `status` (string): draft|review|published|archived
- `language` (string): en|af|xh|ny
- `from_date` (date): Start date (YYYY-MM-DD)
- `to_date` (date): End date (YYYY-MM-DD)
- `sort` (string): sermon_date|title|views_count|created_at
- `order` (string): asc|desc

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Walking in Faith",
      "subtitle": "Understanding John 3:16",
      "excerpt": "A deep dive into God's love...",
      "sermon_date": "2025-01-19",
      "primary_scripture": "John 3:16",
      "language": "en",
      "status": "published",
      "views_count": 342,
      "author": {
        "id": 1,
        "name": "Pastor John Smith"
      },
      "series": {
        "id": 5,
        "title": "Gospel Foundations"
      },
      "thumbnail_url": "https://cdn.example.com/thumbnails/..."
    }
  ],
  "meta": { ... },
  "links": { ... }
}
```

#### Get Single Sermon
```http
GET /sermons/{id}
```

**Query Parameters:**
- `include` (string): translations,verses,media,topics,analytics,social_media,ai_usage

**Response includes full sermon data as shown in database schema document**

#### Create Sermon
```http
POST /sermons
Authorization: Bearer {token}
```

**Request:**
```json
{
  "title": "Walking in Faith",
  "subtitle": "Understanding John 3:16",
  "content": "<h2>Introduction</h2><p>Today we explore...</p>",
  "excerpt": "A deep dive into God's love for humanity...",
  "sermon_date": "2025-01-19",
  "sermon_time": "10:00:00",
  "duration_minutes": 45,
  "primary_scripture": "John 3:16",
  "series_id": 5,
  "template_id": 1,
  "language": "en",
  "status": "draft"
}
```

#### Update Sermon
```http
PUT /sermons/{id}
Authorization: Bearer {token}
```

#### Delete Sermon
```http
DELETE /sermons/{id}
Authorization: Bearer {token}
```

#### Publish Sermon
```http
POST /sermons/{id}/publish
Authorization: Bearer {token}
```

#### Archive Sermon
```http
POST /sermons/{id}/archive
Authorization: Bearer {token}
```

---

### Sermon Series Management

#### List Series
```http
GET /series
```

#### Get Single Series
```http
GET /series/{id}
```

**Response includes sermons in the series**

#### Create Series
```http
POST /series
Authorization: Bearer {token}
```

**Request:**
```json
{
  "title": "Gospel Foundations",
  "description": "Exploring the core truths of the Gospel",
  "start_date": "2025-01-01",
  "end_date": "2025-02-28",
  "theme_color": "#3B82F6"
}
```

---

### Bible Integration

#### Search Verses
```http
GET /bible/search
```

**Query Parameters:**
- `query` (string, required): Search text
- `translation` (string): Bible translation code (default: NIV)
- `language` (string): Language code (en|af|xh|ny)
- `book` (string): Limit to specific book
- `testament` (string): old|new

**Response:**
```json
{
  "data": [
    {
      "id": 1001,
      "book": "John",
      "chapter": 3,
      "verse": 16,
      "text": "For God so loved the world...",
      "translation": "NIV",
      "language": "en",
      "formatted_reference": "John 3:16 (NIV)"
    }
  ],
  "meta": {
    "total_results": 45,
    "query": "love",
    "translation": "NIV"
  }
}
```

#### Get Verse
```http
GET /bible/verse
```

**Query Parameters:**
- `reference` (string, required): e.g., "John 3:16"
- `translation` (string): Bible translation code
- `language` (string): Language code

#### Get Chapter
```http
GET /bible/chapter
```

**Query Parameters:**
- `book` (string, required): Book name
- `chapter` (integer, required): Chapter number
- `translation` (string): Bible translation code
- `language` (string): Language code

#### Compare Translations
```http
GET /bible/compare
```

**Query Parameters:**
- `reference` (string, required): e.g., "John 3:16"
- `translations` (array): ["NIV", "ESV", "AFR1953"]

---

### AI Assistant

#### Start Conversation
```http
POST /ai/conversations
Authorization: Bearer {token}
```

**Request:**
```json
{
  "sermon_id": 1,
  "provider": "openai",
  "model": "gpt-4",
  "initial_message": "Help me understand the historical context of John 3:16"
}
```

**Response:**
```json
{
  "conversation": {
    "id": 501,
    "session_id": "sess_abc123",
    "sermon_id": 1,
    "provider": "openai",
    "model": "gpt-4"
  },
  "message": {
    "id": 5001,
    "role": "assistant",
    "content": "John 3:16 is part of Jesus' conversation with Nicodemus...",
    "tokens_used": 245
  }
}
```

#### Send Message
```http
POST /ai/conversations/{conversation_id}/messages
Authorization: Bearer {token}
```

**Request:**
```json
{
  "content": "Give me some practical applications for this verse"
}
```

**Response (Streaming):**
```
data: {"chunk": "Here are some practical", "tokens": 5}
data: {"chunk": " applications for John 3:16:", "tokens": 6}
data: {"chunk": "\n\n1. Personal reflection on", "tokens": 5}
data: {"done": true, "total_tokens": 156}
```

#### Get Conversation History
```http
GET /ai/conversations/{conversation_id}
Authorization: Bearer {token}
```

#### Generate Sermon Outline
```http
POST /ai/generate/outline
Authorization: Bearer {token}
```

**Request:**
```json
{
  "topic": "Faith in difficult times",
  "scripture": "Hebrews 11:1",
  "language": "en",
  "duration_minutes": 30
}
```

**Response:**
```json
{
  "outline": {
    "title": "Standing Strong: Faith in the Storm",
    "introduction": "When life's storms rage...",
    "main_points": [
      {
        "title": "Faith is Our Foundation",
        "scripture": "Hebrews 11:1",
        "content": "..."
      },
      {
        "title": "Faith is Our Strength",
        "scripture": "Isaiah 40:31",
        "content": "..."
      },
      {
        "title": "Faith is Our Victory",
        "scripture": "1 John 5:4",
        "content": "..."
      }
    ],
    "conclusion": "As we face life's challenges...",
    "illustrations": ["Story of a lighthouse in a storm", "..."]
  },
  "tokens_used": 892,
  "estimated_cost": 0.0089
}
```

#### Get AI Suggestions
```http
GET /sermons/{id}/ai-suggestions
Authorization: Bearer {token}
```

**Query Parameters:**
- `type` (string): outline|illustration|application|verse|general

---

### Media & Files

#### Upload File
```http
POST /media/upload
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request:**
```
file: (binary)
type: thumbnail|powerpoint|pdf|audio|video|image
sermon_id: 1 (optional)
```

**Response:**
```json
{
  "media": {
    "id": 101,
    "uuid": "770e8400-e29b-41d4-a716-446655440002",
    "type": "thumbnail",
    "filename": "sermon-thumbnail-abc123.jpg",
    "mime_type": "image/jpeg",
    "size_bytes": 245760,
    "public_url": "https://cdn.example.com/media/...",
    "created_at": "2025-01-15T10:30:00Z"
  }
}
```

#### Generate Thumbnail
```http
POST /media/generate-thumbnail
Authorization: Bearer {token}
```

**Request:**
```json
{
  "sermon_id": 1,
  "prompt": "A peaceful sunrise over mountains with a cross, warm colors, photorealistic",
  "style_preset": "biblical",
  "include_text": true,
  "text_overlay": {
    "title": "Walking in Faith",
    "subtitle": "John 3:16"
  }
}
```

**Response:**
```json
{
  "thumbnail": {
    "id": 201,
    "media_file": {
      "id": 101,
      "public_url": "https://cdn.example.com/thumbnails/..."
    },
    "prompt": "A peaceful sunrise...",
    "generation_time_seconds": 8,
    "cost": 0.0150
  }
}
```

#### Generate PowerPoint
```http
POST /media/generate-powerpoint
Authorization: Bearer {token}
```

**Request:**
```json
{
  "sermon_id": 1,
  "template": "modern",
  "include_animations": true,
  "sections": ["title", "scripture", "outline", "points", "conclusion"],
  "branding": {
    "logo_position": "bottom-right",
    "theme_color": "#3B82F6"
  }
}
```

---

### Social Media

#### Generate Social Post
```http
POST /social/generate
Authorization: Bearer {token}
```

**Request:**
```json
{
  "sermon_id": 1,
  "platforms": ["facebook", "whatsapp"],
  "languages": ["en", "af"],
  "include_media": true,
  "ai_generate": true
}
```

**Response:**
```json
{
  "posts": [
    {
      "platform": "facebook",
      "language": "en",
      "content": "🙏 This Sunday: Walking in Faith...",
      "media_file_id": 101,
      "status": "draft"
    },
    {
      "platform": "facebook",
      "language": "af",
      "content": "🙏 Hierdie Sondag: Loop in Geloof...",
      "media_file_id": 101,
      "status": "draft"
    }
  ]
}
```

#### Approve Social Post
```http
POST /social/posts/{id}/approve
Authorization: Bearer {token}
```

#### Schedule Social Post
```http
POST /social/posts/{id}/schedule
Authorization: Bearer {token}
```

**Request:**
```json
{
  "scheduled_for": "2025-01-19T12:00:00Z"
}
```

#### Publish Social Post
```http
POST /social/posts/{id}/publish
Authorization: Bearer {token}
```

---

### Analytics

#### Get Sermon Analytics
```http
GET /sermons/{id}/analytics
Authorization: Bearer {token}
```

**Query Parameters:**
- `period` (string): today|week|month|year|custom
- `from_date` (date): Start date for custom period
- `to_date` (date): End date for custom period
- `metrics` (array): views,downloads,shares,engagement

**Response:**
```json
{
  "analytics": {
    "sermon_id": 1,
    "period": {
      "from": "2025-01-01",
      "to": "2025-01-31"
    },
    "metrics": {
      "views": {
        "total": 1245,
        "unique": 892,
        "by_language": {
          "en": 650,
          "af": 345,
          "xh": 178,
          "ny": 72
        }
      },
      "downloads": {
        "total": 156,
        "by_type": {
          "pdf": 89,
          "powerpoint": 45,
          "audio": 22
        }
      },
      "engagement": {
        "average_read_time_seconds": 420,
        "completion_rate": 0.75
      }
    },
    "trend": {
      "views": "+12.5%",
      "downloads": "+8.3%"
    }
  }
}
```

#### Get Dashboard Analytics
```http
GET /analytics/dashboard
Authorization: Bearer {token}
```

**Query Parameters:**
- `period` (string): week|month|year

**Response:**
```json
{
  "overview": {
    "total_sermons": 156,
    "total_views": 45678,
    "total_downloads": 3456,
    "active_series": 3,
    "languages_used": ["en", "af", "xh", "ny"]
  },
  "popular_sermons": [...],
  "trending_topics": [...],
  "engagement_metrics": {...},
  "ai_usage": {
    "total_conversations": 234,
    "total_tokens_used": 456789,
    "estimated_cost": 45.67
  }
}
```

---

### Templates

#### List Templates
```http
GET /templates
```

**Query Parameters:**
- `type` (string): sermon|powerpoint
- `is_public` (boolean): true|false

#### Get Template
```http
GET /templates/{id}
```

#### Create Template
```http
POST /templates
Authorization: Bearer {token}
```

**Request:**
```json
{
  "name": "Sunday Morning Service",
  "description": "Standard Sunday morning sermon template",
  "structure": {
    "sections": [
      {"type": "introduction", "duration": 5},
      {"type": "scripture_reading", "duration": 3},
      {"type": "main_points", "count": 3, "duration": 20},
      {"type": "application", "duration": 5},
      {"type": "conclusion", "duration": 2}
    ]
  },
  "is_public": true
}
```

---

### Transcription

#### Transcribe Media
```http
POST /transcription/create
Authorization: Bearer {token}
```

**Request:**
```json
{
  "media_file_id": 301,
  "source_language": "en",
  "target_languages": ["af", "xh", "ny"]
}
```

**Response:**
```json
{
  "job": {
    "id": "job_abc123",
    "status": "processing",
    "progress": 0,
    "estimated_completion": "2025-01-15T11:00:00Z"
  }
}
```

#### Check Transcription Status
```http
GET /transcription/status/{job_id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "job": {
    "id": "job_abc123",
    "status": "completed",
    "progress": 100,
    "results": {
      "en": {
        "text": "Full transcription text...",
        "confidence": 0.95
      },
      "af": {
        "text": "Volledige transkripsie teks...",
        "confidence": 0.92
      }
    }
  }
}
```

---

## 5. Error Handling

### HTTP Status Codes
- `200 OK` - Successful request
- `201 Created` - Resource created successfully
- `204 No Content` - Successful request with no response body
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `429 Too Many Requests` - Rate limit exceeded
- `500 Internal Server Error` - Server error

### Error Response Format
```json
{
  "message": "Human-readable error message",
  "error_code": "SPECIFIC_ERROR_CODE",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  },
  "debug": {
    "exception": "Exception class name",
    "trace": ["Stack trace (development only)"]
  }
}
```

### Common Error Codes
- `AUTH_INVALID_CREDENTIALS` - Invalid login credentials
- `AUTH_TOKEN_EXPIRED` - Authentication token has expired
- `AUTH_INSUFFICIENT_PERMISSIONS` - User lacks required permissions
- `VALIDATION_FAILED` - Request validation failed
- `RESOURCE_NOT_FOUND` - Requested resource doesn't exist
- `RATE_LIMIT_EXCEEDED` - Too many requests
- `AI_PROVIDER_ERROR` - AI service error
- `BIBLE_API_ERROR` - Bible API service error
- `MEDIA_UPLOAD_FAILED` - File upload failed
- `SOCIAL_POST_FAILED` - Social media posting failed

---

## 6. Rate Limiting

### Default Limits
- **Authenticated requests**: 1000 per hour
- **AI requests**: 100 per hour
- **Media generation**: 50 per hour
- **Bible API**: 500 per hour
- **Social media posts**: 100 per day

### Rate Limit Headers
```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 995
X-RateLimit-Reset: 1640995200
```

### Rate Limit Exceeded Response
```json
{
  "message": "Too many requests",
  "error_code": "RATE_LIMIT_EXCEEDED",
  "retry_after": 3600
}
```

---

## WebSocket Events (Real-time Features)

### Connection
```javascript
// Connect to WebSocket server
const socket = new WebSocket('wss://api.pma-church.com/ws');

// Authenticate
socket.send(JSON.stringify({
  event: 'auth',
  token: 'Bearer {token}'
}));
```

### Events

#### AI Streaming
```javascript
// Subscribe to AI conversation
socket.send(JSON.stringify({
  event: 'subscribe',
  channel: 'ai.conversation.{conversation_id}'
}));

// Receive streaming messages
socket.onmessage = (event) => {
  const data = JSON.parse(event.data);
  if (data.event === 'ai.message.chunk') {
    // Handle streaming AI response
    console.log(data.chunk);
  }
};
```

#### Transcription Progress
```javascript
// Subscribe to transcription job
socket.send(JSON.stringify({
  event: 'subscribe',
  channel: 'transcription.job.{job_id}'
}));

// Receive progress updates
socket.onmessage = (event) => {
  const data = JSON.parse(event.data);
  if (data.event === 'transcription.progress') {
    console.log(`Progress: ${data.progress}%`);
  }
};
```

---

This API specification provides a complete contract between the Laravel backend and Nuxt frontend, ensuring both development teams have a clear understanding of the available endpoints, request/response formats, and error handling.