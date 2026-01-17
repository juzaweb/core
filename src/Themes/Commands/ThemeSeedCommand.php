<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Facades\Theme;
use Symfony\Component\Console\Input\InputArgument;
use function Juzaweb\Modules\Admin\Themes\Commands\set_website;

class ThemeSeedCommand extends Command
{
    protected $name = 'theme:seed';

    protected $description = 'Seed the theme data';

    public function handle(): int
    {
        $theme = Theme::find($this->argument('theme'));
        if (!$theme) {
            $this->error('Theme not found.');
            return Command::FAILURE;
        }

        $seederClass = "Juzaweb\\Themes\\{$theme->studlyName()}\\Database\\Seeders\\DatabaseSeeder";

        if (!class_exists($seederClass)) {
            $this->error("Seeder not found for theme: {$theme->studlyName()}");
            return Command::FAILURE;
        }

        app($seederClass)->run();

        $this->info('Theme data seeded successfully.');

        return Command::SUCCESS;
    }

    protected function getArguments()
    {
        return [
            ['theme', InputArgument::REQUIRED, 'The theme to seed data for.'],
            ['website', InputArgument::REQUIRED, 'The website ID to seed data for.'],
        ];
    }
}
