<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers;

use Juzaweb\Core\Traits\HasSessionResponses;

abstract class AdminController extends Controller
{
    use HasSessionResponses;
}
