<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Models;

use Juzaweb\Modules\Core\Traits\HasAPI;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use HasAPI;
}
