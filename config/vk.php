<?php

return [
    'token' => env('VK_API_TOKEN', ''),
    'version' => env('VK_API_VERSION', ''),
    'confirmation' => env('VK_API_CONFIRMATION', ''),

    'halloween' => [
        'album' => env('VK_HALLOWEEN_ALBUM', ''),
        'group' => env('VK_HALLOWEEN_GROUP', ''),
    ],

    'first' => [
        'token' => env('VK_FIRST_TOKEN', ''),
        'version' => env('VK_FIRST_VERSION', ''),
        'confirmation' => env('VK_FIRST_CONFIRMATION', ''),
    ],
];
