<?php

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogCommand extends Command
{
    protected $signature = 'log:clear';

    protected $description = 'Clear all Laravel log files';

    public function handle(): int
    {
        $logPath = storage_path('logs');

        if (! File::exists($logPath)) {
            $this->error('Log directory does not exist.');
            return Command::FAILURE;
        }

        $files = File::files($logPath);

        foreach ($files as $file) {
            File::delete($file->getPathname());
        }

        $this->info('Laravel logs have been cleared successfully.');

        return Command::SUCCESS;
    }
}
