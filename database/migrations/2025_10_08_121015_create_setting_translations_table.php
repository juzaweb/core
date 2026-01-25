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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('translatable')->default(false)->after('code');
        });

        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->string('setting_code', 100)->index();
            $table->string('locale', 10)->index();
            $table->text('lang_value')->nullable();

            $table->foreignId('setting_id')
                ->constrained('settings')
                ->onDelete('cascade');
            $table->datetimes();

            $table->unique(['setting_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('translatable');
        });
        Schema::dropIfExists('setting_translations');
    }
};
