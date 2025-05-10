<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Traits\HasAPI;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use HasAPI;
}
