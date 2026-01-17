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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->websiteId();
            $table->datetimes();
        });

        Schema::create(
            'menu_items',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('menu_id');
                $table->uuid('parent_id')->index()->nullable();

                $table->string('box_key', 100);
                $table->string('menuable_type', 100)->index()->nullable();
                $table->string('menuable_id')->index()->nullable();
                $table->string('link')->nullable();
                $table->string('icon')->nullable();
                $table->string('target', 10)->default('_self');
                $table->integer('display_order')->index();
                $table->boolean('is_home')->default(false);

                $table->foreign('menu_id')
                    ->references('id')
                    ->on('menus')
                    ->onDelete('cascade');
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('menu_items')
                    ->onDelete('cascade');
            }
        );

        Schema::create(
            'menu_item_translations',
            function (Blueprint $table) {
                $table->id();
                $table->uuid('menu_item_id')->index();
                $table->string('locale', 10)->index();

                $table->string('label', 100);
                $table->unique(['menu_item_id', 'locale']);

                $table->foreign('menu_item_id')
                    ->references('id')
                    ->on('menu_items')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_translations');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};
