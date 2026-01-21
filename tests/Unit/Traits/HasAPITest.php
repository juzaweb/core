<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Traits;

use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HasAPITest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('test_models');

        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function test_api_scope_defaults()
    {
        $model = new TestModelWithApi();

        $query = $model->api([]);

        $this->assertNotNull($query);
        // By default, if scopeInApi is not present (or empty in trait), no specific where clause is added except what searchAndFilter/sort do.
        // We haven't passed params, so just basic select.
    }

    public function test_api_scope_calls_custom_in_api()
    {
        $model = new TestModelWithCustomApi();

        $query = $model->api([]);
        $sql = $query->toSql();

        // SQLite quotes columns with double quotes or backticks depending on version/config, but Laravel usually handles it.
        // We just check for 'name' and parameter binding or value.
        // The custom scope adds where('name', 'test').

        $this->assertStringContainsString('name', $sql);
        // In prepared statements, the value 'test' won't be in SQL string, it will be ?.
        // But the column name should be there.

        // To be sure the scope was applied, we can check bindings.
        $bindings = $query->getBindings();
        $this->assertContains('test', $bindings);
    }
}

class TestModelWithApi extends Model
{
    use HasAPI;
    protected $table = 'test_models';
    protected $guarded = [];
}

class TestModelWithCustomApi extends Model
{
    use HasAPI;
    protected $table = 'test_models';
    protected $guarded = [];

    public function scopeInApi($builder, $params = [])
    {
        return $builder->where('name', 'test');
    }
}
