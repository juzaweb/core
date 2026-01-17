<?php

namespace Juzaweb\Modules\Core\Models\Menus;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\Modules\Admin\Database\Factories\MenuFactory;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;

class Menu extends Model
{
    use HasAPI, HasFactory, HasUuids, UsedInFrontend;

    protected $table = 'menus';

    protected $fillable = [
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id');
    }

    public function scopeWhereInFrontend(Builder $builder, bool $cache): Builder
    {
        return $builder->with(
            [
                'items' => fn($q) => $q
                    ->whereRoot()
                    ->whereFrontend(),
            ]
        );
    }

    public function scopeWithDataItems(Builder $builder, ?string $locale = null): Builder
    {
        return $builder->with(
            [
                'items' => fn($q) => $q->withTranslation($locale)
                    ->with(['menuable'])
                    ->whereRoot()
                    ->withAllChildren($locale, ['menuable'])
            ]
        );
    }

    protected static function newFactory(): MenuFactory
    {
        return MenuFactory::new();
    }
}
