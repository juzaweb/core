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
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->string('seometable_type', 50);
            $table->string('seometable_id', 190);
            $table->timestamps();

            $table->unique(['seometable_type', 'seometable_id']);
        });

        Schema::create('seo_meta_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 190);
            $table->string('description', 250)->nullable();
            $table->string('keywords', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->string('locale', 10)->nullable();
            $table->unsignedBigInteger('seo_meta_id');
            $table->timestamps();

            $table->unique(['seo_meta_id', 'locale']);
            $table->foreign('seo_meta_id')
                ->references('id')
                ->on('seo_metas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_meta_translations');
        Schema::dropIfExists('seo_metas');
    }
};
