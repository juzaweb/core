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
        Schema::create('languages', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('name', 100);
            $table->timestamps();
        });

        DB::table('languages')->insert([
            [
                'code' => 'en',
                'name' => 'English',
            ],
            [
                'code' => 'vi',
                'name' => 'Vietnamese',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
