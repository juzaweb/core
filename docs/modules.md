Module is a package created to manage your large application using Modules. A Module is like a Laravel package, it has some views, controllers or models.

## Getting Started

### Make a Module

```bash
php artisan module:make ModuleName
```

### Make CRUD on module

1. Create migration file for your feature
2. Make model for your feature

```bash
php artisan module:make-model Post ModuleName
```

3. Make CRUD in Admin panel

```bash
php artisan module:make-crud Post ModuleName
```

## Facade

### Module Discovery

Get all modules.

```php
Module::all();
```

Get all cached modules.

```php
Module::getCached()
```

Get ordered modules. The modules will be ordered by the `priority` key in `module.json` file.

```php
Module::getOrdered();
```

Get scanned modules.

```php
Module::scan();
```

Find a specific module.

```php
Module::find('name');
// OR
Module::get('name');
```

Find a module, if there is one, return the `Module` instance, otherwise throw `Nwidart\Modules\Exceptions\ModuleNotFoundException`.

```php
Module::findOrFail('module-name');
```

Get scanned paths.

```php
Module::getScanPaths();
```

Get all modules as a collection instance.

```php
Module::toCollection();
```

Get modules by the status. 1 for active and 0 for inactive.

```php
Module::getByStatus(1);
```

Check the specified module. If it exists, will return `true`, otherwise `false`.

```php
Module::has('blog');
```

Get all enabled modules.

```php
Module::allEnabled();
```

Get all disabled modules.

```php
Module::allDisabled();
```

Get count of all modules.

```php
Module::count();
```

Get module path.

```php
Module::getPath();
```

Register the modules.

```php
Module::register();
```

Boot all available modules.

```php
Module::boot();
```

Get all enabled modules as collection instance.

```php
Module::collections();
```

Get module path from the specified module.

```php
Module::getModulePath('name');
```

Get assets path from the specified module.

```php
Module::assetPath('name');
```

Get config value from this package.

```php
Module::config('composer.vendor');
```

Get used storage path.

```php
Module::getUsedStoragePath();
```

Get used module for cli session.

```php
Module::getUsedNow();
// OR
Module::getUsed();
```

Set used module for cli session.

```php
Module::setUsed('name');
```

Get modules's assets path.

```php
Module::getAssetsPath();
```

Get asset url from specific module.

```php
Module::asset('blog:img/logo.img');
```

Add a macro to the module repository.

```php
Module::macro('hello', function() {
    echo "I'm a macro";
});
```

Call a macro from the module repository.

```php
Module::hello();
```

Get all required modules of a module

```php
Module::getRequirements('module name');
```

## Module Methods

Get an entity from a specific module.

```php
$module = Module::find('blog');
```

Get module name.

```php
$module->getName();
```

Get module name in lowercase.

```php
$module->getLowerName();
```

Get module name in studlycase.

```php
$module->getStudlyName();
```

Get module path.

```php
$module->getPath();
```

Disable the specified module.

```php
$module->disable();
```

Enable the specified module.

```php
$module->enable();
```

Delete the specified module.

```php
$module->delete();
```

Get an array of module requirements. Note: these should be aliases of the module.

```php
$module->getRequires();
```

## Commands

### Make a module

```bash
php artisan module:make module
```

### Module use command

Use a given module. This allows you to not specify the module name on other commands requiring the module name as an argument.

```bash
php artisan module:use module
```

### Module unuse command

This unsets the specified module that was set with the `module:use` command.

```bash
php artisan module:unuse
```

### Module list command

List all available modules.

```bash
php artisan module:list
```

### Module migrate command

Migrate the given module, or without a module an argument, migrate all modules.

```bash
php artisan module:migrate module
```

### Module migrate-rollback command

Rollback the given module, or without an argument, rollback all modules.

```bash
php artisan module:migrate-rollback module
```

### Module migrate-refresh command

Refresh the migration for the given module, or without a specified module refresh all modules migrations.

```bash
php artisan module:migrate-refresh module
```

### Module migrate-reset command module

Reset the migration for the given module, or without a specified module reset all modules migrations.

