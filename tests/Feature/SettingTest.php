<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Juzaweb\Modules\Core\Contracts\GlobalData;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class SettingTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
        ]);

        // Register 'title' setting to allow validation to pass
        $this->app[GlobalData::class]->set('settings.title', [
            'label' => 'Site Title',
            'rules' => ['nullable', 'string', 'max:250'],
            'default' => 'Juzaweb CMS',
        ]);

        config()->set('translatable.fallback_locale', 'en');
    }

    public function test_index_page_loads()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.settings.general'));

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.setting.index');
    }

    public function test_index_redirects_guests()
    {
        Auth::logout();
        $response = $this->get(route('admin.settings.general'));
        $response->assertStatus(302);
    }

    public function test_update_settings()
    {
        $this->actingAs($this->user);

        $data = ['title' => 'New Site Title'];

        $response = $this->putJson(route('admin.settings.update'), $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('settings', [
            'code' => 'title',
            'value' => 'New Site Title',
        ]);
    }

    public function test_update_settings_unauthenticated()
    {
        Auth::logout();

        $data = ['title' => 'New Site Title'];

        $response = $this->putJson(route('admin.settings.update'), $data);

        $response->assertStatus(401);
    }
}
