<?php
/**
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 10:05 PM
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class HookActionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $paths = apply_filters('juzaweb.actions', []);

            foreach ($paths as $path) {
                if (!is_dir($path)) {
                    continue;
                }

                $files = File::allFiles($path);
                foreach ($files as $file) {
                    require ($file->getRealPath());
                }
            }
        });
    }
}
