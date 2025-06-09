<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Controllers;

use Juzaweb\Core\Traits\HasRestResponses;

abstract class APIController extends Controller
{
    use HasRestResponses;
}
