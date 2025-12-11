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

        'biblical-classic' => [
            'name' => 'Biblical Classic',
            'primary' => '#8B7355',
            'secondary' => '#C4A574',
            'accent' => '#D4AF37',
            'text' => '#4A3C2A',
            'text_dark' => '#2C241A',
            'background' => 'linear-gradient(135deg, #F5F0E8 0%, #EDE4D3 50%, #E8DCC8 100%)',
            'font_family' => "'Playfair Display', 'Georgia', serif",
            'title_font_size' => '72px',
            'subtitle_font_size' => '36px',
            'body_font_size' => '28px',
            'use_ai_images' => true,
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
            'search_per_slide' => 3,
        ],
        'ai_generation' => [
            'enabled' => env('SLIDE_AI_IMAGES_ENABLED', true),
            'provider' => 'kie',
            'model' => 'nanobanana',
            'size' => '1792x1024',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Biblical Slide Image Style (Kimi-inspired)
    |--------------------------------------------------------------------------
    |
    | Visual style configuration for AI-generated slide images.
    | These settings create consistent, elegant biblical imagery.
    |
    */
    'biblical_image_style' => [
        'base_style' => 'Elegant theological illustration in warm sepia and gold tones. Soft cream background (#F5F0E8). Classical Christian art style with sacred geometry elements. Soft glowing light effects. Professional presentation quality. Clean composition with ample negative space.',

        'visual_elements' => [
            'dove' => 'Glowing white dove with soft radiant light, symbol of Holy Spirit, ethereal and graceful',
            'bible' => 'Open leather-bound Bible with golden glow emanating from pages, ancient scriptures visible',
            'scrolls' => 'Ancient Hebrew parchment scrolls with delicate calligraphy, aged paper texture',
            'cross' => 'Elegant wooden cross with golden light rays, subtle glow effect',
            'sacred_geometry' => 'Subtle geometric patterns in gold, circles and connecting lines, spiritual symbolism',
            'light_rays' => 'Soft golden light rays emanating from center, divine illumination effect',
            'olive_branch' => 'Delicate olive branch with leaves, symbol of peace and blessing',
            'menorah' => 'Seven-branched golden menorah with soft candlelight glow',
            'tablets' => 'Stone tablets of the law with Hebrew inscriptions, weathered texture',
            'crown' => 'Golden crown with subtle glow, royal and majestic appearance',
        ],

        'slide_type_prompts' => [
            'title' => 'Central composition with [TOPIC] symbolism. Elegant title slide design with sacred geometry frame. Soft cream background with golden accents. Open Bible with glowing dove above. Hebrew scrolls on sides. Professional presentation aesthetic.',

            'scripture' => 'Scripture verse display composition. Open ancient Bible with glowing pages center. Soft radiant light emanating upward. Delicate olive branches framing. Cream parchment background. Reverent and sacred atmosphere.',

            'content' => 'Educational theological illustration. [TOPIC] visual elements arranged elegantly. Clean layout with sacred geometry border. Soft sepia tones with golden highlights. Professional presentation slide.',

            'diagram' => 'Theological concept diagram illustration. [TOPIC] relationship visualization. Connected circles with golden lines. Sacred geometry patterns. Labels in elegant serif font. Cream background with soft shadows.',

            'two_column' => 'Split composition comparing [TOPIC]. Left and right visual elements balanced. Decorative divider with olive motif. Warm sepia color palette. Professional comparison layout.',

            'quote' => 'Elegant quote presentation design. Central text area with decorative frame. Dove and Bible motif in corner. Soft cream background. Golden accent borders. Sophisticated typography layout.',

            'conclusion' => 'Call to action slide design. Magnifying glass over open Bible. Discovery and truth seeking imagery. Golden light illumination. Sacred geometry accents. Inspiring and inviting composition.',
        ],

        'color_palette' => [
            'cream' => '#F5F0E8',
            'parchment' => '#EDE4D3',
            'sepia' => '#8B7355',
            'gold' => '#D4AF37',
            'bronze' => '#C4A574',
            'deep_brown' => '#4A3C2A',
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
