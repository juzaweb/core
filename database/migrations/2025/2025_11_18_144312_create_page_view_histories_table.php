<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_view_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('viewable_id');
            $table->string('viewable_type');
            $table->uuid('viewer_id')->nullable()->index();
            $table->string('viewer_type');
            $table->datetimes();

            $table->index(['viewable_id', 'viewable_type']);
            $table->index(['viewer_id', 'viewer_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_view_histories');
    }
};
