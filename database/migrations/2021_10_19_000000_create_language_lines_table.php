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
        Schema::create(
            'language_lines',
            function (Blueprint $table) {
                $table->id();
                $table->string('namespace', 50)->index();
                $table->string('group', 50)->index();
                $table->string('key', 150)->index();
                $table->json('text');
                $table->datetimes();
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
        Schema::dropIfExists('language_lines');
    }
};
