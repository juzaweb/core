<?php

use Juzaweb\Core\Modules\Activators\FileActivator;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'Juzaweb\\Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path('vendor/juzaweb/core/stubs/modules'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'views/index' => 'resources/views/index.blade.php',
            // 'views/master' => 'resources/views/layouts/master.blade.php',
            'scaffold/config' => 'config/config.php',
            'composer' => 'composer.json',
            // 'assets/js/app' => 'Resources/js/app.js',
            // 'assets/sass/app' => 'Resources/sass/app.scss',
            // 'vite' => 'vite.config.js',
            // 'package' => 'package.json',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME'],
            'vite' => ['LOWER_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
            ],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */

        'assets' => public_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */

        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key too false to not generate that folder
        */
        'generator' => [
            'config' => ['path' => 'config', 'generate' => true],
            'command' => ['path' => 'Commands', 'generate' => true, 'namespace' => 'Commands'],
            'migration' => ['path' => 'database/migrations', 'generate' => true],
            'seeder' => ['path' => 'database/seeders', 'generate' => true, 'namespace' => 'Database/Seeders'],
            'factory' => ['path' => 'database/factories', 'generate' => true, 'namespace' => 'Database/Factories'],
            'model' => ['path' => 'Models', 'generate' => true, 'namespace' => 'Models'],
            'routes' => ['path' => 'Routes', 'generate' => true],
            'controller' => ['path' => 'Http/Controllers', 'generate' => true, 'namespace' => 'Http/Controllers'],
            'filter' => ['path' => 'Http/Middleware', 'generate' => true, 'namespace' => 'Http/Middleware'],
            'request' => ['path' => 'Http/Requests', 'generate' => true, 'namespace' => 'Http/Requests'],
            'provider' => ['path' => 'Providers', 'generate' => true, 'namespace' => 'Providers'],
            'assets' => ['path' => 'assets', 'generate' => true],
            'lang' => ['path' => 'resources/lang', 'generate' => true],
            'views' => ['path' => 'resources/views', 'generate' => true],
            'test' => ['path' => 'tests/Unit', 'generate' => true, 'namespace' => 'Tests\\Unit'],
            'test-feature' => ['path' => 'tests/Feature', 'generate' => true, 'namespace' => 'Tests\\Feature'],
            'repository' => ['path' => 'Repositories', 'generate' => false, 'namespace' => 'Repositories'],
            'event' => ['path' => 'Events', 'generate' => false, 'namespace' => 'Events'],
            'listener' => ['path' => 'Listeners', 'generate' => false, 'namespace' => 'Listeners'],
            'policies' => ['path' => 'Policies', 'generate' => false, 'namespace' => 'Policies'],
            'rules' => ['path' => 'Rules', 'generate' => false, 'namespace' => 'Rules'],
            'jobs' => ['path' => 'Jobs', 'generate' => false, 'namespace' => 'Jobs'],
            'emails' => ['path' => 'Emails', 'generate' => false, 'namespace' => 'Emails'],
            'notifications' => ['path' => 'Notifications', 'generate' => false, 'namespace' => 'Notifications'],
            'resource' => ['path' => 'Http/Resources', 'generate' => false, 'namespace' => 'Http/Resources'],
            'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'component-class' => ['path' => 'View/Components', 'generate' => false, 'namespace' => 'View/Components'],
            'datatable' => [
                'path' => 'Http/DataTables',
                'generate' => false,
                'namespace' => 'Http/DataTables',
                // Column has link to edit
                'titleColumns' => [
                    'name',
                    'title',
                ],
                // Exclude columns of Datatable header
                'excludeColumns' => [
                    'updated_at',
                    'deleted_at',
                    'content',
                    'description',
                    'locale',
                    'type',
                ],

                // Exclude actions of Datatable
                'excludeActions' => [
                    'restore',
                    'forceDelete',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */

    'composer' => [
        'vendor' => 'larabizcms',
        'author' => [
            'name' => 'Larabiz CMS',
            'email' => 'admin@larabiz.com',
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'driver' => 'file',
        'key' => 'larabiz-modules',
        'lifetime' => 60,
    ],
    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         *
         * Note: boot not compatible with asgardcms
         *
         * @example boot|register
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules/statuses.json'),
            'cache-key' => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',
];
