<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Support\Fields\Currency;
use Juzaweb\Modules\Core\Support\Fields\Date;
use Juzaweb\Modules\Core\Support\Fields\Text;
use Juzaweb\Modules\Core\Support\Fields\Textarea;

/**
 * @see \Juzaweb\Modules\Core\Support\FieldFactory
 */
interface Field
{
    public function text(string|Model $label, string $name, array $options = []): Text;

    public function textarea(string|Model $label, string $name, array $options = []): Textarea;

    public function number(string|Model $label, string $name, array $options = []): Text;

    public function date(string|Model $label, string $name, array $options = []): Date;

    public function currency(string|Model $label, string $name, array $options = []): Currency;
}
