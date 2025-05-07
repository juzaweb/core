<?php

namespace Juzaweb\Core\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Core\Modules\FileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, FileRepository::class);
    }
}
