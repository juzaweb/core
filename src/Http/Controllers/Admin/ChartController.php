<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Facades\Chart;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;

class ChartController extends AdminController
{
    public function callAction($method, $parameters)
    {
        if (! config('analytics.property_id')) {
            return response()->json(['labels' => [], 'datasets' => []]);
        }

        return parent::callAction($method, $parameters);
    }

    public function chart(Request $request, string $websiteId, string $chart): JsonResponse
    {
        $chartClass = Chart::get($chart);

        if (! $chartClass) {
            return response()->json([
                'message' => 'Chart not found',
            ], 404);
        }

        $chartInstance = new $chartClass();

        return response()->json($chartInstance->getData());
    }
}
