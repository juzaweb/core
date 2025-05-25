<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

return [
    'path' => base_path('themes'),

    'stubs' => [
        'path' => base_path('vendor/juzaweb/core/stubs/themes'),

        'files' => [
            'index' => 'views/index.blade.php',
            'layout' => 'views/layouts/main.blade.php',
            'search' => 'views/pages/search.blade.php',
            'profile' => 'views/pages/profile/index.blade.php',
            'register_json' => 'register.json',
        ],
        'folders' => [
            'assets/js' => 'assets/js',
            'assets/css' => 'assets/css',
            'views' => 'views',
            'views/auth' => 'views/pages/auth',
            'views/profile' => 'views/pages/profile',
            'src' => 'src',
            'src/Controllers' => 'src/Controllers',
            'src/routes' => 'src/routes',
        ],
    ],
];
