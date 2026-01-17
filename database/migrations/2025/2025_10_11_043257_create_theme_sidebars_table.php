<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_sidebars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sidebar', 100)->index();
            $table->string('widget', 100)->index();
            $table->json('data')->nullable();
            $table->string('theme', 100)->index();
            $table->integer('display_order')->default(0);
            $table->datetimes();
        });

        Schema::create('theme_sidebar_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5)->index();
            $table->string('label')->nullable();
            $table->json('fields')->nullable();
            $table->uuid('theme_sidebar_id');

            $table->unique(['theme_sidebar_id', 'locale']);
            $table->foreign('theme_sidebar_id')
                ->references('id')
                ->on('theme_sidebars')
                ->onDelete('cascade');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_sidebar_translations');
        Schema::dropIfExists('theme_sidebars');
    }
};
