<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers;

use Juzaweb\Modules\Core\Traits\HasSessionResponses;

abstract class ThemeController extends Controller
{
    use HasSessionResponses;
}
