<?php

return [

    /*
    |--------------------------------------------------------------------------
    | KIE.ai API Configuration
    |--------------------------------------------------------------------------
    |
    | Unified AI API provider for images, video, and music generation.
    | https://kie.ai - 1 credit = $0.005
    |
    */

    'api_key' => env('KIE_API_KEY'),

    'base_url' => env('KIE_BASE_URL', 'https://api.kie.ai'),

    'callback_url' => env('KIE_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'image_model' => env('KIE_IMAGE_MODEL', 'seedream'),
        'video_model' => env('KIE_VIDEO_MODEL', 'veo3.1_fast'),
        'music_model' => env('KIE_MUSIC_MODEL', 'V4_5'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Music Generation (Suno API)
    |--------------------------------------------------------------------------
    */

    'music' => [
        'models' => [
            'V3_5' => [
                'name' => 'Suno V3.5',
                'description' => 'Better song structure',
                'max_duration' => 240, // 4 minutes
            ],
            'V4' => [
                'name' => 'Suno V4',
                'description' => 'Improved vocals',
                'max_duration' => 240,
            ],
            'V4_5' => [
                'name' => 'Suno V4.5',
                'description' => 'Smart prompts, faster generation',
                'max_duration' => 480, // 8 minutes
            ],
            'V4_5PLUS' => [
                'name' => 'Suno V4.5 Plus',
                'description' => 'Richer sound quality',
                'max_duration' => 480,
            ],
            'V4_5ALL' => [
                'name' => 'Suno V4.5 All',
                'description' => 'Smart and fast combined',
                'max_duration' => 480,
            ],
            'V5' => [
                'name' => 'Suno V5',
                'description' => 'Superior musicality, fastest generation',
                'max_duration' => 480,
            ],
        ],

        'styles' => [
            'worship' => 'Worship, spiritual, reverent, church music, praise',
            'hymn' => 'Traditional hymn, classic church, organ, choir',
            'contemporary' => 'Contemporary Christian, modern worship, uplifting',
            'gospel' => 'Gospel, soulful, choir, uplifting, inspirational',
            'instrumental' => 'Instrumental, peaceful, meditative, background',
            'acoustic' => 'Acoustic, simple, intimate, guitar-based',
            'orchestral' => 'Orchestral, cinematic, epic, strings',
            'children' => 'Children\'s music, playful, educational, fun',
        ],

        'prompt_max_length' => [
            'simple' => 500,
            'custom_v3_v4' => 3000,
            'custom_v4_5' => 5000,
        ],

        'title_max_length' => 80,
        'style_max_length' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Video Generation (Veo 3.1 API)
    |--------------------------------------------------------------------------
    */

    'video' => [
        'models' => [
            'veo3.1_fast' => [
                'name' => 'Veo 3.1 Fast',
                'description' => 'Quick generation, good quality',
                'credits' => 80,
                'cost_usd' => 0.40,
                'duration' => 8,
            ],
            'veo3.1_quality' => [
                'name' => 'Veo 3.1 Quality',
                'description' => 'High quality, slower generation',
                'credits' => 400,
                'cost_usd' => 2.00,
                'duration' => 8,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Generation
    |--------------------------------------------------------------------------
    */

    'image' => [
        'models' => [
            'seedream' => [
                'name' => 'Seedream 4.0',
                'description' => 'Cheapest option, good quality',
                'credits' => 3.5,
                'cost_usd' => 0.0175,
            ],
            'nanobanana' => [
                'name' => 'NanoBanana',
                'description' => 'Gemini 2.5 Flash Image',
                'credits' => 4,
                'cost_usd' => 0.02,
            ],
            '4o' => [
                'name' => '4o Image',
                'description' => 'GPT-4o vision powered',
                'credits' => 'variable',
                'cost_usd' => 'variable',
            ],
            'flux_kontext' => [
                'name' => 'Flux Kontext',
                'description' => 'Context-aware generation and editing',
                'credits' => 'variable',
                'cost_usd' => 'variable',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    */

    'storage' => [
        'disk' => 'public',
        'paths' => [
            'images' => 'generated/images',
            'videos' => 'generated/videos',
            'music' => 'generated/music',
        ],
        'remote_expiry_days' => 14,
    ],

    /*
    |--------------------------------------------------------------------------
    | Polling Configuration
    |--------------------------------------------------------------------------
    */

    'polling' => [
        'interval_seconds' => 5,
        'max_attempts' => 120, // 10 minutes max
        'timeout_seconds' => 600,
    ],

];
