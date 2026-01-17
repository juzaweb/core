<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\DataTables;

use Illuminate\Support\Str;

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
    protected ?string $color = null;

    /**
     * @var ?string
     */
    protected ?string $action = null;

    /**
     * @var bool
     */
    protected bool $visible = true;

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

    /**
     * Create a delete action.
     *
     * This action is used to represent a deletion operation
     * with a trash icon and a danger color.
     *
     * @return static The configured delete action instance.
     */
    public static function delete(): static
    {
        return static::make(__('admin::translation.delete'), null, 'fas fa-trash')
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

    /**
     * Set the type of action.
     *
     * @param string $type
     *      Supported types are `url` and `action`.
     *      - `url`: The action will link to the given URL.
     *      - `action`: The action will trigger the given action.
     * @return static
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the color of the bulk action.
     *
     * @param string $color
     *      The color used to represent the bulk action visually.
     * @return static
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the action for the bulk action.
     *
     * @param string $action
     *      The action used to trigger the bulk action.
     * @return static
     */
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

    /**
     * Set the visibility of the bulk action.
     *
     * @param bool $visible
     *      Whether the bulk action should be visible or not.
     * @return static
     */
    public function visible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function can(string $permission): static
    {
        // This method can be used to check permissions if needed.
        // For now, it does nothing but can be extended later.
        return $this->visible(auth()->user()->can($permission));
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAction(): ?string
    {
        return $this->action ?? Str::slug(Str::lower($this->label));
    }

    public function getColor(): ?string
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

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
