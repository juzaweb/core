<?php
/**
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 10:05 PM
 */

namespace Juzaweb\Core\Helpers;

use Illuminate\Support\Collection;
use Juzaweb\Core\Traits\MenuHookAction;
use Juzaweb\Core\Traits\PostTypeHookAction;
use Juzaweb\Core\Facades\Hook;

class HookAction
{
    use MenuHookAction, PostTypeHookAction;

    /**
     * Add hook actions folder
     *
     * @param string $path
     **/
    public function loadActionForm($path)
    {
        add_filters('juzaweb.actions', function ($items) use ($path) {
            $items[] = $path;
            return collect($items)->unique();
        });
    }

    /**
     * Registers menu item in menu builder.
     *
     * @param string $postType
     * @param array $args
     * @throws \Exception
     * */
    public function registerPermalink($postType, $args = [])
    {
        if (empty($args['label'])) {
            throw new \Exception('Permalink args label is required');
        }

        if (empty($args['base'])) {
            throw new \Exception('Permalink args default_base is required');
        }

        $args = new Collection(array_merge([
            'label' => '',
            'base' => '',
            'callback' => '',
            'priority' => 20,
            'position' => 20,
        ], $args));

        add_filters('juzaweb.permalinks', function ($items) use ($postType, $args) {
            $args['key'] = $postType;
            $items[$postType] = collect($args);
            return $items;
        }, $args->get('priority'));
    }

    public function addAction($tag, $callback, $priority = 20, $arguments = 1)
    {
        Hook::addAction($tag, $callback, $priority, $arguments);
    }

    public function addFilter($tag, $callback, $priority = 20, $arguments = 1)
    {
        Hook::addFilter($tag, $callback, $priority, $arguments);
    }

    public function applyFilters($tag, $value, ...$args)
    {
        return Hook::filter($tag, $value, ...$args);
    }

    /**
     * Add setting form
     * @param string $key
     * @param array $args
     *      - name : Name form setting
     *      - view : View form setting
     */
    public function addSettingForm($key, $args = [])
    {
        Hook::addFilter('admin.general_settings.forms', function ($items) use ($key, $args) {
            $items[$key] = $args;
            return $items;
        }, $args['priority'] ?? 10);
    }


}
