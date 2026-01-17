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
            $table->id();
            $table->string('code', 10)->index();
            $table->string('name', 100);
            $table->datetimes();

            $table->unique(['code', 'website_id']);
        });

        DB::table('languages')->insert([
            [
                'code' => 'en',
                'name' => 'English',
                'website_id' => config('network.main_website_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'vi',
                'name' => 'Vietnamese',
                'website_id' => config('network.main_website_id'),
                'created_at' => now(),
                'updated_at' => now(),
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
