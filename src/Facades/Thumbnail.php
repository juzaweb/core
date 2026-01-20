<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void defaults(callable $callback)
 * @method static array getDefaults()
 * @see \Juzaweb\Modules\Core\Support\ThumbnailRepository
 */
class Thumbnail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Thumbnail::class;
    }
}
