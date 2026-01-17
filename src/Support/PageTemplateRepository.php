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
use Juzaweb\Modules\Core\Contracts\PageTemplate;

class PageTemplateRepository implements PageTemplate
{
    protected array $templates = [];

    public function make(string $key, callable $callback): void
    {
        $this->templates[$key] = $callback;
    }

    public function get(string $key): ?\Juzaweb\Modules\Core\Support\Entities\PageTemplate
    {
        if ($template = data_get($this->templates, $key)) {
            return new \Juzaweb\Modules\Core\Support\Entities\PageTemplate($key, $template());
        }

        return null;
    }

    public function all(): Collection
    {
        return collect($this->templates)->map(
            function ($callback, $key) {
                return new \Juzaweb\Modules\Core\Support\Entities\PageTemplate($key, $callback());
            }
        );
    }
}
