<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
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

