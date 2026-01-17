<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\DataTables;

class Action
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
    protected string $type = 'url';

    /**
     * @var string
     */
    protected string $color = 'primary';

    /**
     * @var ?string
     */
    protected ?string $action = null;

    /**
     * @var bool
     */
    protected bool $disabled = false;

    /**
     * @var bool
     */
    protected bool $visible = true;

    /**
     * @var ?string
     */
    protected string $target = '_self';

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

    public static function edit(string $url): static
    {
        return static::link(__('admin::translation.edit'), $url, 'fas fa-edit');
    }

    public static function link(string $label, string $url, string $icon = 'fas fa-link'): static
    {
        return static::make($label, $url, $icon);
    }

    /**
     * Get an instance of the delete action.
     *
     * @return static
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
     * Set the label of the action.
     *
     * @param string $label
     *      The label used to represent the action.
     * @return static
     */
    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the URL of the action.
     *
     * @param string $url
     *      The URL used to link the action.
     * @return static
     */
    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
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
     * Set the color of the action.
     *
     * @param string $color
     *      The color used to represent the action.
     * @return static
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the action of the button.
     *
     * @param string $action
     *      The action used to trigger the action.
     *      If the type is `action`, this will be used as the action name.
     * @return static
     */
    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Set the icon for the action.
     *
     * @param string $icon
     *      The icon class used to represent the action visually.
     * @return static
     */
    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the disabled state of the action.
     *
     * @param bool $disabled
     *      If true, the action will be disabled.
     * @return static
     */
    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Set the visibility of the action.
     *
     * @param bool $visible
     *      If true, the action will be visible.
     * @return static
     */
    public function visible(bool $visible = true): static
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Check if the action is disabled.
     *
     * @return bool
     *      Returns true if the action is disabled, false otherwise.
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Check if the action is visible.
     *
     * @return bool
     *      Returns true if the action is visible, false otherwise.
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function can(string $permission): static
    {
        // This method can be used to check permissions for the action.
        $this->visible = auth()->user()->can($permission);
        // For now, it does nothing but can be extended in the future.
        return $this;
    }

    /**
     * Set the target attribute for the link.
     *
     * @param string $target
     *      The target attribute (e.g., '_blank', '_self').
     * @return static
     */
    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get the type of action.
     *
     * @return string
     *      The type of action, either `url` or `action`.
     */
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

    public function getTarget(): ?string
    {
        return $this->target;
    }
}
