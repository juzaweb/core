<?php

namespace Juzaweb\Core\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Contracts\ConfigContract;
use Juzaweb\Core\Support\Config as JwConfig;

class DbConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->checkDbConnection()) {
            return;
        }

        $mail = get_config('email');
        $timezone = get_config('timezone');
        $language = get_config('language');

        if ($mail) {
            $config = [
                'driver'     => 'smtp',
                'host'       => $mail['host'] ?? '',
                'port'       => $mail['port'] ?? '',
                'from'       => [
                    'address'   => $mail['from_address'] ?? '',
                    'name'      => $mail['from_name'] ?? '',
                ],
                'encryption' => $mail['encryption'] ?? '',
                'username'   => $mail['username'] ?? '',
                'password'   => $mail['password'] ?? '',
            ];

            Config::set('mail', $config);
        }

        if ($timezone) {
            Config::set('app.timezone', $timezone);
            date_default_timezone_set($timezone);
        }

        if ($language) {
            Config::set('app.locale', $language);
        }
    }

    public function register()
    {
        if (!$this->checkDbConnection()) {
            return;
        }

        $this->app->singleton(ConfigContract::class, function () {
            return new JwConfig();
        });
    }
    
    protected function checkDbConnection()
    {
        try {
            DB::connection()->getPdo();

            if (Schema::hasTable('configs')) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    
        return false;
    }
}
