<?php

use App\Models\User;

if (! function_exists('model_of_guard')) {
    /**
     * @param string $guard
     *
     * @return string|null
     */
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

if (!function_exists('has_permission')) {
    /**
     * @param User|null $user
     * @return bool
     */
    function has_permission(User|null $user = null): bool
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
