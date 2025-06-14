<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
