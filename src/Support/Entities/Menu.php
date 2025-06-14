<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support\Entities;

use Illuminate\Support\Str;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Support\Abstracts\Customizer;
use Juzaweb\Core\Support\Actions;
use Juzaweb\Core\Support\Traits\HasPermission;
use Juzaweb\Core\Support\Traits\HasTitle;
use Juzaweb\Core\Traits\Fillable;
use Juzaweb\Core\Traits\Whenable;
use Juzaweb\Hooks\Contracts\Hook;

class Menu extends Customizer
{
    use Fillable, HasPermission, Whenable, HasTitle;

    protected int $priority = 20;

    protected string $position = 'admin-left';

    protected string $icon = 'fa-circle';

    protected ?string $parent = null;

    protected bool $external = false;

    protected bool $disabled = false;

    protected string $target = '_self';

    protected string $prefix = 'admin-cp';

    protected ?string $slug = null;

    protected ?string $url = null;

    public function __construct(
        protected GlobalData $globalData,
        protected Hook $hook,
        protected string $key,
        ?string $title = null
    ) {
        $this->title = $title;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function priority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function position(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function parent(string $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function external(bool $external = true): static
    {
        $this->external = $external;

        $this->target = $external ? '_blank' : '_self';

        return $this;
    }

    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function slug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function forClient(bool $forClient = true): static
    {
        $this->prefix = $forClient ? 'client' : 'admin-cp';

        return $this->noPermission();
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function add(): void
    {
        $this->hook->addAction(Actions::MENU_INIT, [$this, 'register'], $this->priority);
    }

    public function register(): void
    {
        $key = Str::replace('.', '-', $this->key);

        $this->globalData->set("menus.{$this->position}.{$key}", $this->toArray());
    }

    public function toArray(): array
    {
        $slug = Str::replace('.', '/', $this->slug ?? $this->key);

        if ($slug === 'dashboard') {
            $slug = '';
        }

        $url = rtrim($this->url ? "/{$this->prefix}/{$this->url}" : "/{$this->prefix}/{$slug}", '/');

        return [
            'key' => $this->key,
            'title' => $this->getTitle(),
            'icon' => Str::snake($this->icon),
            'external' => $this->external,
            'disabled' => $this->disabled,
            'parent' => $this->parent,
            'target' => $this->target,
            'position' => $this->position,
            'url' => $url,
            'permissions' => $this->getPermissions(),
            'priority' => $this->priority,
        ];
    }
}
