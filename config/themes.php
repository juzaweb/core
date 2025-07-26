<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

return [
    'path' => base_path('themes'),

    'stubs' => [
        'path' => base_path('vendor/juzaweb/core/stubs/themes'),

        'files' => [
            'index' => 'resources/views/index.blade.php',
            'layout' => 'resources/views/layouts/main.blade.php',
            'search' => 'resources/views/search.blade.php',
            'profile' => 'resources/views/profile/index.blade.php',
        ],
        'folders' => [
            'assets/js' => 'resources/assets/js',
            'assets/css' => 'resources/assets/css',
            'assets/public' => 'resources/assets/public',
            'lang' => 'resources/lang/en',
            'views' => 'resources/views/layouts',
            'Http/Controllers' => 'Http/Controllers',
            'Http/Middleware' => 'Http/Middleware',
            'Http/Requests' => 'Http/Requests',
            'Providers' => 'Providers',
            'Models' => 'Models',
            'migrations' => 'Database/migrations',
            'seeds' => 'Database/Seeds',
            'config' => 'config',
            'routes' => 'routes',
        ],
    ],
];
