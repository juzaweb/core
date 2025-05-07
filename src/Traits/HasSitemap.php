<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Support\Str;
use Juzaweb\Core\Models\Model;

/**
 * @mixin Model
 */
trait HasSitemap
{
    public static function getSitemapPage(): string
    {
        return Str::slug((new static())->getTable());
    }
}
