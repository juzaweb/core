<?php

/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @author     The Anh Dang
 *
 * @link       https://larabiz.com
 */

use Juzaweb\Modules\Admin\Models\User;

if (! function_exists('model_of_guard')) {
    function model_of_guard(string $guard): ?string
    {
        return collect(config('auth.guards'))
            ->map(function ($guard) {
                if (! isset($guard['provider'])) {
                    return;
                }

                return config("auth.providers.{$guard['provider']}.model");
            })->get($guard);
    }
}

if (! function_exists('has_permission')) {
    function has_permission(?User $user = null): bool
    {
        if ($user === null) {
            $user = auth()->user();
        }

        if ($user === null) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission();
    }
}
