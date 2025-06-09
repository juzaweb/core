<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
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
