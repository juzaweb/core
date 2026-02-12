<?php

namespace Juzaweb\Modules\Core\Modules\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("dev-tool.modules.paths.generator.$value"));
    }
}
