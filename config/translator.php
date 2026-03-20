<?php

use Juzaweb\Modules\Core\Translations\Translators\GoogleTranslator;

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

return [

    'enable' => env('TRANSLATOR_ENABLE', false),

    /**
     * Driver default to use for translation services.
     */
    'driver' => env('TRANSLATOR_DRIVER', 'google'),

    'queue' => env('TRANSLATOR_QUEUE', 'default'),

    /**
     * Available translation drivers.
     */
    'drivers' => [
        'google' => [
            'class' => GoogleTranslator::class,
        ],
    ],
];
