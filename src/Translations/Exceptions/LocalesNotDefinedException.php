<?php

namespace Juzaweb\Modules\Core\Translations\Exceptions;

class LocalesNotDefinedException extends \Exception
{
    public static function make(): self
    {
        return new self('Please make sure you have run `php artisan migrate` and that the locales configuration is defined in the `languages` table.');
    }
}
