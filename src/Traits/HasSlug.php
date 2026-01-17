<?php

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Support\Str;
use function Juzaweb\Modules\Admin\Traits\website_id;

trait HasSlug
{
    /**
     * Boot the HasSlug trait for a model.
     *
     * When saving the model, if the slug is null, generate a slug using the generateSlug method.
     *
     * @return void
     */
    public static function bootHasSlug(): void
    {
        static::saving(
            function ($model) {
                if (! isset($model->slug)) {
                    $model->slug = $model->generateSlug();
                }
            }
        );
    }

    /**
     * Find a model by slug.
     *
     * @param  string  $slug
     * @param  array  $column
     * @return static|null
     */
    public static function findBySlug(string $slug, array $column = ['*']): ?self
    {
        return self::query()
            ->where('slug', '=', $slug)
            ->first($column);
    }

    /**
     * Find a model by slug or throw an exception.
     *
     * @param  string  $slug
     * @return static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findBySlugOrFail(string $slug): self
    {
        return self::query()
            ->where('slug', '=', $slug)
            ->firstOrFail();
    }

    /**
     * Get the display name for the model.
     *
     * If the fieldName property is empty, return either the name or title property.
     * Otherwise, return the value of the property specified by displayName.
     *
     * @return string|null The display name of the model, or null if not available.
     */
    public function getDisplayName(): ?string
    {
        if (empty($this->fieldName)) {
            return $this->name ?: $this->title;
        }

        return $this->{$this->displayName};
    }

    /**
     * Generate a unique slug for the model.
     *
     * If $string is empty, use the display name of the model, or the current value of the slug field.
     * Otherwise, use the given string.
     *
     * @param  string|null  $string The string to generate a slug from.
     * @return string The unique slug.
     */
    public function generateSlug(?string $string = null): string
    {
        if (empty($string)) {
            $string = request()?->input('slug') ?? $this->slug ?? $this->getDisplayName();
        }

        $baseSlug = Str::substr($string, 0, 70);
        $baseSlug = Str::slug($baseSlug);

        $i = 1;
        $slug = $baseSlug;
        do {
            $row = self::where('id', '!=', $this->id)
                ->where('slug', '=', $slug)
                ->where('website_id', '=', website_id())
                ->orderBy('slug', 'DESC')
                ->first(['slug']);

            if ($row) {
                $slug = $baseSlug . '-' . $i;
            }

            $i++;
        } while ($row);

        return $slug;
    }
}
