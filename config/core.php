<?php

use Laravel\Socialite\Two\BitbucketProvider;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GitlabProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\LinkedInProvider;
use Laravel\Socialite\Two\TwitterProvider;

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
    /**
     * Admin panel prefix
     */
    'admin_prefix' => env('JW_ADMIN_PREFIX', 'admin'),

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
            'google' => GoogleProvider::class,
            'facebook' => FacebookProvider::class,
            'linkedin' => LinkedInProvider::class,
            'twitter' => TwitterProvider::class,
            'github' => GithubProvider::class,
            'gitlab' => GitlabProvider::class,
            'bitbucket' => BitbucketProvider::class,
        ],
    ],

    'optimize' => [
        'minify_views' => (bool) env('MINIFY_VIEWS', true),

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
        ...(env('CSP_SCRIPT_SRC') ? explode(',', env('CSP_SCRIPT_SRC')) : []),
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
