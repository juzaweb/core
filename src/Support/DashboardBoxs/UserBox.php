<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\DashboardBoxs;

use Illuminate\Contracts\Support\Htmlable;
use Juzaweb\Core\Contracts\DashboardBox;
use Juzaweb\Core\Models\User;

class UserBox implements DashboardBox
{
    public function __construct()
    {
        // Initialization code if needed
    }

    public function render(): Htmlable
    {
        return view('core::components.dashboard.box', ['box' => $this]);
    }

    public function getTitle(): string
    {
        return 'Users';
    }

    public function getIcon(): string
    {
        return 'fa-users';
    }

    public function getBackground(): string
    {
        return 'bg-warning';
    }

    public function getData(): int
    {
        return User::active()->count();
    }
}
