<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 6/19/2021
 * Time: 11:22 AM
 */

namespace Juzaweb\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Installer\Helpers\DatabaseManager;
use Juzaweb\Core\Installer\Helpers\FinalInstallManager;
use Juzaweb\Core\Installer\Helpers\InstalledFileManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Juzaweb\Core\Models\User;

class InstallCommand extends Command
{
    protected $signature = 'juzaweb:install';

    protected $user;

    public function handle(
        DatabaseManager $databaseManager,
        InstalledFileManager $fileManager,
        FinalInstallManager $finalInstall
    )
    {
        $this->info('MYMO CMS Installer');
        $this->info('-- Database Install');
        $databaseManager->run();
        $this->info('-- Publish assets');
        $finalInstall->runFinal();
        $this->info('-- Create user admin');
        $this->createAdminUser();
        $this->info('-- Update installed');
        $fileManager->update();
        $this->info('MYMO CMS Install Successfully !!!');
    }

    protected function createAdminUser()
    {
        $this->user['name'] = $this->ask('Full Name?');
        $this->user['email'] = $this->ask('Email?');
        $this->user['password'] = $this->ask('Password?');

        $validator = Validator::make($this->user, [
            'name' => 'required|max:150',
            'email' => 'required|email|max:150',
            'password' => 'required|max:32|min:6'
        ], [], [
            'name' => trans('juzaweb::app.name'),
            'email' => trans('juzaweb::app.email'),
            'password' => trans('juzaweb::app.password')
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->messages()[0]);
            $this->createAdminUser();
        }

        DB::beginTransaction();
        try {
            $model = new User();
            $model->fill($this->user);
            $model->password = Hash::make($this->user['password']);
            $model->is_admin = 1;
            $model->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}