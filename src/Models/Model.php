<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model as ModelAlias;
use Juzaweb\QueryCache\QueryCacheable;

class Model extends ModelAlias
{
    use QueryCacheable;

    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }
}
