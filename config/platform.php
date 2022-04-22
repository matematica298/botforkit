<?php

return [
    'domain' => env('DASHBOARD_DOMAIN', null),

    'prefix' => env('DASHBOARD_PREFIX', '/admin'),

    'middleware' => [
        'public'  => ['web'],
        'private' => ['web', 'platform'],
    ],

    'auth'  => true,

    'index' => 'platform.main',

    'resource' => [
        'stylesheets' => [],
        'scripts'     => [],
    ],

    'template' => [
        'header' => 'brand.header',
        'footer' => 'brand.footer',
    ],

    'attachment' => [
        'disk'      => 'public',
        'generator' => \Orchid\Attachment\Engines\Generator::class,
    ],

    'icons' => [],
];
