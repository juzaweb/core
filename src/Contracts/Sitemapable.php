<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Sitemapable extends \Spatie\Sitemap\Contracts\Sitemapable
{
    public function scopeForSitemap(Builder $builder): Builder;

    public static function getSitemapPage(): string;
}
