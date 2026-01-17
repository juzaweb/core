<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Notifications\Facades;

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
        return \Juzaweb\Modules\Core\Notifications\Notification::class;
    }
}
