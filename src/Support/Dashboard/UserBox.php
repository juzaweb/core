<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\Dashboard;

use Illuminate\Contracts\Support\Htmlable;
use Juzaweb\Core\Contracts\DashboardBox;
use Juzaweb\Core\Models\User;

class UserBox implements DashboardBox
{
    public function render(): Htmlable
    {
        return view('core::components.dashboard.box', ['box' => $this]);
    }

    public function getTitle(): string
    {
        return __('Total Users');
    }

    public function getIcon(): string
    {
        return 'fas fa-users';
    }

    public function getBackground(): string
    {
        return 'bg-warning';
    }

    public function getOrder(): int
    {
        return 999;
    }

    /**
     * Get the data for the dashboard box.
     *
     * @return int
     */
    public function getData(): int
    {
        return User::active()->cacheFor(3600)->count();
    }
}
