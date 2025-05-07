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
        Schema::create('translate_histories', function (Blueprint $table) {
            $table->id();
            $table->string('translateable_type')->index();
            $table->string('translateable_id', 50)->index();
            $table->string('locale', 10)->index();
            $table->string('status', 10)->index()->default('pending');
            $table->json('error')->nullable();
            $table->timestamps();

            $table->unique(['translateable_type', 'translateable_id', 'locale'], 'translate_histories_locale_unique');
            $table->index(['translateable_type', 'translateable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translate_histories');
    }
};
