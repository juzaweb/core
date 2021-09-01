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
use Juzaweb\Theme\Support\PostTypeMenuBox;

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
                'label_type' => ucfirst($type) .' '. $args['label'],
                'priority' => 20,
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

            $args['type'] = $type;
            $args['post_type'] = $objectType;
            $args['taxonomy'] = $taxonomy;
            $args['singular'] = Str::singular($taxonomy);
            $args = new Collection(array_merge($opts, $args));

            add_filters('juzaweb.taxonomies', function ($items) use ($taxonomy, $objectType, $args) {
                $items[$objectType][$taxonomy] = $args;
                return $items;
            }, $args->get('priority'));

            if ($args->get('show_in_menu')) {
                $this->addAdminMenu(
                    $args->get('label'),
                    $args->get('menu_slug'),
                    [
                        'icon' => $args->get('menu_icon', 'fa fa-list'),
                        'parent' => $args->get('parent'),
                        'position' => $args->get('menu_position')
                    ]
                );
            }

            if ($args->get('rewrite')) {
                $this->registerPermalink($args->get('menu_slug'), [
                    'label' => $args->get('label'),
                    'base' => Str::singular($type . '-' . $taxonomy),
                    'priority' => $args->get('priority'),
                ]);
            }
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

        if (empty($args['label'])) {
            throw new \Exception('Post type label is required.');
        }

        $args = array_merge([
            'description' => '',
            'priority' => 20,
            'show_in_menu' => true,
            'rewrite' => true,
            'taxonomy_rewrite' => true,
            'menu_box' => true,
            'menu_position' => 20,
            'menu_icon' => 'fa fa-list-alt',
            'supports' => [],
        ], $args);

        $args['key'] = $key;
        $args['singular'] = Str::singular($key);
        $args['model_key'] = str_replace('\\', '_', $args['model']);

        $args = new Collection($args);

        add_filters('juzaweb.post_types', function ($items) use ($args) {
            $items[$args->get('key')] = $args;
            return $items;
        }, $args->get('priority'));

        if ($args->get('show_in_menu')) {
            $this->registerMenuPostType($key, $args);
        }

        $supports = $args->get('supports', []);
        if (in_array('category', $supports)) {
            $this->registerTaxonomy('categories', $key, [
                'label' => trans('juzaweb::app.categories'),
                'priority' => $args->get('priority') + 5,
                'menu_position' => 4,
                'show_in_menu' => $args->get('show_in_menu'),
                'rewrite' => $args->get('taxonomy_rewrite'),
            ]);
        }

        if (in_array('tag', $args['supports'])) {
            $this->registerTaxonomy('tags', $key, [
                'label' => trans('juzaweb::app.tags'),
                'priority' => $args->get('priority') + 6,
                'menu_position' => 15,
                'show_in_menu' => $args->get('show_in_menu'),
                'rewrite' => $args->get('taxonomy_rewrite'),
                'supports' => []
            ]);
        }

        if ($args->get('rewrite')) {
            $this->registerPermalink($key, [
                'label' => $args->get('label'),
                'base' => $args->get('singular'),
                'priority' => $args->get('priority'),
            ]);
        }

        if ($args->get('menu_box')) {
            $this->registerMenuBox('post_type.' . $key, [
                'title' => $args->get('label'),
                'group' => 'post_type',
                'menu_box' => new PostTypeMenuBox($key, $args),
                'priority' => 10
            ]);
        }
    }

    /**
     * @param string $key
     * @param Collection $args
     */
    protected function registerMenuPostType($key, $args)
    {
        $supports = $args->get('supports', []);

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

        if (in_array('comment', $supports)) {
            $this->addAdminMenu(
                trans('juzaweb::app.comments'),
                $args->get('singular') . '.comments',
                [
                    'icon' => 'fa fa-comments',
                    'position' => 20,
                    'parent' => $key,
                ]
            );
        }
    }
}
