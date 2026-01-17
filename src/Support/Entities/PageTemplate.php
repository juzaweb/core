<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Entities;

class PageTemplate
{
    public string $label;

    public array $blocks = [];

    public function __construct(public string $key, protected array $options = [])
    {
        $this->label = $options['label'] ?? title_from_key($key);
        $this->blocks = $options['blocks'] ?? [];
    }

    public function get(string $key, $default = null)
    {
        return $this->{$key} ?? $default;
    }
}
