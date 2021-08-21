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
 * Date: 6/13/2021
 * Time: 11:09 AM
 */

namespace Juzaweb\Core\Http\Controllers\Backend;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Http\Controllers\BackendController;
use Symfony\Component\Process\Process;

class UpdateController extends BackendController
{
    public function index()
    {
        return view('juzaweb::backend.update', [
            'title' => trans('juzaweb::app.updates'),
        ]);
    }

    public function update()
    {
        Artisan::call('down');
        DB::beginTransaction();
        try {
            $cmd = 'cd ' . base_path() . ' && php composer.phar update juzaweb/*';
            $process = new Process($cmd);
            $process->start();

            Artisan::call('migrate', ['--force'=> true]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error([
                'message' => $e->getMessage(),
            ]);
        }

        Artisan::call('up');

        return $this->success([
            'message' => trans('juzaweb::app.updated_successfully'),
        ]);
    }
}