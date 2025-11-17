<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Slide Dimensions
    |--------------------------------------------------------------------------
    |
    | Standard presentation dimensions (16:9 aspect ratio)
    |
    */
    'dimensions' => [
        'width' => 1920,
        'height' => 1080,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme to use when generating slides
    |
    */
    'default_theme' => 'professional-blue',

    /*
    |--------------------------------------------------------------------------
    | Theme Presets
    |--------------------------------------------------------------------------
    |
    | Predefined color schemes and styling for slides
    |
    */
    'themes' => [
        'professional-blue' => [
            'name' => 'Professional Blue',
            'primary' => '#1e3a8a',
            'secondary' => '#3b82f6',
            'accent' => '#60a5fa',
            'text' => '#ffffff',
            'text_dark' => '#1f2937',
            'background' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
            'font_family' => "'Inter', sans-serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '32px',
        ],

        'warm-earth' => [
            'name' => 'Warm Earth',
            'primary' => '#92400e',
            'secondary' => '#d97706',
            'accent' => '#fbbf24',
            'text' => '#ffffff',
            'text_dark' => '#1f2937',
            'background' => 'linear-gradient(135deg, #92400e 0%, #d97706 100%)',
            'font_family' => "'Inter', sans-serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '32px',
        ],

        'clean-white' => [
            'name' => 'Clean White',
            'primary' => '#1f2937',
            'secondary' => '#4b5563',
            'accent' => '#22c55e',
            'text' => '#1f2937',
            'text_dark' => '#1f2937',
            'background' => '#ffffff',
            'font_family' => "'Inter', sans-serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '32px',
        ],

        'adventist-green' => [
            'name' => 'Adventist Green',
            'primary' => '#065f46',
            'secondary' => '#059669',
            'accent' => '#10b981',
            'text' => '#ffffff',
            'text_dark' => '#1f2937',
            'background' => 'linear-gradient(135deg, #065f46 0%, #059669 100%)',
            'font_family' => "'Inter', sans-serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '32px',
        ],

        'royal-purple' => [
            'name' => 'Royal Purple',
            'primary' => '#581c87',
            'secondary' => '#7c3aed',
            'accent' => '#a78bfa',
            'text' => '#ffffff',
            'text_dark' => '#1f2937',
            'background' => 'linear-gradient(135deg, #581c87 0%, #7c3aed 100%)',
            'font_family' => "'Inter', sans-serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '32px',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slide Types
    |--------------------------------------------------------------------------
    |
    | Available slide types and their characteristics
    |
    */
    'slide_types' => [
        'title' => [
            'name' => 'Title Slide',
            'description' => 'Opening slide with sermon title and subtitle',
            'template' => 'title-slide',
        ],
        'outline' => [
            'name' => 'Outline',
            'description' => 'Overview of main points',
            'template' => 'outline-slide',
        ],
        'content' => [
            'name' => 'Content',
            'description' => 'Main content with title and body text',
            'template' => 'content-slide',
        ],
        'scripture' => [
            'name' => 'Scripture',
            'description' => 'Bible verse display',
            'template' => 'scripture-slide',
        ],
        'two-column' => [
            'name' => 'Two Column',
            'description' => 'Split content layout',
            'template' => 'two-column-slide',
        ],
        'image' => [
            'name' => 'Image',
            'description' => 'Full background image with text overlay',
            'template' => 'image-slide',
        ],
        'application' => [
            'name' => 'Application',
            'description' => 'Practical application points',
            'template' => 'content-slide',
        ],
        'conclusion' => [
            'name' => 'Conclusion',
            'description' => 'Summary and call to action',
            'template' => 'conclusion-slide',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Generation Settings
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'max_slides' => 20,
        'min_slides' => 5,
        'default_slide_count' => 10,
        'planning_prompt_template' => 'Analyze this sermon and create a slide deck plan...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    */
    'images' => [
        'unsplash' => [
            'enabled' => env('UNSPLASH_ENABLED', false),
            'access_key' => env('UNSPLASH_ACCESS_KEY'),
            'search_per_slide' => 3, // Number of images to search
        ],
        'ai_generation' => [
            'enabled' => true,
            'style' => 'reverent biblical illustration',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    */
    'export' => [
        'formats' => ['pptx', 'pdf'],
        'storage_path' => 'exports/slides',
        'temp_path' => 'temp/slides',
    ],
];
