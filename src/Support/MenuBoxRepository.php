<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\GlobalData;
use Juzaweb\Modules\Core\Contracts\MenuBox;

class MenuBoxRepository implements MenuBox
{
    public function __construct(
        protected GlobalData $globalData
    ) {
    }

    public function make(string $key, string $class, callable $options): void
    {
        $this->globalData->set(
            "menu_boxes.{$key}",
            [
                'class' => $class,
                'options' => $options,
            ]
        );
    }

    public function get(string $position): array
    {
        return $this->globalData->get("menu_boxes.{$position}");
    }

    public function all(): Collection
    {
        return collect($this->globalData->get('menu_boxes', []))->sort(
            fn ($a, $b) => ($a['options']()['priority'] ?? 99) <=> ($b['options']()['priority'] ?? 99)
        );
    }
}
