<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Core\Models\Setting as SettingModel;

/**
 * Interface Setting
 * @package Juzaweb\Core\Contracts
 * @see \Juzaweb\Core\Support\SettingRepository
 */
interface Setting
{
    /**
     * Retrieves the value of a configuration key.
     *
     * @param  string  $key  The configuration key to retrieve.
     * @param  string|array|null  $default  The default value to return if the key is not found.
     * @return null|string|array The value of the configuration key.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Sets a configuration value for the application.
     *
     * @param  string  $key  The key of the configuration.
     * @param  string|array|null  $value  The value of the configuration.
     * @return SettingModel The updated or created ConfigModel instance.
     */
    public function set(string $key, mixed $value = null): SettingModel;

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
