<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as ModelAlias;
use Juzaweb\Core\Traits\QueryCacheable;

/**
 * Juzaweb\Core\Models\Model
 *
 * @property array $translatedAttributes
 * @method static Builder|Model newModelQuery()
 * @method static Builder|Model newQuery()
 * @method static Builder|Model query()
 * @mixin \Eloquent
 */
class Model extends ModelAlias
{
    use QueryCacheable;

    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }
}
