<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ModuleLinkCommand extends Command
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
