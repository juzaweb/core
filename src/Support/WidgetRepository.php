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
use Juzaweb\Modules\Core\Contracts\Widget;
use Juzaweb\Modules\Core\Support\Entities\Widget as WidgetEntity;

class WidgetRepository implements Widget
{
    protected array $widgets = [];

    public function make(string $key, callable $callback): void
    {
        $this->widgets[$key] = $callback;
    }

    public function get(string $key): null|WidgetEntity
    {
        $widget = data_get($this->widgets, $key);

        if ($widget) {
            return new WidgetEntity($key, $widget());
        }

        return null;
    }

    public function all(): Collection
    {
        return collect($this->widgets)->map(
            function ($callback, $key) {
                return new WidgetEntity($key, $callback());
            }
        );
    }
}
