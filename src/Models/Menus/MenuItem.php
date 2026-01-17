<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models\Menus;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\Translatable;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable as TranslatableContract;

class MenuItem extends Model implements TranslatableContract
{
    use HasUuids, Translatable, UsedInFrontend;

    public $timestamps = false;

    protected $table = 'menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'menuable_type',
        'menuable_id',
        'link',
        'icon',
        'target',
        'display_order',
        'box_key',
    ];

    public $translatedAttributes = [
        'label',
        'locale',
    ];

    protected $appends = [
        'menuable_class_name',
        'edit_url',
        'element_data',
        'is_custom',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->orderBy('display_order');
    }

    public function menuable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeWhereRoot(Builder $builder): Builder
    {
        return $builder->whereNull('parent_id');
    }

    public function scopeWithAllChildren(
        Builder $builder,
        ?string $locale = null,
        array $with = [],
        bool $cache = false
    ): Builder {
        return $builder->with([
            'children' => function ($q) use ($locale, $with, $cache) {
                if ($cache) {
                    $q->cacheFor(3600);
                }

                $q->with($with);
                $q->withTranslation($locale);
                $q->withAllChildren($locale);
                $q->orderBy('display_order');
            }
        ]);
    }

    public function scopeWhereInFrontend(Builder $builder, bool $cache): Builder
    {
        return $builder->withTranslation(null, null, $cache)
            ->with(['menuable'])
            ->withAllChildren(
                with: ['menuable'],
                cache: $cache
            )
            ->orderBy('display_order');
    }

    public function getMenuableClassNameAttribute(): ?string
    {
        if (empty($this->menuable_type)) {
            return null;
        }

        return class_basename($this->menuable_type);
    }

    public function getEditUrlAttribute(): ?string
    {
        if (empty($this->menuable_type) || empty($this->menuable_id)) {
            return null;
        }

        if (!class_exists($this->menuable_type) || !$this->menuable) {
            return null;
        }

        if (!method_exists($this->menuable, 'getEditUrl')) {
            return null;
        }

        return $this->menuable->getEditUrl();
    }

    public function getIsCustomAttribute(): bool
    {
        return empty($this->menuable_type) && empty($this->menuable_id);
    }

    public function getElementDataAttribute(): string
    {
        if ($this->is_custom) {
            return implode(
                ' ',
                [
                    'data-id="'.e($this->id).'"',
                    'data-label="'.e($this->label).'"',
                    'data-link="'.e($this->link).'"',
                    'data-target="'.e($this->target).'"',
                ]
            );
        }

        return implode(
            ' ',
            [
                'data-id="'.e($this->id).'"',
                'data-key="'.e($this->box_key).'"',
                'data-label="'.e($this->label).'"',
                'data-link="'.e($this->link).'"',
                'data-target="'.e($this->target).'"',
                'data-menuable_type="'.e($this->menuable_type).'"',
                'data-menuable_id="'.e($this->menuable_id).'"',
            ]
        );
    }

    public function getUrl(): ?string
    {
        if ($this->is_custom) {
            return $this->link;
        }

        if (!class_exists($this->menuable_type) || !$this->menuable) {
            return null;
        }

        if (!method_exists($this->menuable, 'getUrl')) {
            return null;
        }

        return $this->menuable->getUrl();
    }
}
