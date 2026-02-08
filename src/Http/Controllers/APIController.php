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

    protected function getLimitRequest()
    {
        $limit = request()->get('limit', 10);

        if (!is_numeric($limit) || $limit <= 0 || $limit > 100) {
            $limit = 10;
        }

        return (int) $limit;
    }
}
