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
            'index' => 'views/index.twig',
            'header' => 'views/header.twig',
            'footer' => 'views/footer.twig',
            'search' => 'views/search.twig',
            'single' => 'views/template-parts/single.twig',
            'page' => 'views/template-parts/single-page.twig',
            'taxonomy' => 'views/template-parts/taxonomy.twig',
            'profile' => 'views/profile/index.twig',
            'content' => 'views/template-parts/content.twig',
            'home' => 'views/templates/home.twig',
            'register_json' => 'register.json',
        ],
        'folders' => [
            'views' => 'views',
            'views/auth' => 'views/auth',
            'views/profile' => 'views/profile',
            'views/template-parts' => 'views/template-parts',
            'views/templates' => 'views/templates'
        ],
    ],
];
