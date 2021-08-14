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

use Juzaweb\Core\Traits\MenuHookAction;
use Juzaweb\Core\Traits\PostTypeHookAction;
use Tadcms\Hooks\Facades\Events as Hook;

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
     * @param string $key
     * @param array $args
     * @throws \Exception
     * */
    public function registerPermalink($key, $args = [])
    {
        if (empty($args['label'])) {
            throw new \Exception('Permalink args label is required');
        }

        if (empty($args['base'])) {
            throw new \Exception('Permalink args default_base is required');
        }

        add_filters('juzaweb.permalinks', function ($items) use ($key, $args) {
            array_merge([
                'label' => '',
                'base' => '',
                'callback' => '',
                'position' => 20,
            ], $args);

            $args['key'] = $key;
            $items[$key] = collect($args);
            return $items;
        });
    }

    public function addAction($tag, $callback, $priority = 20, $arguments = 1)
    {
        Hook::addAction($tag, $callback, $priority, $arguments);
    }

    public function addFilter($tag, $callback, $priority = 20, $arguments = 1)
    {
        return Hook::addFilter($tag, $callback, $priority, $arguments);
    }

    /**
     * Add setting form
     * @param string $key
     * @param array $args
     *      - name : Name form setting
     *      - view : View form setting
     **/
    public function addSettingForm($key, $args = [])
    {
        Hook::addFilter('admin.general_settings.forms', function ($items) use ($key, $args) {
            $items[$key] = $args;
            return $items;
        });
    }
}
