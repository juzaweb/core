<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\DataTables;

class BulkAction
{
    /**
     * @var ?string
     */
    protected ?string $icon;

    /**
     * @var ?string
     */
    protected ?string $url;

    /**
     * @var string
     */
    protected string $label;

    /**
     * @var string
     */
    protected string $type = 'action';

    /**
     * @var string
     */
    protected string $color = 'primary';

    protected ?string $action = null;

    /**
     * Create a new Action instance.
     *
     * @param string $label
     * @param string|null $url
     * @param string|null $icon
     * @return static
     */
    public static function make(string $label, ?string $url = null, ?string $icon = null): static
    {
        return new static($label, $url, $icon);
    }

    public static function delete(): static
    {
        return static::make(__('Delete'), null, 'fas fa-trash')
            ->type('action')
            ->action('delete')
            ->color('danger');
    }

    /**
     * Create a new Action instance.
     *
     * @param string $label
     * @param string|null $url
     * @param string|null $icon
     */
    public function __construct(string $label, ?string $url = null, ?string $icon = null)
    {
        $this->icon = $icon;
        $this->url = $url;
        $this->label = $label;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
