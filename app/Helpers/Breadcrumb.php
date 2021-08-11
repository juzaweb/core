<?php
/**
 * MYMO CMS - The Best Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/25/2021
 * Time: 10:05 PM
 */

namespace Juzaweb\Core\Helpers;

class Breadcrumb
{
    public static function render($name, $items = [])
    {
        return view(static::getNameView($name), [
            'items' => $items
        ]);
    }
    
    public static function getNameView($name)
    {
        return apply_filters('breadcrumb.render', [
            'admin' => 'juzaweb::items.breadcrumb',
        ])[$name];
    }
}
