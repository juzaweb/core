<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Contracts\Theme as ThemeContract;
use Illuminate\Contracts\Config\Repository as ConfigContract;

class Theme implements Arrayable
{
    protected Setting $setting;

    protected ConfigContract $config;

    public function __construct(
        protected Application $app,
        protected ThemeContract $themeRepository,
        protected string $path
    ) {
        $this->setting = $app[Setting::class];
        $this->config = $this->app['config'];
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->get('name');
    }

    public function lowerName(): string
    {
        return strtolower($this->name());
    }

    /**
     * Get path.
     *
     * @param string $path
     * @return string
     */
    public function path(string $path = ''): string
    {
        if (empty($path)) {
            return realpath($this->path);
        }

        return realpath($this->path) .'/'. ltrim("/{$path}", '/');
    }

    public function isActive(): bool
    {
        return $this->themeRepository->current()?->lowerName() == $this->lowerName();
    }

    public function activate(): bool
    {
        File::put(
            $this->config->get('themes.path') . '/theme_statuses.json',
            json_encode(['name' => $this->name()], JSON_THROW_ON_ERROR)
        );

        return true;
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string|null $file
     *
     * @return Collection
     * @throws \Exception
     */
    public function json(string $file = null): Collection
    {
        if ($file === null) {
            $file = 'theme.json';
        }

        return collect(json_decode(File::get($this->path($file)), true, 512, JSON_THROW_ON_ERROR));
    }

    public function toArray(): array
    {
        return $this->json()->toArray();
    }
}
