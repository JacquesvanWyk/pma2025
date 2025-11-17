# Database Schema Design

## Tables Structure

### 1. users
- id (primary key)
- name
- email (unique)
- password
- role (admin, editor, viewer)
- created_at
- updated_at

### 2. studies
- id (primary key)
- category_id (foreign key)
- title
- slug (unique)
- content (text/markdown)
- excerpt
- language (en/af)
- author_id (foreign key to users)
- published_at
- created_at
- updated_at

### 3. study_categories
- id (primary key)
- name
- slug (unique)
- description
- parent_id (nullable, for subcategories)
- order
- created_at
- updated_at

### 4. sermons
- id (primary key)
- title
- slug (unique)
- speaker
- description
- youtube_url
- audio_file_path
- date_preached
- series_id (nullable, foreign key)
- language (en/af)
- views_count
- published_at
- created_at
- updated_at

### 5. sermon_series
- id (primary key)
- name
- description
- created_at
- updated_at

### 6. resources
- id (primary key)
- type (tract, ebook, note, dvd)
- title
- description
- file_path
- file_size
- download_count
- language (en/af)
- author
- published_at
- created_at
- updated_at

### 7. donations
- id (primary key)
- donor_name
- donor_email
- amount
- type (one-time, monthly-pledge)
- payment_method
- transaction_id
- status (pending, completed, failed)
- notes
- created_at
- updated_at

### 8. pledges
- id (primary key)
- donor_name
- donor_email
- monthly_amount
- start_date
- end_date (nullable)
- status (active, paused, cancelled)
- created_at
- updated_at

### 9. newsletter_subscribers
- id (primary key)
- email (unique)
- name
- language_preference (en/af)
- subscribed_at
- unsubscribed_at (nullable)
- created_at
- updated_at

### 10. contact_submissions
- id (primary key)
- name
- email
- phone
- subject
- message
- status (new, read, responded)
- responded_by (nullable, foreign key to users)
- response (nullable)
- created_at
- updated_at

### 11. pages
- id (primary key)
- title
- slug (unique)
- content
- meta_description
- language (en/af)
- published_at
- created_at
- updated_at

### 12. media
- id (primary key)
- filename
- original_filename
- mime_type
- file_size
- file_path
- alt_text
- uploaded_by (foreign key to users)
- created_at
- updated_at

### 13. settings
- id (primary key)
- key (unique)
- value (json)
- created_at
- updated_at

### 14. activity_logs
- id (primary key)
- user_id (foreign key)
- action
- model_type
- model_id
- changes (json)
- ip_address
- user_agent
- created_at