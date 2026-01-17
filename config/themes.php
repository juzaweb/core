<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

return [
    'enable' => env('JW_THEME_ENABLE', true),

    'path' => base_path('themes'),

    'stubs' => [
        'path' => resource_path('stubs/themes'),

        'files' => [
            'index' => 'resources/views/index.blade.php',
            'layout' => 'resources/views/layouts/main.blade.php',
            'search' => 'resources/views/search.blade.php',
            'mix' => 'assets/mix.js',
            // 'resources/views/profile/index' => 'resources/views/profile/index.blade.php',
            // 'resources/views/profile/notification' => 'resources/views/profile/notification.blade.php',
            // 'resources/views/profile/components/sidebar' => 'resources/views/profile/components/sidebar.blade.php',
            'ThemeServiceProvider' => 'Providers/ThemeServiceProvider.php',
            'RouteServiceProvider' => 'Providers/RouteServiceProvider.php',
            'controllers/HomeController' => 'Http/Controllers/HomeController.php',
            'controllers/ProfileController' => 'Http/Controllers/ProfileController.php',
            'lang' => 'resources/lang/en/translation.php',
            'routes/admin' => 'routes/admin.php',
            'routes/web' => 'routes/web.php',
        ],
        'folders' => [
            'assets/js' => 'assets/js',
            'assets/css' => 'assets/css',
            'lang' => 'resources/lang/en',
            'views' => 'resources/views/layouts',
            'Http/Controllers' => 'Http/Controllers',
            // 'Http/Middleware' => 'Http/Middleware',
            // 'Http/Requests' => 'Http/Requests',
            'Providers' => 'Providers',
        ],
    ],
];
