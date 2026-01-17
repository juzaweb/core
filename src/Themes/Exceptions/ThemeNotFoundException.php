<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Themes\Exceptions;

class ThemeNotFoundException extends \Exception
{
    public static function make(string $name): ThemeNotFoundException
    {
        return new self("Theme [{$name}] does not exist!");
    }
}
