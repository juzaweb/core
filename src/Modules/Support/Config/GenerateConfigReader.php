<?php

namespace Juzaweb\Core\Modules\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("modules.paths.generator.$value"));
    }
}
