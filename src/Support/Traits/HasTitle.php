<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Traits;

trait HasTitle
{
    protected string $title;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(?string $key = null): string
    {
        if (! isset($this->title)) {
            $title = title_from_key($key ?? $this->key);

            $this->title = __($title);
        }

        return __($this->title);
    }
}
