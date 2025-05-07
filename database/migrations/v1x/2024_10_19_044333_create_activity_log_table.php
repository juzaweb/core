<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))
            ->create(
                config('activitylog.table_name'),
                function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('log_name')->nullable();
                    $table->text('description');
                    $table->string('subject_type', 150)->nullable();
                    $table->string('subject_id', 150)->nullable();
                    $table->uuid('causer_id')->nullable();
                    $table->string('causer_type', 150)->nullable();
                    $table->json('properties')->nullable();
                    $table->string('event')->nullable();
                    $table->uuid('batch_uuid')->index()->nullable();
                    $table->timestamps();

                    $table->index(['log_name']);
                    $table->index(['subject_type', 'subject_id']);
                    $table->index(['causer_type', 'causer_id']);
                }
            );
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))
            ->dropIfExists(config('activitylog.table_name'));
    }
};
