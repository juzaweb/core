<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Juzaweb\Modules\Core\Tests\TestCase;

class AddonControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \Juzaweb\Modules\Core\Translations\Models\Language::updateOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'default' => true,
            ]
        );

        $this->app['config']->set('translatable.fallback_locale', 'en');
        $this->app[\Juzaweb\Modules\Core\Contracts\Setting::class]->set('language', 'en');
        app()->setLocale('en');
    }

    public function test_statuses_handles_redis_failure()
    {
        // Mock Redis to throw an exception
        Redis::shouldReceive('zadd')
            ->andThrow(new \Exception('Redis connection failed'));

        // We don't strictly need zremrangebyscore expectation if zadd throws first,
        // but let's be safe or just mocking zadd is enough to trigger failure.

        $response = $this->post('online/statuses');

        // We expect the application to handle it and return 200 OK
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
