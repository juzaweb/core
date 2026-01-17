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

use Illuminate\View\View;
use Juzaweb\Modules\Core\Models\ThemeSidebar;

class Widget
{
    public string $label;

    public ?string $description = null;

    public ?string $form = null;

    public ?string $view = null;

    public function __construct(protected string $key, protected array $options = [])
    {
        $this->label = $options['label'] ?? title_from_key($key);
        $this->description = $options['description'] ?? null;
        $this->form = data_get($options, 'form');
        $this->view = data_get($options, 'view');
    }

    public function get(string $key, $default = null)
    {
        return $this->{$key} ?? $default;
    }

    public function form(array $data = []): View|string
    {
        if (! $this->form) {
            return '{form}';
        }

        return view($this->form, compact('data'));
    }

    public function view(ThemeSidebar $sidebar): View|string
    {
        return view($this->view, ['widget' => $this, 'sidebar' => $sidebar]);
    }
}
