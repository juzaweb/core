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
            /** Mime types can be uploaded */
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
            ],

            /** Max size of file in bytes */
            'max_size' => 1024 * 1024 * 15, // 15 MB
        ],
    ],

    /**
     * On/Off Optimize uploaded images
     *
     * @see https://larabiz.com/docs/v1/larabiz/the-basics/media#content-image-optimizer
     */
    'image-optimize' => env('LB_MEDIA_IMAGE_OPTIMIZE', false),

    /**
     * Mime types for images
     */
    'image_mime_types' => [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/svg+xml',
        'image/svg',
        'image/webp',
    ],
];
