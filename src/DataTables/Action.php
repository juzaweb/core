<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
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

    public static function edit(string $url): static
    {
        return new static(__('Edit'), $url, 'fas fa-edit');
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

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