```bash
php artisan module:migrate-reset module
```

### Module seed command

Seed the given module, or without an argument, seed all modules

```bash
php artisan module:seed module
```

### Module publish-migration command

Publish the migration files for the given module, or without an argument publish all modules migrations.

```bash
php artisan module:publish-migration module
```

### Module publish-config command

Publish the given module configuration files, or without an argument publish all modules configuration files.

```bash
php artisan module:publish-config module
```

### Module publish-translation command

Publish the translation files for the given module, or without a specified module publish all modules migrations.

```bash
php artisan module:publish-translation module
```

### Module enable command

Enable the given module.

```bash
php artisan module:enable module
```

### Module disable command

Disable the given module.

```bash
php artisan module:disable module
```

### Module update command

Update the given module.

```bash
php artisan module:update module
```

## Generator Commands

### Module make command

Generate the given console command for the specified module.

```bash
php artisan module:make-command CreatePostCommand module
```

### Module make migration command

Generate a migration for specified module.

```bash
php artisan module:make-migration create_posts_table module
```

### Module make seed command

Generate the given seed name for the specified module.

```bash
php artisan module:make-seed seed_fake_blog_posts module
```

### Module make controller command

Generate a controller for the specified module.

```bash
php artisan module:make-controller PostsController module
```

Optional options:

- `--plain`,`-p` : create a plain controller
- `--api` : create a resource controller

### Module make model command

Generate the given model for the specified module.

```bash
php artisan module:make-model Post module
```

Optional options:

- `--fillable=field1,field2`: set the fillable fields on the generated model
- `--migration`, `-m`: create the migration file for the given model

### Module make provider command

Generate the given service provider name for the specified module.

```bash
php artisan module:make-provider moduleServiceProvider module
```

### Module make middleware command

Generate the given middleware name for the specified module.

```bash
php artisan module:make-middleware CanReadPostsMiddleware module
```

### Module make mail command

Generate the given mail class for the specified module.

```bash
php artisan module:make-mail SendWeeklyPostsEmail module
```

### Module make notification command

Generate the given notification class name for the specified module.

```bash
php artisan module:make-notification NotifyAdminOfNewComment module
```

### Module make listener command

Generate the given listener for the specified module. Optionally you can specify which event class it should listen to. It also accepts a `--queued` flag allowed queued event listeners.

```bash
php artisan module:make-listener NotifyUsersOfANewPost module
php artisan module:make-listener NotifyUsersOfANewPost module --event=PostWasCreated
php artisan module:make-listener NotifyUsersOfANewPost module --event=PostWasCreated --queued
```

### Module make request command

Generate the given request for the specified module.

```bash
php artisan module:make-request CreatePostRequest module
```

### Module make event command

Generate the given event for the specified module.

```bash
php artisan module:make-event BlogPostWasUpdated module
```

### Module make job command

Generate the given job for the specified module.

```bash
php artisan module:make-job JobName module

php artisan module:make-job JobName module --sync # A synchronous job class
```

### Module route-provider command

Generate the given route service provider for the specified module.

```bash
php artisan module:route-provider module
```

### Module make factory command

Generate the given database factory for the specified module.

```bash
php artisan module:make-factory ModelName module
```

### Module make policy command

Generate the given policy class for the specified module.

The `Policies` is not generated by default when creating a new module. Change the value of `paths.generator.policies` in `modules.php` to your desired location.

```bash
php artisan module:make-policy PolicyName module
```

### Module make rule command

Generate the given validation rule class for the specified module.

The `Rules` folder is not generated by default when creating a new module. Change the value of `paths.generator.rules` in `modules.php` to your desired location.

```bash
php artisan module:make-rule ValidationRule module
```

### Module make resource command

Generate the given resource class for the specified module. It can have an optional `--collection` argument to generate a resource collection.

The `Transformers` folder is not generated by default when creating a new module. Change the value of `paths.generator.resource` in `modules.php` to your desired location.

```bash
php artisan module:make-resource PostResource module
php artisan module:make-resource PostResource module --collection
```
