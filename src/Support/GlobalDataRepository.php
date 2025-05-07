<?php

namespace Juzaweb\Core\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Core\Contracts\GlobalData as GlobalDataContract;

class GlobalDataRepository implements GlobalDataContract
{
    protected array $values = [];

    public function set(string $key, array $value): void
    {
        Arr::set($this->values, $key, $value);
    }

    public function push($key, $value): void
    {
        $data = $this->get($key);
        $data[] = $value;
        $this->set($key, $data);
    }

    public function get(string $key, array $default = [])
    {
        return Arr::get($this->values, $key, $default);
    }

    public function collect(string $key, array $default = []): Collection
    {
        return new Collection($this->get($key, $default));
    }

    public function all(): Collection
    {
        return new Collection($this->values);
    }
}
