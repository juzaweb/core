<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Models\Setting as SettingModel;

/**
 * Interface Setting
 * @package App\Contracts
 * @see \Juzaweb\Modules\Core\Support\SettingRepository
 */
interface Setting
{
    /**
     * Creates a new setting instance with the given key and optional label.
     *
     * @param  string  $key  The key for the setting.
     * @return \Juzaweb\Modules\Core\Support\Entities\Setting
     */
    public function make(string $key): \Juzaweb\Modules\Core\Support\Entities\Setting;

    /**
     * Retrieves the value of a configuration key.
     *
     * @param  string  $key  The configuration key to retrieve.
     * @param  string|array|null  $default  The default value to return if the key is not found.
     * @return null|string|array The value of the configuration key.
     */
    public function get(string $key, mixed $default = null): mixed;

    public function boolean(string $key, mixed $default = null);

    public function integer(string $key, mixed $default = null): ?int;

    public function float(string $key, mixed $default = null): ?float;

    /**
     * Sets a configuration value for the application.
     *
     * @param  string  $key  The key of the configuration.
     * @param  string|array|null  $value  The value of the configuration.
     * @return SettingModel The updated or created ConfigModel instance.
     */
    public function set(string $key, mixed $value = null): SettingModel;

    public function sets(array $keys): Collection;

    /**
     * Retrieves the configuration values for the given keys and returns them in an array.
     *
     * @param  array  $keys  The keys for which the configuration values are to be retrieved.
     * @param  mixed  $default  The default value to be used if a configuration value is not found for a key.
     * Defaults to null.
     * @return array The configuration values for the given keys in an array.
     */
    public function gets(array $keys, mixed $default = null): array;

    /**
     * Retrieves all the values from the configs and applies a transformation to each value.
     *
     * @return Collection A collection containing the transformed values.
     */
    public function all(): Collection;

    /**
     * Generate a new collection with the keys of the original collection.
     *
     * @param array|null $keys The keys to include in the new collection
     * @return Collection
     */
    public function keys(?array $keys = null): Collection;

    public function settings(): Collection;

    public function configs(): Collection;
}
