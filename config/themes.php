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
            'resources/assets/css/profile.css' => 'resources/assets/css/profile.css',
            'resources/assets/public/css/profile.min' => 'resources/assets/public/css/profile.min.css',
            'resources/assets/public/mix-manifest' => 'resources/assets/public/mix-manifest.json',
            'resources/views/profile/index' => 'resources/views/profile/index.blade.php',
            'resources/views/profile/notification' => 'resources/views/profile/notification.blade.php',
            'resources/views/profile/components/sidebar' => 'resources/views/profile/components/sidebar.blade.php',
            'ThemeServiceProvider' => 'Providers/ThemeServiceProvider.php',
            'RouteServiceProvider' => 'Providers/RouteServiceProvider.php',
            'controllers/HomeController' => 'Http/Controllers/HomeController.php',
            'controllers/ProfileController' => 'Http/Controllers/ProfileController.php',
            'lang' => 'resources/lang/en/translation.php',
            'routes/admin' => 'routes/admin.php',
            'routes/web' => 'routes/web.php',
        ],
        'folders' => [
            'assets/js' => 'resources/assets/js',
            'assets/css' => 'resources/assets/css',
            'assets/plugins' => 'resources/assets/plugins',
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
