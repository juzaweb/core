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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Modules\Core\Themes\Contracts\ThemeActivatorInterface;

class Theme implements Arrayable
{
    protected Setting $setting;

    protected ConfigContract $config;

    protected ThemeActivatorInterface $activator;

    protected array $json = [];

    public function __construct(
        protected Application $app,
        protected ThemeContract $themeRepository,
        protected string $path
    ) {
        $this->setting = $app[Setting::class];
        $this->config = $this->app['config'];
        $this->activator = $app[ThemeActivatorInterface::class];
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

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function studlyName(): string
    {
        return Str::studly($this->name());
    }

    public function title()
    {
        return $this->get('title');
    }

    public function lowerName(): string
    {
        return strtolower($this->name());
    }

    /**
     * Get screenshot URL.
     *
     * @return string
     */
    public function thumbnailUrl(): string
    {
        $screenshotPath = base_path("themes/{$this->lowerName()}/assets/public/images/thumbnail.png");

        if (file_exists($screenshotPath)) {
            return asset("themes/{$this->lowerName()}/images/thumbnail.png");
        }

        return route('generate.thumbnail', [
            'text' => $this->name(),
            'width' => 360,
            'height' => 200,
        ]);
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

        return realpath($this->path) . '/' . ltrim("/{$path}", '/');
    }

    public function isActive(): bool
    {
        return $this->activator->isActive($this);
    }

    public function activate(): bool
    {
        $this->activator->activate($this);

        return true;
    }

    /**
     * Get required modules from theme.json
     *
     * @return array
     */
    public function getRequiredModules(): array
    {
        return $this->get('require', []);
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

    public function composer()
    {
        return $this->json('composer.json');
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
     * Get json contents from the cache, setting as needed.
     *
     * @param string|null $file
     *
     * @return Collection
     * @throws \Exception
     */
    public function json(string|null $file = null): Collection
    {
        if ($file === null) {
            $file = 'theme.json';
        }

        if (isset($this->json[$file])) {
            return $this->json[$file];
        }

        return $this->json[$file] = collect(json_decode(File::get($this->path($file)), true, 512, JSON_THROW_ON_ERROR));
    }

    public function toArray(): array
    {
        return $this->json()->toArray();
    }
}
