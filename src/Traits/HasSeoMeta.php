<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Models\SeoMeta;
use Juzaweb\Modules\Core\Observes\HasSeoMetaObserve;

/**
 * @property SeoMeta $seoMeta
 * @method static Builder|static withSeoMeta(?string $locale = null)
 * @mixin Model
 */
trait HasSeoMeta
{
    public static function bootHasSeoMeta(): void
    {
        static::observe(HasSeoMetaObserve::class);
    }

    abstract public function seoMetaFill(): array;

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(
            SeoMeta::class,
            'seometable',
            'seometable_type',
            'seometable_id',
            'id',
            'id'
        );
    }

    public function scopeWithSeoMeta(Builder $builder, ?string $locale = null): Builder
    {
        return $builder->with(
            ['seoMeta' => fn ($q) => $q->withTranslation($locale ?? $this->getDefaultLocale() ?? app()->getLocale())]
        );
    }

    /**
     * @param  string<'title', 'description', 'keywords', 'image'>  $key
     * @param  string|null  $locale
     * @return null|string
     */
    public function getSeoMeta(string $key, string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        return $this->seoMeta?->translate($locale)->{$key};
    }

    /**
     * @param  array|string<'title', 'description', 'keywords', 'image'>  $key
     * @param  string|null  $value
     * @param  string|null  $locale
     * @return void
     */
    public function setSeoMeta(array|string $key, ?string $value = null, string $locale = null): void
    {
        $locale = $locale ?? app()->getLocale();

        if (is_array($key)) {
            $this->seoMeta?->translateOrNew($locale)->fill($key)->save();
            return;
        }

        $this->seoMeta?->translateOrNew($locale)->fill([$key => $value])->save();
    }
}
