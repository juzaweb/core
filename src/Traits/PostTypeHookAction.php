<?php
/**
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait PostTypeHookAction
{
    /**
     * JUZAWEB CMS: Creates or modifies a taxonomy object.
     * @param string $taxonomy (Required) Taxonomy key, must not exceed 32 characters.
     * @param array|string $objectType
     * @param array $args (Optional) Array of arguments for registering a post type.
     * @return void
     *
     * @throws \Exception
     */
    public function registerTaxonomy($taxonomy, $objectType, $args = [])
    {
        $objectTypes = is_string($objectType) ? [$objectType] : $objectType;
        foreach ($objectTypes as $objectType) {
            $type = Str::singular($objectType);
            $opts = [
                'label' => '',
                'label_type' => ucfirst($type) .' '. $args['label'],
                'description' => '',
                'hierarchical' => false,
                'parent' => $objectType,
                'menu_slug' => $type . '.' . $taxonomy,
                'menu_position' => 20,
                'menu_icon' => 'fa fa-list',
                'show_in_menu' => true,
                'rewrite' => true,
                'supports' => [
                    'thumbnail',
                    'hierarchical'
                ],
            ];

            $iargs = $args;
            $iargs['type'] = $type;
            $iargs['post_type'] = $objectType;
            $iargs['taxonomy'] = $taxonomy;
            $iargs['singular'] = Str::singular($taxonomy);
            $iargs = new Collection(array_merge($opts, $iargs));

            add_filters('juzaweb.taxonomies', function ($items) use ($taxonomy, $objectType, $iargs) {
                $items[$objectType][$taxonomy] = $iargs;
                return $items;
            });

            $this->addAdminMenu(
                $iargs->get('label'),
                $iargs->get('menu_slug'),
                [
                    'icon' => $iargs->get('menu_icon', 'fa fa-list'),
                    'parent' => $iargs->get('parent'),
                    'position' => $iargs->get('menu_position')
                ]
            );
        }
    }

    /**
     * JUZAWEB CMS: Registers a post type.
     * @param string $key (Required) Post type key. Must not exceed 20 characters
     * @param array $args Array of arguments for registering a post type.
     *
     * @throws \Exception
     */
    public function registerPostType($key, $args = [])
    {
        if (empty($args['model'])) {
            throw new \Exception('Post type model is required. E.x: \\Juzaweb\Core\\Models\\Post.');
        }

        $args = array_merge([
            'label' => '',
            'description' => '',
            'show_in_menu' => true,
            'rewrite' => true,
            'menu_position' => 20,
            'menu_icon' => 'fa fa-list-alt',
            'supports' => [],
        ], $args);

        $args['key'] = $key;
        $args['singular'] = Str::singular($key);
        $args = new Collection($args);

        add_filters('juzaweb.post_types', function ($items) use ($args) {
            $items[$args->get('key')] = $args;
            return $items;
        });

        if ($args->get('show_in_menu')) {
            $this->registerMenuPostType($key, $args);
        }

        $supports = $args->get('supports', []);
        if (in_array('category', $supports)) {
            $this->registerTaxonomy('categories', $key, [
                'label' => trans('juzaweb::app.categories'),
                'menu_position' => 4,
                'show_in_menu' => $args->get('show_in_menu'),
                'rewrite' => $args->get('rewrite'),
            ]);
        }

        if (in_array('tag', $args['supports'])) {
            $this->registerTaxonomy('tags', $key, [
                'label' => trans('juzaweb::app.tags'),
                'menu_position' => 15,
                'show_in_menu' => $args->get('show_in_menu'),
                'rewrite' => $args->get('rewrite'),
                'supports' => []
            ]);
        }

        $this->registerPermalink($key, [
            'label' => $args->get('label'),
            'base' => $args->get('singular'),
        ]);
    }

    /**
     * @param string $key
     * @param Collection $args
     */
    protected function registerMenuPostType($key, $args)
    {
        $this->addAdminMenu(
            $args->get('label'),
            $key,
            [
                'icon' => $args->get('menu_icon', 'fa fa-edit'),
                'position' => $args->get('menu_position', 20)
            ]
        );

        $this->addAdminMenu(
            trans('juzaweb::app.all') . ' '. $args->get('label'),
            $key,
            [
                'icon' => 'fa fa-list-ul',
                'position' => 2,
                'parent' => $key,
            ]
        );

        $this->addAdminMenu(
            trans('juzaweb::app.add_new'),
            $key . '.create',
            [
                'icon' => 'fa fa-plus',
                'position' => 3,
                'parent' => $key,
            ]
        );
    }
}
