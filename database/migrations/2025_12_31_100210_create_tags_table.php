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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->datetimes();
        });

        Schema::create('taggable', function (Blueprint $table) {
            $table->uuid('taggable_id');
            $table->string('taggable_type', 190);
            $table->foreignId('tag_id')
                ->constrained('tags')
                ->onDelete('cascade');

            $table->primary(['taggable_id', 'taggable_type', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taggable');
        Schema::dropIfExists('tags');
    }
};
