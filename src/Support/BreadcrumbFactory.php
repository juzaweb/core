<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Contracts\Support\Arrayable;
use Juzaweb\Core\Contracts\Breadcrumb;

class BreadcrumbFactory implements Breadcrumb, Arrayable
{
    protected array $items = [];

    public function add(string $title, string $url = null): void
    {
        $this->items[] = [
            'title' => $title,
            'url' => $url,
        ];
    }

    public function items(array $items): void
    {
        $this->items = $items;
    }

    public function addItems(array $items): void
    {
        $this->items = array_merge($this->items, $items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return $this->getItems();
    }
}
