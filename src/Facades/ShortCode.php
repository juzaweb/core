<?php

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Contracts\ShortCode as ShortCodeContract;

/**
 * @method static void register(string $tag, callable|string $callback)
 * @method static string compile(string $content)
 * @see \Juzaweb\Modules\Core\Support\ShortCode
 */
class ShortCode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ShortCodeContract::class;
    }
}
