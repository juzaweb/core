<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

return [
    /**
     * Admin panel prefix
     */
    'admin_prefix' => env('JW_ADMIN_PREFIX', 'admin-cp'),

    /**
     * Default Authentication middleware
     */
    'auth_middleware' => ['auth'],

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
];

