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
use Juzaweb\Modules\Core\Contracts\PageBlock;

class PageBlockRepository implements PageBlock
{
    protected array $blocks = [];

    public function make(string $key, callable $callback): void
    {
        $this->blocks[$key] = $callback;
    }

    public function get(string $key): ?\Juzaweb\Modules\Core\Support\Entities\PageBlock
    {
        if ($template = data_get($this->blocks, $key)) {
            return new \Juzaweb\Modules\Core\Support\Entities\PageBlock($key, $template());
        }

        return null;
    }

    public function all(): Collection
    {
        return collect($this->blocks)->map(
            function ($callback, $key) {
                return new \Juzaweb\Modules\Core\Support\Entities\PageBlock($key, $callback());
            }
        );
    }
}
