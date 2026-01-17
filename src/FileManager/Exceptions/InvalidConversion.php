<?php

namespace Juzaweb\Modules\Core\FileManager\Exceptions;

class InvalidConversion extends \Exception
{
    public static function doesNotExist(string $name): static
    {
        return new static("The conversion [{$name}] does not exist.");
    }
}
