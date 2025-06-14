<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Traits\HasAPI;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use HasAPI;
}
