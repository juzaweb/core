<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers;

use Juzaweb\Core\Traits\HasSessionResponses;

abstract class AdminController extends Controller
{
    use HasSessionResponses;
}
