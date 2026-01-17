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
     * Admin panel prefix
     */
    'admin_prefix' => env('JW_ADMIN_PREFIX', 'admin'),

    /**
     * Default Authentication middleware
     */
    'auth_middleware' => ['auth'],

    'query_cache' => [
        /**
         * Enable query cache
         *
         * Default: true
         */
        'enable' => env('JW_QUERY_CACHE', true),

        /**
         * Query cache driver
         *
         * Default: file
         */
        'driver' => env('JW_QUERY_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),

        /**
         * Query cache lifetime
         *
         * Default: 3600s (1 hour)
         */
        'lifetime' => env('JW_QUERY_CACHE_LIFETIME', 3600),
    ],

    /*
     * Social Login Providers
     */
    'social_login' => [
        'providers' => [
            'google' => \Laravel\Socialite\Two\GoogleProvider::class,
            'facebook' => \Laravel\Socialite\Two\FacebookProvider::class,
            'linkedin' => \Laravel\Socialite\Two\LinkedInProvider::class,
            'twitter' => \Laravel\Socialite\Two\TwitterProvider::class,
            'github' => \Laravel\Socialite\Two\GithubProvider::class,
            'gitlab' => \Laravel\Socialite\Two\GitlabProvider::class,
            'bitbucket' => \Laravel\Socialite\Two\BitbucketProvider::class,
        ]
    ],

    'optimize' => [
        'minify_views' => env('MINIFY_VIEWS', true),

        /**
         * Deny iframe to website
         *
         * Default: true
         */
        'deny_iframe' => (bool) env('DENY_IFRAME', true),
    ],

    /*
     * Content Security Policy - Script Src
     */
    'csp_script_src' => [
        'https://cdnjs.cloudflare.com',
        'https://www.googletagmanager.com',
        'https://www.google-analytics.com',
        'https://cdn.juzaweb.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Languages
    |--------------------------------------------------------------------------
    |
    | This array contains the list of available languages for the application.
    | Each language is defined with its ISO 639-1 code as the key and the
    | language name as the value.
    |
    */

    'languages' => [
        'zh',
        'cs',
        'en',
        'fr',
        'de',
        'it',
        'ja',
        'ko',
        'pl',
        'pt',
        'ru',
        'es',
        'tr',
        'vi',
    ],
];
