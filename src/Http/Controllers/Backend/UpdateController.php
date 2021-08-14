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
use Juzaweb\Core\Updater\UpdaterManager;

class UpdateController extends BackendController
{
    protected $updater;

    public function __construct(UpdaterManager $updater)
    {
        $this->updater = $updater;
    }

    public function index()
    {
        return view('juzaweb::backend.update', [
            'title' => trans('juzaweb::app.updates'),
            'updater' => $this->updater
        ]);
    }

    public function update()
    {
        if (!$this->updater->source()->isNewVersionAvailable()) {
            return $this->error([
                'message' => trans('juzaweb::app.no_new_version_available'),
            ]);
        }

        Artisan::call('down');
        DB::beginTransaction();
        try {
            $versionAvailable = $this->updater->source()->getVersionAvailable();
            $release = $this->updater->source()->fetch($versionAvailable);
            $this->updater->source()->update($release);
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