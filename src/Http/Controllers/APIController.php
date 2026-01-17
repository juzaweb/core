<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Controllers;

use Juzaweb\Modules\Core\Traits\HasRestResponses;

abstract class APIController extends Controller
{
    use HasRestResponses;
}
