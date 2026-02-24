<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Providers\AdminServiceProvider;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Support\Facades\View;

class LogViewerTestAdminServiceProvider extends AdminServiceProvider
{
    // Concrete implementation
}

class LogViewerMenuTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            LogViewerTestAdminServiceProvider::class,
        ]);
    }

    public function test_log_viewer_menu_is_registered()
    {
        // This should fail before implementation
        $menu = Menu::get('log-viewer');
        $this->assertNotNull($menu, 'Log Viewer menu should be registered');
        $this->assertEquals('log-viewer', $menu['url']);
        // Verify priority is high (bottom)
        $this->assertGreaterThanOrEqual(99, $menu['priority']);
    }

    public function test_log_viewer_link_is_removed_from_navbar()
    {
        // Mock user as super admin to ensure the link would show up if it was there
        $user = new \Juzaweb\Modules\Core\Models\User();
        $user->fill([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret',
            'is_super_admin' => true,
        ]);
        $user->save();

        $this->actingAs($user);

        $view = View::make('core::layouts.components.navbar')->render();

        // This should fail before implementation (link exists)
        $this->assertStringNotContainsString(url('log-viewer'), $view);
        $this->assertStringNotContainsString('core::translation.log_view', $view);
    }
}
