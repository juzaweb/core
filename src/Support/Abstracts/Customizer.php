<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Abstracts;

use Juzaweb\Modules\Core\Traits\Whenable;

abstract class Customizer
{
    use Whenable;

    protected bool $added = false;

    abstract public function add();

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
