<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\GlobalData;
use Juzaweb\Modules\Core\Contracts\Theme;
use Juzaweb\Modules\Core\Contracts\ThemeSetting as SettingContract;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Models\ThemeSetting as SettingModel;
use Juzaweb\Modules\Core\Support\Entities\ThemeSetting;
use function Juzaweb\Modules\Admin\Support\website_id;

class ThemeSettingRepository implements SettingContract
{
    public function __construct(
        protected CacheManager $cache,
        protected GlobalData $globalData,
        protected Theme $theme
    ) {
        //
    }

    public function make(string $key): ThemeSetting
    {
        return app(ThemeSetting::class, ['key' => $key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->configs()->get($key) ?? $this->settings()->get($key)['default'] ?? $default;
    }

    public function boolean(string $key, mixed $default = null)
    {
        $value = $this->get($key, $default);

        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function integer(string $key, mixed $default = null): ?int
    {
        $value = $this->get($key, $default);

        if ($value === null) {
            return null;
        }

        return (int) $value;
    }

    public function float(string $key, mixed $default = null): ?float
    {
        $value = $this->get($key, $default);

        if ($value === null) {
            return null;
        }

        return (float) $value;
    }

    /**
     * Sets a configuration value for the application.
     *
     * @param  string  $key  The key of the configuration.
     * @param  mixed  $value  The value of the configuration.
     * @return SettingModel The updated or created ConfigModel instance.
     */
    public function set(string $key, mixed $value = null): SettingModel
    {
        $model = Model::withoutEvents(
            function () use ($key, $value) {
                return SettingModel::withoutGlobalScope('website_id')->updateOrCreate(
                    [
                        'code' => $key,
                        'theme' => $this->theme->current()->name(),
                        'website_id' => website_id(),
                    ],
                    [
                        'value' => $value,
                    ]
                );
            });

        SettingModel::flushQueryCache();

        return $model;
    }

    public function sets(array $keys): Collection
    {
        foreach ($keys as $key => $value) {
            $this->set($key, $value);
        }

        return $this->configs()->only(array_keys($keys));
    }

    public function gets(array $keys, mixed $default = null): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->get($key, $default);
        }

        return $data;
    }

    public function all(): Collection
    {
        $configs = $this->configs();

        return $this->settings()->map(
            fn($setting) => $configs[$setting['key']] ?? $setting['default'] ?? null
        );
    }

    public function keys(?array $keys = null): Collection
    {
        if (is_null($keys)) {
            return $this->settings()->keys();
        }

        return $this->settings()->only($keys)->keys();
    }

    public function settings(?string $key = null): Collection
    {
        return $this->globalData->collect('theme_settings');
    }

    public function configs(): Collection
    {
        return SettingModel::cacheFor(3600)
            ->withoutGlobalScope('website_id')
            ->where('website_id', website_id())
            ->where('theme', $this->theme->current()->name())
            ->get(['code', 'value'])
            ->pluck('value', 'code');
    }
}
