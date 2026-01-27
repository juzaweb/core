<?php

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
        Schema::create(
            'translations',
            function (Blueprint $table) {
                $table->id();
                $table->string('locale', 50)->index();
                $table->string('group', 50)->index();
                $table->string('namespace', 50)->index();
                $table->string('object_type', 50)->index();
                $table->string('object_key', 50)->index();
                $table->text('key');
                $table->text('value')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translations');
    }
};
