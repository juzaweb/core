<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    protected $signature = 'juzaweb:update';

    public function handle()
    {
        $cmd = 'cd ' . base_path() . ' && php composer.phar update juzaweb/*';
        $process = new Process($cmd);
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo "\n".$data;
            } else {
                echo "\n".$data;
            }
        }
    }
}