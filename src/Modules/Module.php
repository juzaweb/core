<?php

namespace Juzaweb\Modules\Core\Modules;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Translation\Translator;
use Juzaweb\Modules\Core\Modules\Contracts\ActivatorInterface;
use Juzaweb\Modules\Core\Modules\Support\Json;
use Throwable;

class Module
{
    use Macroable;

    /**
     * The laravel|lumen application instance.
     *
     * @var Application
     */
    protected Application|Container $app;

    /**
     * The module name.
     *
     * @var
     */
    protected string $name;

    /**
     * The module path.
     *
     * @var string
     */
    protected string $path;

    /**
     * @var array of cached Json objects, keyed by filename
     */
    protected array $moduleJson = [];
    /**
     * @var CacheManager
     */
    private mixed $cache;
    /**
     * @var Filesystem
     */
    private mixed $files;
    /**
     * @var Translator
     */
    private mixed $translator;
    /**
     * @var ActivatorInterface
     */
    private mixed $activator;

    /**
     * The constructor.
     * @param Application|Container $app
     * @param $name
     * @param $path
     */
    public function __construct(Application $app, string $name, $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->translator = $app['translator'];
        $this->activator = $app[ActivatorInterface::class];
        $this->app = $app;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName(): string
    {
        return strtolower($this->name);
    }

    public function getAliasName(): string
    {
        return $this->get('alias');
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName(): string
    {
        return Str::studly($this->name);
    }

    /**
     * Get name in snake case.
     *
     * @return string
     */
    public function getSnakeName(): string
    {
        return Str::snake($this->name);
    }

    /**
     * Get name in kebab case.
     *
     * @return string
     */
    public function getKebabName(): string
    {
        return strtolower(Str::kebab($this->name));
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->get('description');
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority(): string
    {
        return $this->get('priority');
    }

    public function getResourceNamespace(): string|null
    {
        return $this->get('alias');
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path): static
    {
        $this->path = $path;

        return $this;
    }

    public function path(?string $path = null): string
    {
        if ($path) {
            return $this->getPath() . '/' . $path;
        }

        return $this->getPath();
    }

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if (config('modules.register.translations', true) === true) {
            $this->registerTranslation();
        }

        if ($this->isLoadFilesOnBoot()) {
            $this->registerFiles();
        }

        $this->fireEvent('boot');
    }

    /**
     * Register module's translation.
     *
     * @return void
     */
    protected function registerTranslation(): void
    {
        $lowerName = $this->getLowerName();

        $langPath = $this->getPath() . '/Resources/lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param  string|null  $file
     *
     * @return Json
     */
    public function json(?string $file = null): Json
    {
        if ($file === null) {
            $file = 'module.json';
        }

        return Arr::get($this->moduleJson, $file, function () use ($file) {
            return $this->moduleJson[$file] = new Json($this->getPath() . '/' . $file, $this->files);
        });
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param string $key
     * @param null $default
     *
     * @return array|string|null
     */
    public function get(string $key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get a specific data from composer.json file by given the key.
     *
     * @param string $key
     * @param null $default
     *
     * @return array|string|null
     */
    public function getComposerAttr(string $key, $default = null): array|string|null
    {
        return $this->json('composer.json')->get($key, $default);
    }

    /**
     * Register the module.
     */
    public function register(): void
    {
        $this->registerAliases();

        $this->registerProviders();

        if ($this->isLoadFilesOnBoot() === false) {
            $this->registerFiles();
        }

        $this->fireEvent('register');
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function fireEvent($event): void
    {
        $this->app['events']->dispatch(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath(): string
    {
        // This checks if we are running on a Laravel Vapor managed instance
        // and sets the path to a writable one (services path is not on a writable storage in Vapor).
        if (!is_null(env('VAPOR_MAINTENANCE_MODE'))) {
            return Str::replaceLast('config.php', $this->getSnakeName() . '_module.php', $this->app->getCachedConfigPath());
        }

        return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->getCachedServicesPath());
    }

    /**
     * {@inheritdoc}
     */
    public function registerProviders(): void
    {
        try {
            (new ProviderRepository($this->app, new Filesystem(), $this->getCachedServicesPath()))
                ->load($this->get('providers', []));
        } catch (Throwable $e) {
            report($e);
            if (! app()->runningInConsole()) {
                admin_message("Module {$this->getName()} Provider Error: " . $e->getMessage());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerAliases(): void
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->get('aliases', []) as $aliasName => $aliasClass) {
            $loader->alias($aliasName, $aliasClass);
        }
    }

    /**
     * Register the files from this module.
     */
    protected function registerFiles(): void
    {
        foreach ($this->get('files', []) as $file) {
            include $this->path . '/' . $file;
        }
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Determine whether the given status same with the current module status.
     *
     * @param bool $status
     *
     * @return bool
     */
    public function isStatus(bool $status): bool
    {
        return $this->activator->hasStatus($this, $status);
    }

    /**
     * Determine whether the current module activated.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->activator->hasStatus($this, true);
    }

    /**
     *  Determine whether the current module not disabled.
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    /**
     * Set active state for current module.
     *
     * @param bool $active
     *
     * @return void
     */
    public function setActive(bool $active): void
    {
        $this->activator->setActive($this, $active);
    }

    /**
     * Disable the current module.
     */
    public function disable(): void
    {
        $this->fireEvent('disabling');

        $this->activator->disable($this);
        $this->flushCache();

        $this->fireEvent('disabled');
    }

    /**
     * Enable the current module.
     */
    public function enable(): void
    {
        $this->fireEvent('enabling');

        $this->activator->enable($this);
        $this->flushCache();

        $this->fireEvent('enabled');
    }

    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $this->activator->delete($this);

        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    /**
     * Get extra path.
     *
     * @param string $path
     *
     * @return string
     */
    public function getExtraPath(string $path): string
    {
        return $this->getPath() . '/' . $path;
    }

    /**
     * Check if can load files of module on boot method.
     *
     * @return bool
     */
    protected function isLoadFilesOnBoot(): bool
    {
        return config('modules.register.files', 'register') === 'boot';
    }

    protected function flushCache(): void
    {
        if (config('modules.cache.enabled')) {
            $this->cache->store(config('modules.cache.driver'))->flush();
        }
    }

    /**
     * Register a translation file namespace.
     *
     * @param  string  $path
     * @param  string  $namespace
     * @return void
     */
    protected function loadTranslationsFrom(string $path, string $namespace): void
    {
        $this->translator->addNamespace($namespace, $path);
    }
}
