<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Collection $metas
 */
trait HasMeta
{
    abstract public function metas(): HasMany;

    /**
     * Get meta value
     *
     * @param string $key
     * @param mixed $default
     * @return string|array
     */
    public function getMeta(string $key, mixed $default = null): string|array
    {
        return $this->metas->where('meta_key', $key)->first()?->meta_value ?? $default;
    }

    /**
     * Get all meta data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMetas(): Collection
    {
        return $this->metas;
    }

    /**
     * @param  Builder  $builder
     * @param  string  $key
     * @param  string|array|int|null  $value
     *
     * @return Builder
     */
    public function scopeWhereMeta(Builder $builder, string $key, string|array|int|null $value): Builder
    {
        return $builder->whereHas(
            'metas',
            function (Builder $q) use (
                $key,
                $value
            ) {
                $q->where('meta_key', '=', $key);
                if (is_array($value)) {
                    $q->whereIn('meta_value', $value);
                } else {
                    $q->where('meta_value', '=', $value);
                }
            }
        );
    }

    /**
     * A scope to filter the builder based on meta key and values.
     *
     * @param Builder $builder The query builder instance
     * @param string $key The meta key to filter by
     * @param array $values The array of meta values to filter by
     * @return Builder The modified query builder instance
     */
    public function scopeWhereMetaIn(Builder $builder, string $key, array $values): Builder
    {
        return $builder->whereHas(
            'metas',
            function (Builder $q) use (
                $key,
                $values
            ) {
                $q->where('meta_key', '=', $key);
                $q->whereIn('meta_value', $values);
            }
        );
    }

    /**
     * Set a meta value for the model.
     *
     * @param string $key The meta key to set
     * @param string|array|int|null $value The value to set
     * @return void
     */
    public function setMeta(string $key, string|array|int|null $value): void
    {
        $this->metas()->updateOrCreate(
            [
                'meta_key' => $key
            ],
            [
                'meta_value' => is_array($value) ? json_encode($value, JSON_THROW_ON_ERROR) : $value
            ]
        );
    }

    /**
     * Delete a meta value by its key.
     *
     * @param string $key The meta key to delete
     * @return bool
     */
    public function deleteMeta(string $key): bool
    {
        $this->metas()->where('meta_key', $key)->delete();

        return true;
    }

    /**
     * Delete multiple meta values by their keys.
     *
     * @param array $keys The array of meta keys to delete
     * @return bool
     */
    public function deleteMetas(array $keys): bool
    {
        $this->metas()->whereIn('meta_key', $keys)->delete();

        return true;
    }

    /**
     * Synchronize the given meta values with the existing ones.
     *
     * @param array $data The array of meta values to synchronize. The keys are the meta keys and the values are the meta values.
     * @return void
     */
    public function syncMetas(array $data = []): void
    {
        $this->syncMetasWithoutDetaching($data);

        $this->metas()->whereNotIn('meta_key', array_keys($data))->delete();
    }

    /**
     * Synchronize the given meta values with the existing ones without detaching the removed ones.
     *
     * @param array $data The array of meta values to synchronize. The keys are the meta keys and the values are the meta values.
     * @return void
     */
    public function syncMetasWithoutDetaching(array $data = []): void
    {
        foreach ($data as $key => $val) {
            $this->metas()->updateOrCreate(
                [
                    'meta_key' => $key,
                ],
                [
                    'meta_value' => is_array($val) ? json_encode($val, JSON_THROW_ON_ERROR) : $val,
                ]
            );
        }
    }
}
