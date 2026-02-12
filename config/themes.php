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

    'upload_enabled' => env('JW_THEME_UPLOAD_ENABLED', true),

    'path' => base_path('themes'),

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
