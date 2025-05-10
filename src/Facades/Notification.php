<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void subscriptable(string $channel, array $data = [])
 * @method static array getSubscriptableChannels()
 * @method static array getSubscriptableData(string $channel)
 */
class Notification extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Notifications\Contracts\Notification::class;
    }
}
