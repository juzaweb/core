<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

/**
 * Trait HasDescription
 *
 * Automatically generates SEO-friendly description from content.
 * The description is generated using seo_string() helper which:
 * - Strips HTML tags
 * - Limits to 160 characters (SEO recommended length)
 * - Removes zero-width spaces and normalizes whitespace
 *
 * Usage: Add this trait to any model with a 'content' field
 *
 * @package Juzaweb\Modules\Admin\Traits
 */
trait HasDescription
{
    /**
     * Boot the HasDescription trait.
     * Sets up model event listeners to auto-generate description.
     *
     * @return void
     */
    protected static function bootHasDescription(): void
    {
        static::saving(function ($model) {
            // Auto-generate description if content exists and description is empty or should be regenerated
            if (!empty($model->content)) {
                $model->description = seo_string($model->content, 160);
            }
        });
    }
}
