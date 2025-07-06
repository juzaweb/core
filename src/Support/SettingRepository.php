<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Contracts\Setting as SettingContract;
use Juzaweb\Core\Models\Setting as SettingModel;
use Juzaweb\Core\Support\Entities\Setting;

class SettingRepository implements SettingContract
{
    public function __construct(
        protected CacheManager $cache,
        protected GlobalData $globalData
    ) {
        //
    }

    public function make(string $key): Setting
    {
        return app(Setting::class, ['key' => $key]);
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
        return SettingModel::updateOrCreate(
            [
                'code' => $key,
            ],
            [
                'value' => $value,
            ]
        );
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
            fn ($setting) => $configs[$setting['key']] ?? $setting['default'] ?? null
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
        return $this->globalData->collect('settings');
    }

    public function configs(): Collection
    {
        return SettingModel::cacheForever()->get(
            [
                'code',
                'value',
            ]
        )->pluck('value', 'code');
    }
}
