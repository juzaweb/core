<?php

namespace Juzaweb\Modules\Core\Themes\Support;

use Illuminate\Filesystem\Filesystem;
use Juzaweb\Modules\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Support\Collection;
use Juzaweb\Modules\Core\Themes\Exceptions\ThemeNotFoundException;

/**
 * Adapter class to make ThemeRepository compatible with RepositoryInterface
 * This allows reusing Installer and Updater classes for themes
 */
class ThemeRepositoryAdapter implements RepositoryInterface
{
    public function __construct(
        protected ThemeContract $themeRepository,
        protected Filesystem $files
    ) {
    }

    /**
     * Get all themes.
     *
     * @return array
     */
    public function all()
    {
        return $this->themeRepository->all()->toArray();
    }

    /**
     * Get cached themes.
     *
     * @return array
     */
    public function getCached()
    {
        // Themes don't have caching like modules
        return $this->all();
    }

    /**
     * Scan & get all available themes.
     *
     * @return array
     */
    public function scan()
    {
        return $this->themeRepository->all()->toArray();
    }

    /**
     * Get themes as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->all());
    }

    /**
     * Get scanned paths.
     *
     * @return array
     */
    public function getScanPaths()
    {
        return [$this->getPath()];
    }

    /**
     * Get list of enabled themes.
     * Note: All themes are considered "enabled" in this context
     *
     * @return mixed
     */
    public function allEnabled()
    {
        return $this->all();
    }

    /**
     * Get list of disabled themes.
     * Note: Themes don't have disabled state like modules
     *
     * @return mixed
     */
    public function allDisabled()
    {
        return [];
    }

    /**
     * Get count from all themes.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->themeRepository->all()->count();
    }

    /**
     * Get all ordered themes.
     *
     * @param string $direction
     * @return array
     */
    public function getOrdered($direction = 'asc')
    {
        return $this->all();
    }

    /**
     * Get themes by the given status.
     *
     * @param int $status
     *
     * @return mixed
     */
    public function getByStatus($status)
    {
        if ($status) {
            return $this->allEnabled();
        }

        return $this->allDisabled();
    }

    /**
     * Find a specific theme.
     *
     * @param string $name
     * @return \Juzaweb\Modules\Core\Themes\Theme|null
     */
    public function find(string $name)
    {
        return $this->themeRepository->find($name);
    }

    /**
     * Find a specific theme. If there return that, otherwise throw exception.
     *
     * @param string $name
     *
     * @return \Juzaweb\Modules\Core\Themes\Theme
     * @throws ThemeNotFoundException
     */
    public function findOrFail(string $name)
    {
        return $this->themeRepository->findOrFail($name);
    }

    /**
     * Get theme path for a specific theme.
     * This implements the module interface method name but returns theme path
     *
     * @param string $themeName
     * @return string
     */
    public function getModulePath($themeName)
    {
        $theme = $this->find($themeName);

        if ($theme) {
            return $theme->path() . '/';
        }

        // If theme doesn't exist, return default path
        return $this->getPath() . '/' . strtolower($themeName) . '/';
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFiles(): Filesystem
    {
        return $this->files;
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public function config(string $key, $default = null)
    {
        return config('themes.' . $key, $default);
    }

    /**
     * Get themes path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return config('themes.path', base_path('themes'));
    }

    /**
     * Boot the themes.
     */
    public function boot(): void
    {
        // Themes are initialized via ThemeRepository::init()
        $this->themeRepository->init();
    }

    /**
     * Register the themes.
     */
    public function register(): void
    {
        // Themes don't have register process like modules
    }

    /**
     * Get asset path for a specific theme.
     *
     * @param string $theme
     * @return string
     */
    public function assetPath(string $theme): string
    {
        return public_path('themes/' . $theme);
    }

    /**
     * Delete a specific theme.
     *
     * @param string $theme
     * @return bool
     * @throws ThemeNotFoundException
     */
    public function delete(string $theme): bool
    {
        $themeEntity = $this->findOrFail($theme);

        return $this->files->deleteDirectory($themeEntity->path());
    }

    /**
     * Determine whether the given theme is activated.
     *
     * @param string $name
     * @return bool
     * @throws ThemeNotFoundException
     */
    public function isEnabled(string $name): bool
    {
        return $this->findOrFail($name)->isActive();
    }

    /**
     * Determine whether the given theme is not activated.
     *
     * @param string $name
     * @return bool
     * @throws ThemeNotFoundException
     */
    public function isDisabled(string $name): bool
    {
        return !$this->isEnabled($name);
    }
}
