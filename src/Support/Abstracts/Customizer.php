<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Abstracts;

use Juzaweb\Core\Traits\Whenable;

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
