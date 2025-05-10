<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Providers;

use Juzaweb\Core\Facades\Menu;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Menu::make('dashboard', 'Dashboard')->icon('fa-tachometer-alt');

        Menu::make('settings', 'Settings')
            ->icon('fa-cogs')
            ->priority(99);

        Menu::make('general', 'General')
            ->url('settings')
            ->parent('settings');

        Menu::make('roles', 'Roles')
            ->parent('settings');

        Menu::make('users', 'Users')
            ->parent('settings');
    }
}
