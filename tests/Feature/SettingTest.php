<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
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
        $this->app[\Juzaweb\Modules\Core\Contracts\GlobalData::class]->set('settings.title', [
            'label' => 'Site Title',
            'rules' => ['nullable', 'string', 'max:250'],
            'default' => 'Juzaweb CMS',
        ]);

        config()->set('translatable.fallback_locale', 'en');
    }

    public function testIndexPageLoads()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.settings.general'));

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.setting.index');
    }

    public function testIndexRedirectsGuests()
    {
        Auth::logout();
        $response = $this->get(route('admin.settings.general'));
        $response->assertStatus(302);
    }

    public function testUpdateSettings()
    {
        $this->actingAs($this->user);

        $data = ['title' => 'New Site Title'];

        $response = $this->putJson(route('admin.settings.update'), $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('settings', [
            'code' => 'title',
            'value' => 'New Site Title'
        ]);
    }

    public function testUpdateSettingsUnauthenticated()
    {
        Auth::logout();

        $data = ['title' => 'New Site Title'];

        $response = $this->putJson(route('admin.settings.update'), $data);

        $response->assertStatus(401);
    }
}
