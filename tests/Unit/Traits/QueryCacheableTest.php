<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Traits\QueryCacheable;

class QueryCacheableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('query_cacheable_test_models');

        Schema::create('query_cacheable_test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('query_cacheable_test_models');
        parent::tearDown();
    }

    public function test_cache_works_with_file_driver()
    {
        Config::set('cache.default', 'file');
        Cache::flush();

        $model = QueryCacheableTestModel::create(['name' => 'Original']);

        // Cache the query for 10 seconds
        $result1 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('Original', $result1->name);

        // Update database directly to bypass model events and cache clearing
        DB::table('query_cacheable_test_models')
            ->where('id', $model->id)
            ->update(['name' => 'Updated']);

        // Should still return cached value
        $result2 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('Original', $result2->name);

        // Flush cache
        Cache::flush();

        // Should return new value
        $result3 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('Updated', $result3->name);
    }

    public function test_cache_works_with_redis_driver()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension not loaded.');
        }

        try {
            // Attempt a simple connection check
            // If redis is configured but not running, this might fail immediately or timeout
            // We'll set the config and try.
            Config::set('cache.default', 'redis');

            // If we are in a testing environment without redis setup, we might want to skip.
            // But let's assume the user wants us to try.
            Cache::store('redis')->flush();
        } catch (\Exception $e) {
            $this->markTestSkipped('Could not connect to Redis: ' . $e->getMessage());
        }

        $model = QueryCacheableTestModel::create(['name' => 'RedisOriginal']);

        // Cache the query
        $result1 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('RedisOriginal', $result1->name);

        // Update DB directly
        DB::table('query_cacheable_test_models')
            ->where('id', $model->id)
            ->update(['name' => 'RedisUpdated']);

        // Should be cached
        $result2 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('RedisOriginal', $result2->name);

        // Clear cache
        Cache::store('redis')->flush();

        // Should be updated
        $result3 = QueryCacheableTestModel::cacheFor(10)->find($model->id);
        $this->assertEquals('RedisUpdated', $result3->name);
    }

    public function test_scope_one_query_with_cache_uses_tags()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension not loaded.');
        }

        try {
            Config::set('cache.default', 'redis');
            Cache::store('redis')->flush();
        } catch (\Exception $e) {
            $this->markTestSkipped('Could not connect to Redis: ' . $e->getMessage());
        }

        $model = QueryCacheableTestModel::create(['name' => 'Tagged']);

        // Use the scope which applies tags
        $result = QueryCacheableTestModel::query()->oneQueryWithCache($model->id)->find($model->id);

        $this->assertNotNull($result);
        $this->assertEquals('Tagged', $result->name);
    }

    public function test_file_driver_handles_tags_gracefully()
    {
        Config::set('cache.default', 'file');

        $model = QueryCacheableTestModel::create(['name' => 'FileTags']);

        // It seems the library or environment handles tags gracefully on file driver (ignores them or doesn't crash)
        // We verify that it returns the result correctly.
        $result = QueryCacheableTestModel::query()
            ->oneQueryWithCache($model->id)
            ->cacheFor(10)
            ->find($model->id);

        $this->assertNotNull($result);
        $this->assertEquals('FileTags', $result->name);
    }

    public function test_get_cache_tags_to_invalidate_on_update()
    {
        $model = new QueryCacheableTestModel();
        $model->id = 1;

        $tags = $model->getCacheTagsToInvalidateOnUpdate();

        $this->assertIsArray($tags);
        $this->assertContains('query_cacheable_test_models:1', $tags);
        // Base tags are usually the table name
        $this->assertContains('query_cacheable_test_models', $tags);
    }
}

class QueryCacheableTestModel extends Model
{
    use QueryCacheable;

    protected $table = 'query_cacheable_test_models';
    protected $guarded = [];
}
