<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\DataTables;

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
        return static::make(__('Edit'), $url, 'fas fa-edit');
    }

    /**
     * Get an instance of the delete action.
     *
     * @return static
     */
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
}
