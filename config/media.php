<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

return [
    'disks' => [
        'public' => [
            /**
             * Mime types can be uploaded
             */
            'mime_types' => [
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/svg+xml',
                'image/svg',
                'video/quicktime',
                'video/webm',
                'video/mp4',
                'audio/mp3',
                'audio/ogg',
                'image/webp',
                'audio/mp3',
                'audio/mpeg',
                'audio/ogg',
                'application/pdf',
                'text/plain',
                'application/zip',
            ],

            /**
             * Max size of file in bytes
             */
            'max_size' => 1024 * 1024 * 15, // 15 MB
        ],
    ],

    /**
     * On/Off Upload from URL
     *
     * Default: true
     */
    'upload_from_url' => env('JW_MEDIA_UPLOAD_FROM_URL', true),

    /**
     * On/Off Optimize uploaded images
     *
     * Default: false
     */
    'image-optimize' => env('JW_MEDIA_IMAGE_OPTIMIZE', false),

    /**
     * On/Off automatic upload to cloud storage
     *
     * Default: false
     */
    'cloud_upload_enabled' => env('CLOUD_UPLOAD_ENABLED', false),

    /**
     * Cloud disk name to upload files
     *
     * Default: cloud
     */
    'cloud_disk' => env('CLOUD_DISK', 'cloud'),

    /**
     * Mime types each type for filter
     */
    'types' => [
        /**
         * Mime types for images
         */
        'image' => [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/svg+xml',
            'image/svg',
            'image/webp',
        ],

        /**
         * Mime types for videos
         */
        'video' => [
            'video/mp4',
            'video/ogg',
            'video/webm',
        ],

        /**
         * Mime types for audio
         */
        'audio' => [
            'audio/mp3',
            'audio/mpeg',
            'audio/ogg',
        ],

        /**
         * Mime types for documents
         */
        'document' => [
            'application/pdf',
            'text/plain',
        ],

        /**
         * Mime types for zip
         */
        'zip' => [
            'application/zip',
        ],

        'file' => [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/svg+xml',
            'image/svg',
            'video/mp4',
            'video/ogg',
            'video/webm',
            'audio/mp3',
            'audio/mpeg',
            'audio/ogg',
            'application/pdf',
            'text/plain',
            'application/zip',
        ],
    ],

    'extensions' => [
        'image' => [
            'png',
            'jpg',
            'jpeg',
            'gif',
            'svg',
            'webp',
        ],

        'video' => [
            'mp4',
            'ogg',
            'webm',
        ],

        'audio' => [
            'mp3',
            'mpeg',
            'ogg',
        ],

        'document' => [
            'pdf',
            'txt',
        ],

        'zip' => [
            'zip',
        ],

        'file' => [
            'png',
            'jpg',
            'jpeg',
            'gif',
            'svg',
            'mp4',
            'ogg',
            'webm',
            'mp3',
            'mpeg',
            'pdf',
            'txt',
            'zip',
        ],
    ],
];
