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
        'path' => base_path('vendor/juzaweb/core/stubs/themes'),

        'files' => [
            'index' => 'src/resources/views/index.blade.php',
            'layout' => 'src/resources/views/layouts/main.blade.php',
            'search' => 'src/resources/views/search.blade.php',
            'mix' => 'assets/webpack.mix.js',
            // 'resources/views/profile/index' => 'src/resources/views/profile/index.blade.php',
            // 'resources/views/profile/notification' => 'src/resources/views/profile/notification.blade.php',
            // 'resources/views/profile/components/sidebar' => 'src/resources/views/profile/components/sidebar.blade.php',
            'ThemeServiceProvider' => 'src/Providers/ThemeServiceProvider.php',
            'RouteServiceProvider' => 'src/Providers/RouteServiceProvider.php',
            'controllers/HomeController' => 'src/Http/Controllers/HomeController.php',
            'controllers/ProfileController' => 'src/Http/Controllers/ProfileController.php',
            'lang' => 'src/resources/lang/en/translation.php',
            'routes/admin' => 'src/routes/admin.php',
            'routes/web' => 'src/routes/web.php',
        ],
        'folders' => [
            'assets/js' => 'assets/js',
            'assets/css' => 'assets/css',
            'lang' => 'src/resources/lang/en',
            'views' => 'src/resources/views/layouts',
            'Http/Controllers' => 'src/Http/Controllers',
            // 'Http/Middleware' => 'src/Http/Middleware',
            // 'Http/Requests' => 'src/Http/Requests',
            'Providers' => 'src/Providers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Activator
    |--------------------------------------------------------------------------
    |
    | You can define the activator for themes here.
    | The setting activator will store the active theme in settings table.
    |
    */
    'activators' => [
        'setting' => [
            'class' => \Juzaweb\Modules\Core\Themes\Activators\SettingActivator::class,
            'key' => 'theme',
        ],
    ],

    'activator' => 'setting',
];
