<?php

namespace Juzaweb\Modules\Core\Modules\Support\Config;

class GeneratorPath
{
    private string $path;
    private bool $generate;
    private string $namespace;
    protected array $options = [];

    public function __construct($config)
    {
        if (is_array($config)) {
            $this->path = $config['path'];
            $this->generate = $config['generate'];
            $this->namespace = $config['namespace'] ?? $this->convertPathToNamespace($config['path']);
            $this->options = $config;
            return;
        }

        $this->path = $config;
        $this->generate = (bool) $config;
        $this->namespace = $config;

        $this->options = [
            'path' => $this->path,
            'generate' => $this->generate,
            'namespace' => $this->namespace,
        ];
    }

    public function getPath()
    {
        return $this->path;
    }

    public function generate(): bool
    {
        return $this->generate;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function get(?string $key = null, $default = null)
    {
        return $this->getOption($key, $default);
    }

    public function getOption(?string $key = null, $default = null)
    {
        return data_get($this->options, $key, $default);
    }

    private function convertPathToNamespace($path): string
    {
        return str_replace('/', '\\', $path);
    }
}
