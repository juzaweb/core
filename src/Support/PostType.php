<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/31/2021
 * Time: 9:48 PM
 */

namespace Juzaweb\Core\Support;

use Illuminate\Support\Arr;

class PostType
{
    /**
     * Get post type setting
     *
     * @param string|null $postType
     * @return \Illuminate\Support\Collection
     * */
    public static function getPostTypes($postType = null)
    {
        if ($postType) {
            return Arr::get(apply_filters('juzaweb.post_types', []), $postType);
        }

        return apply_filters('juzaweb.post_types', []);
    }

    public static function getTaxonomies($postType = null)
    {
        $taxonomies = collect(apply_filters('juzaweb.taxonomies', []));
        if (empty($taxonomies)) {
            return $taxonomies;
        }

        $taxonomies = collect($taxonomies[$postType] ?? []);
        $taxonomies = $taxonomies ?
            $taxonomies->sortBy('menu_position')
            : [];

        return $taxonomies;
    }

    /**
     * Sync taxonomies post type
     *
     * @param string $postType
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     * @return void
     *
     * @throws \Exception
     */
    public static function syncTaxonomies($postType, $model, array $attributes)
    {
        $taxonomies = PostType::getTaxonomies($postType);
        foreach ($taxonomies as $taxonomy) {
            if (method_exists($model, 'taxonomies')) {
                $data = Arr::get($attributes, $taxonomy->get('taxonomy'), []);
                $detachIds = $model->taxonomies()
                    ->where('taxonomy', '=', $taxonomy->get('taxonomy'))
                    ->whereNotIn('id', $data)
                    ->pluck('id')
                    ->toArray();

                $model->taxonomies()->detach($detachIds);
                $model->taxonomies()
                    ->syncWithoutDetaching(combine_pivot($data, [
                        'term_type' => $postType
                    ]), ['term_type' => $postType]);
            }
        }
    }
}