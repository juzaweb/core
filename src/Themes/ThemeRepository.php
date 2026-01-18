<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Themes;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\ViewFinderInterface;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface as ModuleRepositoryInterface;
use Juzaweb\Modules\Core\Themes\Exceptions\ThemeNotFoundException;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;

class ThemeRepository implements ThemeContract
{
    protected Collection $themes;

    // protected ?Theme $currentTheme;

    protected Setting $setting;

    protected ViewFinderInterface $viewFinder;

    protected Translator $translator;

    protected ConfigContract $config;

    public function __construct(
        protected ApplicationContract $app,
        protected string $path
    ) {
        $this->setting = $app[Setting::class];
        $this->viewFinder = $app['view']->getFinder();
        $this->translator = $app['translator'];
        $this->config = $this->app['config'];
    }

    /**
     * Get all themes.
     *
     * @return Collection<Theme>
     */
    public function all(): Collection
    {
        return $this->scan();
    }

    public function find(string $name): ?Theme
    {
        foreach ($this->all() as $theme) {
            if ($theme->lowerName() === strtolower($name)) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param string $name
     *
     * @return Theme
     *
     * @throws ThemeNotFoundException
     */
    public function findOrFail(string $name): Theme
    {
        $theme = $this->find($name);

        if ($theme !== null) {
            return $theme;
        }

        throw ThemeNotFoundException::make($name);
    }

    public function current(): ?Theme
    {
        // if (isset($this->currentTheme)) {
        //     return $this->currentTheme;
        // }

        $theme = $this->setting->get('theme', 'itech');

        // return ($this->currentTheme = $currentTheme);
        return $this->find($theme);
    }

    public function has(string $name): bool
    {
        return $this->find($name) !== null;
    }

    public function activate(string $theme): bool
    {
        return $this->findOrFail($theme)->activate();
    }

    public function init(): void
    {
        if (!($theme = $this->current())) {
            return;
        }

        // Register and boot required modules for this theme
        $this->bootRequiredModules($theme);

        foreach ($theme->get('providers', []) as $provider) {
            $this->app->register($provider);
        }

        foreach ($theme->get('files', []) as $file) {
            require ($theme->path($file));
        }
    }

    /**
     * Boot required modules for the theme
     *
     * @param Theme $theme
     * @return void
     */
    protected function bootRequiredModules(Theme $theme): void
    {
        $requiredModules = $theme->getRequiredModules();

        if (empty($requiredModules)) {
            return;
        }

        $moduleRepository = $this->app[ModuleRepositoryInterface::class];

        foreach ($requiredModules as $moduleName) {
            $module = $moduleRepository->find($moduleName);

            if ($module === null) {
                throw new \RuntimeException(
                    "Required module '{$moduleName}' for theme '{$theme->name()}' not found."
                );
            }

            if ($module->isEnabled()) {
                // Already logged in register method
                continue;
            }

            $module->register();
            $module->boot();
        }
    }

    protected function scan(): Collection
    {
        if (isset($this->themes)) {
            return $this->themes;
        }

        if (!File::isDirectory($this->path)) {
            return ($this->themes = new Collection());
        }

        $themeDirectories = File::directories($this->path);
        $themes = [];

        foreach ($themeDirectories as $themePath) {
            $theme = $this->makeThemeEntity($themePath);
            $themes[$theme->name()] = $theme;

            $lowerName = $theme->lowerName();
            $langPublishPath = resource_path("lang/themes/{$lowerName}");

            $this->app[Translation::class]->register($lowerName, [
                'type' => 'theme',
                'key' => $lowerName,
                'namespace' => $lowerName,
                'lang_path' => $theme->path('resources/lang'),
                'src_path' => $theme->path(),
                'publish_path' => $langPublishPath,
            ]);
        }

        return ($this->themes = new Collection($themes));
    }

    protected function makeThemeEntity(string $path): Theme
    {
        return new Theme($this->app, $this, $path);
    }
}
