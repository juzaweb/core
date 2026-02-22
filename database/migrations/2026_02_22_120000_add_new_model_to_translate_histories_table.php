<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translate_histories', function (Blueprint $table) {
            $table->string('new_model_id', 50)->nullable()->index();
            $table->string('new_model_type')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translate_histories', function (Blueprint $table) {
            $table->dropIndex(['new_model_id']);
            $table->dropIndex(['new_model_type']);
            $table->dropColumn(['new_model_id', 'new_model_type']);
        });
    }
};
