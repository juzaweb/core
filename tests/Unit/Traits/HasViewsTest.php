<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\Models\DailyView;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Models\PageViewHistory;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Traits\HasViews;

class HasViewsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('has_views_test_models');
        Schema::dropIfExists('daily_views');
        Schema::dropIfExists('page_view_histories');

        Schema::create('has_views_test_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('daily_views', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('viewable');
            $table->date('date');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('page_view_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('viewable_id');
            $table->string('viewable_type');
            $table->uuid('viewer_id')->nullable()->index();
            $table->string('viewer_type');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('has_views_test_models');
        Schema::dropIfExists('daily_views');
        Schema::dropIfExists('page_view_histories');
        parent::tearDown();
    }

    public function test_increment_views_creates_daily_views_and_history()
    {
        $model = HasViewsTestModel::create();
        $viewer = new \stdClass;
        $viewer->id = 123;

        // Mock get_class for the stdClass viewer, but incrementViews calls get_class($viewer)
        // so we need a real object. We can use the model itself as viewer for simplicity or another instance.
        $viewer = HasViewsTestModel::create();

        $ip = '127.0.0.1';

        $model->incrementViews($viewer, $ip);

        $this->assertEquals(1, $model->fresh()->views);

        $dailyView = DailyView::where('viewable_id', $model->id)
            ->where('viewable_type', HasViewsTestModel::class)
            ->first();

        $this->assertNotNull($dailyView);
        $this->assertEquals(1, $dailyView->views);
        $this->assertEquals(now()->toDateString(), $dailyView->date);

        $history = PageViewHistory::where('viewable_id', $model->id)
            ->where('viewable_type', HasViewsTestModel::class)
            ->first();

        $this->assertNotNull($history);
        $this->assertEquals($viewer->id, $history->viewer_id);
    }

    public function test_increment_views_debounces_same_ip()
    {
        Cache::flush();
        $model = HasViewsTestModel::create();
        $viewer = HasViewsTestModel::create();
        $ip = '127.0.0.1';

        $model->incrementViews($viewer, $ip);
        $model->incrementViews($viewer, $ip); // Should be ignored

        $this->assertEquals(1, $model->fresh()->views);

        $dailyView = DailyView::where('viewable_id', $model->id)->first();
        $this->assertEquals(1, $dailyView->views);
    }

    public function test_increment_views_increments_for_different_ips()
    {
        Cache::flush();
        $model = HasViewsTestModel::create();
        $viewer1 = HasViewsTestModel::create();
        $viewer2 = HasViewsTestModel::create(); // Different viewer to avoid history collision if logic depends on it

        $model->incrementViews($viewer1, '127.0.0.1');
        $model->incrementViews($viewer2, '127.0.0.2');

        $this->assertEquals(2, $model->fresh()->views);

        $dailyView = DailyView::where('viewable_id', $model->id)->first();
        $this->assertEquals(2, $dailyView->views);

        $this->assertEquals(2, PageViewHistory::count());
    }
}

class HasViewsTestModel extends Model
{
    use HasViews;

    protected $table = 'has_views_test_models';

    protected $guarded = [];
}
