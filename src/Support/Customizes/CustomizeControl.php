<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Customizes;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CustomizeControl
{
    /**
     * @var Customize
     */
    protected Customize $customize;

    protected string $key;

    /**
     * @var Collection
     */
    protected Collection $args;

    public function __construct(Customize $customize, string $key, array $args = [])
    {
        $this->customize = $customize;
        $this->key = $key;
        $this->args = collect($args);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getArgs(): Collection
    {
        return $this->args;
    }

    public function contentTemplate(): Factory|View
    {
        return match ($this->args->get('type')) {
            'text' => view('core::admin.customize.control.text', [
                'args' => $this->args,
                'key' => $this->key,
            ]),
            'textarea' => view('core::admin.customize.control.textarea', [
                'args' => $this->args,
                'key' => $this->key,
            ]),
            'site_identity' => view('core::admin.customize.control.site-identity', [
                'args' => $this->args,
                'key' => $this->key,
            ]),
            default => '',
        };
    }
}
