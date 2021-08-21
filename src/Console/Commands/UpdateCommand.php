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
use Illuminate\Support\Facades\Log;
use Juzaweb\Core\Models\UpdateProcess;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;

class UpdateCommand extends Command
{
    protected $signature = 'juzaweb:update';

    public function handle()
    {
        $processes = UpdateProcess::where('status', '=', 'pending')
            ->get();

        foreach ($processes as $process) {
            $process->update([
                'status' => 'processing'
            ]);

            try {
                switch ($process->type) {
                    case 'core':
                        $this->updateCore();
                }

                $process->delete();

            } catch (\Throwable $e) {
                Log::error($e);

                $process->update([
                    'status' => 'error',
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function updateCore()
    {
        $process = Process::fromShellCommandline(sprintf(
            'cd %s && php composer.phar update juzaweb/*',
            base_path()
        ));

        $process->setTimeout(3600);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        Artisan::call('migrate');
    }
}