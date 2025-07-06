<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Support\Traits\HasRules;
use Juzaweb\Core\Traits\Whenable;

class Setting implements Arrayable
{
    use HasRules, Whenable;

    protected string $label;

    protected string $type = 'string';

    protected string|null|bool|array $default = null;

    protected bool $showApi = true;

    protected bool $added = false;

    public function __construct(
        protected GlobalData $globalData,
        protected string $key
    ) {
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function showApi(bool $show): static
    {
        $this->showApi = $show;

        return $this;
    }

    public function default(string|null|bool|array $value): static
    {
        $this->default = $value;

        return $this;
    }

    public function disableShowApi(): static
    {
        $this->showApi(false);

        return $this;
    }

    public function add(): void
    {
        $this->added = true;

        $this->globalData->set("settings.{$this->key}", $this->toArray());
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'show_api' => $this->showApi,
            'default' => $this->default,
            'type' => $this->type,
            'rules' => $this->getRules(),
        ];
    }

    public function withAdded(bool $added): static
    {
        $this->added = $added;

        return $this;
    }

    public function __destruct()
    {
        if (! $this->added) {
            $this->add();
        }
    }
}
