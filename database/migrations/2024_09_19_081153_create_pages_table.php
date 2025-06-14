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
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status', 20)->index()->default('published');
            $table->string('template', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 190)->unique();
            $table->longText('content')->nullable();
            $table->string('locale', 10)->index();
            $table->uuid('page_id');

            $table->unique(['page_id', 'locale']);
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
