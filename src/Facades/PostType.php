<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getPostTypes($postType = null)
 * @method static \Illuminate\Support\Collection getTaxonomies($postType = null)
 * @method static void syncTaxonomies($postType, $model, array $attributes)
 *
 * @see \Juzaweb\Core\Helpers\PostType
 */
class PostType extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'juzaweb.post_type';
    }
}