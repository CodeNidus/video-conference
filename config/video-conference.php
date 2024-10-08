<?php

return [
    'path' => 'rooms',
    'prefix' => 'videoconference',
    'app_id' => env('VIDUS_APP_ID', '1'),
    'app_secret' => env('VIDUS_APP_SECRET', 'top-secret'),

    'demo_user' => false,

    // user username field name
    'user' => [
        'username_field' => 'email'
    ],

    'routes' => [
        'web' => [
            'middleware' => [],
        ],
        'api' => [
            'middleware' => ['auth:sanctum'],
        ],
    ]
];
