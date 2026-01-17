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
use Juzaweb\Modules\Core\Models\Pages\PageBlock as PageBlockModel;

class PageBlock
{
    public string $label;

    public string $form;

    public string $view;

    public function __construct(public string $key, protected array $options = [])
    {
        $this->label = $options['label'] ?? title_from_key($key);
        $this->form = $options['form'];
        $this->view = $options['view'];
    }

    public function get(string $key, $default = null)
    {
        return $this->{$key} ?? $default;
    }

    public function form(array $data = []): View
    {
        $params = [
            'name' => $data['name'],
            'data' => $data,
        ];

        return view($this->form, $params);
    }

    public function view(PageBlockModel $block): View
    {
        return view($this->view, ['block' => $block]);
    }
}
