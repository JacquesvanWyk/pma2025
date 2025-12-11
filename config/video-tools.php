<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Whisper Configuration
    |--------------------------------------------------------------------------
    */
    'whisper' => [
        'python_path' => env('WHISPER_PYTHON_PATH', '/Users/jacquesvanwyk/whisper-env/bin/python3'),
        'model' => env('WHISPER_MODEL', 'base'),
        'language' => env('WHISPER_LANGUAGE', 'en'),
        'temp_dir' => storage_path('app/temp/whisper'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Remotion Configuration
    |--------------------------------------------------------------------------
    */
    'remotion' => [
        'project_path' => env('REMOTION_PROJECT_PATH', '/Users/jacquesvanwyk/remotion-renderer'),
        'node_path' => env('REMOTION_NODE_PATH', '/usr/local/bin/node'),
        'temp_dir' => storage_path('app/temp/remotion'),
        'output_dir' => storage_path('app/public/videos'),
        'concurrency' => env('REMOTION_CONCURRENCY', 2),
    ],

    /*
    |--------------------------------------------------------------------------
    | Output Settings
    |--------------------------------------------------------------------------
    */
    'output' => [
        'disk' => env('VIDEO_OUTPUT_DISK', 'public'),
        'video_dir' => 'videos',
        'thumbnail_dir' => 'thumbnails',
    ],
];
