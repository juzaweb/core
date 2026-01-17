<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Sitemap\Contracts\Sitemapable as BaseSitemapable;

interface Sitemapable extends BaseSitemapable
{
    public function scopeForSitemap(Builder $builder): Builder;

    public function getUrl(): string;

    public static function getSitemapPage(): string;
}
