<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

return [
    'enabled' => env('JW_THEME_ENABLED', true),

    'path' => base_path('themes'),

    'stubs' => [
        'path' => base_path('vendor/juzaweb/core/stubs/themes'),

        'files' => [
            'index' => 'views/index.blade.php',
            'layout' => 'views/layouts/main.blade.php',
            'search' => 'views/search.blade.php',
            'profile' => 'views/profile/index.blade.php',
            'register_json' => 'register.json',
        ],
        'folders' => [
            'assets/js' => 'assets/js',
            'assets/css' => 'assets/css',
            'views' => 'views',
            'views/auth' => 'views/auth',
            'views/profile' => 'views/profile',
            'src' => 'src',
            'src/Controllers' => 'src/Controllers',
            'src/routes' => 'src/routes',
        ],
    ],
];
