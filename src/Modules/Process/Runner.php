<?php

namespace Juzaweb\Modules\Core\Modules\Process;

use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Contracts\RunableInterface;
use Symfony\Component\Process\Process;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     * @var RepositoryInterface
     */
    protected $module;

    public function __construct(RepositoryInterface $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600);
        $process->run(function ($type, $line) {
            echo $line;
        });
    }
}
