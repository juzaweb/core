<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Spatie\Activitylog\ActivityLogger;

trait CausesActivity
{
    use \Spatie\Activitylog\Traits\CausesActivity;

    /**
     * Log an activity with an optional log name and mark the current user as the cause.
     *
     * @param string|null $logName Optional name for the log.
     * @return ActivityLogger The activity logger instance.
     */
    public function logActivity(?string $logName = null): ActivityLogger
    {
        return activity($logName)->causedBy($this);
    }
}
