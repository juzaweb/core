<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as ModelAlias;
use Juzaweb\Modules\Core\Traits\QueryCacheable;

class Model extends ModelAlias
{
    use QueryCacheable;

    public function cachePrefixValue(): string
    {
        return cache_prefix($this->getTable());
    }

    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }
}
