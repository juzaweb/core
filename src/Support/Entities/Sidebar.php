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

class Sidebar
{
    public string $label;

    public ?string $description = null;

    public function __construct(protected string $key, protected array $options = [])
    {
        $this->label = $options['label'] ?? title_from_key($key);
        $this->description = $options['description'] ?? null;
    }

    public function get(string $key, $default = null)
    {
        return $this->{$key} ?? $default;
    }
}
