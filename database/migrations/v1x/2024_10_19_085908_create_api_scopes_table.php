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
        Schema::create('api_scope_groups', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('api_scopes', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name');
            $table->string('group_code', 50)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_scope_groups');
        Schema::dropIfExists('api_scopes');
    }
};
