<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 7/10/2021
 * Time: 5:24 PM
 */

namespace Juzaweb\Core\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Juzaweb\Core\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Crypt;
use Juzaweb\Core\Support\DataTable;

class DatatableController extends BackendController
{
    public function getData(Request $request)
    {
        $table = $this->getTable($request->get('table'));
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = $table->query($request);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $columns = $table->columns();
        foreach ($rows as $index => $row) {
            foreach ($columns as $key => $column) {
                if (!empty($column['formatter'])) {
                    $row->{$key} = $column['formatter']($row->{$key}, $row, $index);
                }
            }
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function bulkActions(Request $request)
    {

    }

    /**
     * Get datatable
     *
     * @param string $table
     * @return DataTable
     */
    protected function getTable($table)
    {
        $table = Crypt::decryptString($table);
        return app($table);
    }
}