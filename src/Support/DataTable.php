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
 * Date: 5/31/2021
 * Time: 9:55 PM
 */

namespace Juzaweb\Core\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

abstract class DataTable
{
    /**
     * Columns datatable
     *
     * @return array
     */
    abstract public function columns();

    /**
     * Query data datatable
     *
     * @param Request $request
     * @return Builder
     */
    abstract public function query(Request $request);

    protected function bulkActions()
    {
        return [];
    }

    public static function render()
    {
        $datatable = new static();
        $uniqueId = 'juzaweb_'. Str::random(15);

        return view('juzaweb::components.datatable', [
            'columns' => $datatable->columns(),
            'actions' => $datatable->bulkActions(),
            'unique_id' => $uniqueId,
            'table' => Crypt::encryptString(static::class),
        ]);
    }
}