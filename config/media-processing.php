<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Processing API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the remote media processing API that handles
    | Whisper transcription and Remotion video rendering.
    |
    */

    'api_url' => env('MEDIA_API_URL', 'http://localhost'),
    'api_key' => env('MEDIA_API_KEY', ''),

    'timeout' => [
        'transcription' => env('MEDIA_TRANSCRIPTION_TIMEOUT', 300),
        'render' => env('MEDIA_RENDER_TIMEOUT', 600),
    ],

    'callback_url' => env('MEDIA_CALLBACK_URL', env('APP_URL') . '/api/media/callback'),
];
