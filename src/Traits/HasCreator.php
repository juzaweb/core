<?php

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Juzaweb\Modules\Admin\Models\Guest;

/**
 * @mixin Model
 */
trait HasCreator
{
    public static function bootHasCreator(): void
    {
        static::saving(
            function ($model) {
                // Check if a user is authenticated
                if (Auth::check()) {
                    $model->creator()->associate(Auth::user());
                }

                if (($ip = client_ip()) && $ip != '127.0.0.1') {
                    $creator = Guest::firstOrCreate(
                        [
                            'ipv4' => client_ip(),
                        ],
                        [
                            'user_agent' => request()->userAgent(),
                        ]
                    );

                    $model->creator()->associate($creator);
                }
            }
        );
    }

    public function creator(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'created_type', 'created_by');
    }

    public function scopeWhereCreator(Builder $builder, $user = null): Builder
    {
        $user = $user ?: current_actor();

        return $builder->where('created_type', $user->getMorphClass())
            ->where('created_by', $user->id);
    }
}
