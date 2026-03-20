<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Support\GlobalDataRepository;

/**
 * @method static void set(string $key, array $value)
 * @method static void push(string $key, mixed $value)
 * @method static mixed get(string $key, array $default = [])
 * @method static Collection collect(string $key, array $default = [])
 * @method static Collection all()
 *
 * @see GlobalDataRepository
 */
class GlobalData extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\GlobalData::class;
    }
}
