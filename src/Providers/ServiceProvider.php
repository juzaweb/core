<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    protected function loadMigrationsFrom($paths): void
    {
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            $directories = glob("{$path}/*", GLOB_ONLYDIR);

            array_push($paths, ...$directories);
        }

        parent::loadMigrationsFrom($paths);
    }
}
