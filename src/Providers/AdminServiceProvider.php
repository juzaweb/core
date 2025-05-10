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
    }
}
