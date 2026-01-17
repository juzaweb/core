<?php

declare(strict_types=1);

namespace Juzaweb\Modules\Core\Services;

use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    /**
     * Execute a Closure within a transaction.
     *
     * @param callable $callback
     * @return mixed
     */
    protected function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }

    protected function result(bool $status, mixed $data = null, string $message = ''): object
    {
        return (object) [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];
    }
}
