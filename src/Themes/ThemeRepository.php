<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\ViewFinderInterface;
use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Core\Themes\Exceptions\ThemeNotFoundException;
use Juzaweb\Translations\Contracts\Translation;
use Laravel\Folio\FolioManager;
use Laravel\Folio\PendingRoute;

class ThemeRepository implements ThemeContract
{
    protected Collection $themes;

    protected ?Theme $currentTheme;

    protected Setting $setting;

    protected ViewFinderInterface $viewFinder;

    protected Translator $translator;

    protected ConfigContract $config;

    /**
     * @var FolioManager|PendingRoute $folio
     */
    protected FolioManager $folio;

    public function __construct(
        protected ApplicationContract $app,
        protected string $path
    ) {
        $this->setting = $app[Setting::class];
        $this->viewFinder = $app['view']->getFinder();
        $this->translator = $app['translator'];
        $this->config = $this->app['config'];
        $this->folio = $app[FolioManager::class];
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

        $theme = null;
        $statusPath = $this->config->get('themes.path') . '/statuses.json';
        if (File::exists($statusPath)) {
            $theme = json_decode(File::get($statusPath), true, 512, JSON_THROW_ON_ERROR);
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

        foreach ($theme->get('providers', []) as $provider) {
            $this->app->register($provider);
        }

        $viewPath = $theme->path('views');
        $langPath = $theme->path('lang');

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

        $this->folio->path("{$viewPath}/pages")
            ->middleware([
                '*' => [
                    'web',
                ],
            ]);

        $lowerName = $theme->lowerName();

        $this->app[Translation::class]->register("{$lowerName}_theme", [
            'type' => 'theme',
            'key' => $lowerName,
            'namespace' => '*',
            'lang_path' => $theme->path('/lang'),
            'src_path' => $theme->path('/src'),
            'publish_path' => $langPublishPath,
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
