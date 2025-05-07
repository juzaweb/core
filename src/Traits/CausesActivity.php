<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Spatie\Activitylog\ActivityLogger;

trait CausesActivity
{
    use \Spatie\Activitylog\Traits\CausesActivity;

    public function logActivity(?string $logName = null): ActivityLogger
    {
        return activity($logName)->causedBy($this);
    }
}
