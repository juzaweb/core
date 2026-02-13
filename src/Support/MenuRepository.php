<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\Menu as MenuContract;

class MenuRepository implements MenuContract
{
    protected array $menus = [];

    public function make(string $key, callable $callback): void
    {
        $this->menus[$key] = $callback;
    }

    public function get(string $key): ?array
    {
        if ($template = data_get($this->menus, $key)) {
            return $template();
        }

        return null;
    }

    public function getByPosition(string $position): Collection
    {
        $menus = collect($this->menus)->map(
            function ($callback, $key) {
                $data = $callback();

                if (!$data) {
                    return null;
                }

                // Merge key vào data
                $data['key'] = $key;
                $prefix = $data['prefix'] ?? 'admin';
            
                // Build URL nếu chưa có
                if (!isset($data['url'])) {
                    if ($key === 'dashboard') {
                        $data['url'] = url($prefix ?: '');
                    } else {
                        $data['url'] = url($prefix ? "{$prefix}/{$key}" : $key);
                    }
                } else {
                    $data['url'] = isset($data['prefix']) ? url($data['prefix'] .'/'. ltrim($data['url'], '/')) : admin_url($data['url']);
                }

                // Set default values
                $data['target'] = $data['target'] ?? '_self';
                $data['icon'] = $data['icon'] ?? 'fa fa-circle';
                $data['priority'] = $data['priority'] ?? 20;
                $data['parent'] = $data['parent'] ?? null;
                $data['position'] = $data['position'] ?? null;
                $data['permission'] = $data['permission'] ?? ['super-admin'];

                if (! is_array($data['permission'])) {
                    $data['permission'] = [$data['permission']];
                }

                return $data;
            }
        )
            ->filter()
            ->filter(
                function ($menu) use ($position) {
                    return ($menu['position'] ?? 'admin-left') === $position;
                }
            );

        return $menus->values();
    }

    public function all(): Collection
    {
        return collect($this->menus)->map(
            function ($callback, $key) {
                return $callback();
            }
        );
    }
}
