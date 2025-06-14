<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Contracts\Setting as SettingContract;
use Juzaweb\Core\Models\Setting as SettingModel;

class SettingRepository implements SettingContract
{
    public function __construct(
        protected CacheManager $cache,
        protected GlobalData $globalData
    ) {
        //
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->configs()->get($key) ?? $this->settings()->get($key)['default'] ?? $default;
    }

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
