<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes\Exceptions;

class ThemeNotFoundException extends \Exception
{
    public static function make(string $name): ThemeNotFoundException
    {
        return new self("Theme [{$name}] does not exist!");
    }
}
