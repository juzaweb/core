<?php

namespace Juzaweb\Modules\Core\Tests;

use Juzaweb\Modules\Core\Providers\CoreServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create class aliases for backward compatibility
        if (!class_exists('Juzaweb\Modules\Admin\Models\User')) {
            class_alias(
                'Juzaweb\Modules\Core\Models\User',
                'Juzaweb\Modules\Admin\Models\User'
            );
        }

        // Load and alias UserFactory
        $factoryPath = __DIR__ . '/Factories/UserFactory.php';
        if (file_exists($factoryPath)) {
            require_once $factoryPath;
            if (!class_exists('Juzaweb\Modules\Admin\Database\Factories\UserFactory')) {
                class_alias(
                    'Juzaweb\Modules\Core\Tests\Factories\UserFactory',
                    'Juzaweb\Modules\Admin\Database\Factories\UserFactory'
                );
            }
        }
    }


    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            CoreServiceProvider::class,
            \Juzaweb\QueryCache\QueryCacheServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup filesystem disks for testing
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
        ]);

        $app['config']->set('filesystems.disks.private', [
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ]);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}
