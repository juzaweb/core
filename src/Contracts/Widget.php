<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Support\Entities\Widget as WidgetEntity;

interface Widget
{
    public function make(string $key, callable $callback): void;

    public function get(string $key): null|WidgetEntity;

    public function all(): Collection;
}
