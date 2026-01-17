<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Illuminate\Support\Facades\Redis;
use Juzaweb\Modules\Admin\Networks\Facades\Network;

if (!function_exists('online_count')) {
    function online_count(): int
    {
        $siteId = Network::website()->id;
        $key = "site:{$siteId}:users_online";
        $now = time();
        $ttl = 300;

        return Redis::zcount($key, $now - $ttl, $now);
    }
}
