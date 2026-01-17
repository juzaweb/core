<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

return [

    /**
     * Driver default to use for translation services.
     */
    'driver' => env('TRANSLATOR_DRIVER', 'ex-google'),

    'queue' => env('TRANSLATOR_QUEUE', 'default'),

    /**
     * Available translation drivers.
     */
    'drivers' => [
        'google' => [
            'class' => \Juzaweb\Modules\Core\Translations\Translators\GoogleTranslator::class,
        ],
    ],
];
