<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Console\Command;
use Juzaweb\Core\Facades\Module;
use Juzaweb\Core\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class LinkCommand extends Command
{
    use ModuleCommandTrait;

    protected $name = 'module:link';

    public function handle(): int
    {
        $module = Module::findOrFail($this->getModuleName());
        $target = $module->path('assets/public');
        $link = public_path('modules/' . $module->getLowerName());

        if (!file_exists($link)) {
            symlink($target, $link);
            $this->info("Symlink created: {$link} → {$target}");
        } else {
            $this->warn("Symlink already exists.");
        }

        return self::SUCCESS;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module name.'],
        ];
    }
}
