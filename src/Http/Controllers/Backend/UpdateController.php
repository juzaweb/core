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
use Juzaweb\Core\Http\Controllers\BackendController;
use Juzaweb\Core\Models\UpdateProcess;
use Symfony\Component\Process\Process;

class UpdateController extends BackendController
{
    public function index()
    {
        return view('juzaweb::backend.update', [
            'title' => trans('juzaweb::app.updates'),
        ]);
    }

    public function process()
    {
        $processes = UpdateProcess::where('status', '=', 'pending')
            ->get();

        if ($processes->isEmpty()) {
            return redirect()->route('admin.update');
        }

        return view('juzaweb::backend.update_process', [
            'title' => trans('juzaweb::app.updates'),
            'processes' => $processes
        ]);
    }

    public function getProcess($id)
    {
        $process = UpdateProcess::find($id);

        if (empty($process)) {
            return $this->success([
                'message' => 'Update success',
                'result' => 'ok',
            ]);
        }

        return $this->success([
            'message' => 'Update success',
            'result' => $process->status,
        ]);
    }

    public function update()
    {
        //Artisan::call('down');
        //DB::beginTransaction();

        try {

            UpdateProcess::create([
                'name' => 'Core',
                'type' => 'core',
            ]);

            //DB::commit();
        } catch (\Throwable $e) {
            //DB::rollBack();
            return $this->error([
                'message' => $e->getMessage(),
            ]);
        }

        //Artisan::call('up');

        return $this->success([
            'message' => trans('juzaweb::app.updated_successfully'),
        ]);
    }
}