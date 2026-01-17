<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models;

use Juzaweb\Modules\Subscription\Models\Subscription as BaseSubscription;

class Subscription extends BaseSubscription
{
    protected $connection = 'mysql';
}
