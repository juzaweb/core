<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     Larabiz Team <admin@larabiz.com>
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CacheSizeCommand extends Command
{
    protected $name = 'cache:size';

    public function handle(): void
    {
        $fileSize = 0;
        foreach (File::allFiles(storage_path('framework/cache')) as $file) {
            $fileSize += $file->getSize();
        }

        $this->info("Current cache site: " . format_size_units($fileSize));
    }
}
