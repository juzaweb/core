<?php

namespace Juzaweb\Modules\Core\Support;

use Symfony\Component\Process\Process;

class Composer
{
    public function update(string $path): void
    {
        $this->runCommand($path, ['update', '--no-dev', '--optimize-autoloader']);
    }

    public function install(string $path): void
    {
        $this->runCommand($path, ['install', '--no-dev', '--optimize-autoloader']);
    }

    protected function runCommand(string $path, array $command): void
    {
        // Find composer executable
        $composer = $this->findComposer();

        $command = array_merge($composer, $command);

        $process = new Process($command, $path);
        $process->setTimeout(600); // 10 minutes

        $process->run();

        if (!$process->isSuccessful()) {
             throw new \RuntimeException($process->getErrorOutput());
        }
    }

    protected function findComposer(): array
    {
        if (file_exists(base_path('composer.phar'))) {
            return ['php', base_path('composer.phar')];
        }

        return ['composer'];
    }
}
