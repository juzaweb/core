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
        // Use MySQL if DB_CONNECTION is set (e.g., in CI), otherwise use SQLite
        $connection = env('DB_CONNECTION', 'sqlite');

        if ($connection === 'mysql') {
            $app['config']->set('database.default', 'mysql');
            $app['config']->set('database.connections.mysql', [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'testing'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ]);
        } else {
            // Setup default database to use sqlite :memory:
            $app['config']->set('database.default', 'testbench');
            $app['config']->set('database.connections.testbench', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
        }

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
        $connection = config('database.default');

        $this->loadLaravelMigrations(['--database' => $connection]);

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->artisan('migrate', ['--database' => $connection])->run();
    }
}
