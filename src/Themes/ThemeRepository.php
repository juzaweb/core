<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\ViewFinderInterface;
use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Core\Themes\Exceptions\ThemeNotFoundException;
use Juzaweb\Core\Translations\Contracts\Translation;

class ThemeRepository implements ThemeContract
{
    protected Collection $themes;

    protected ?Theme $currentTheme;

    protected Setting $setting;

    protected ViewFinderInterface $viewFinder;

    protected Translator $translator;

    public function __construct(
        protected ApplicationContract $app,
        protected string $path
    ) {
        $this->setting = $app[Setting::class];
        $this->viewFinder = $app['view']->getFinder();
        $this->translator = $app['translator'];
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
        if (isset($this->currentTheme)) {
            return $this->currentTheme;
        }

        try {
            $theme = $this->setting->get('theme_statuses', []);
        } catch (\Exception $exception) {
            $theme = null;
        }

        if (empty($theme)) {
            return null;
        }

        $currentTheme = $this->find(Arr::get($theme, 'name'));

        return ($this->currentTheme = $currentTheme);
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

        $viewPath = $theme->path('src/views');
        $langPath = $theme->path('src/lang');

        $viewPublishPath = resource_path('views/themes/' . $theme->name());
        $langPublishPath = resource_path('lang/themes/' . $theme->name());

        $namespace = 'theme';
        $this->viewFinder->addNamespace($namespace, $viewPath);

        if (is_dir($viewPublishPath)) {
            $this->viewFinder->prependNamespace($namespace, $viewPublishPath);
        }

        $this->translator->addNamespace($namespace, $langPath);

        if (is_dir($langPublishPath)) {
            $this->translator->addNamespace($namespace, $langPublishPath);
        }

        $lowerName = $theme->lowerName();

        $this->app[Translation::class]->register("{$lowerName}_theme", [
            'type' => 'theme',
            'key' => $lowerName,
            'namespace' => '*',
            'lang_path' => $theme->path('/lang'),
            'src_path' => $theme->path('/src'),
            'publish_path' => resource_path("lang/vendor/{$lowerName}"),
        ]);
    }

    protected function scan(): Collection
    {
        if (isset($this->themes)) {
            return $this->themes;
        }

        $themeDirectories = File::directories($this->path);
        $themes = [];

        foreach ($themeDirectories as $themePath) {
            $theme = $this->makeThemeEntity($themePath);
            $themes[$theme->name()] = $theme;
        }

        return ($this->themes = new Collection($themes));
    }

    protected function makeThemeEntity(string $path): Theme
    {
        return new Theme($this->app, $this, $path);
    }
}
