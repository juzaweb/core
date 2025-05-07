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
            'notification_logs',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('channel', 190)->index();
                $table->nullableUuidMorphs('notifiable');
                $table->string('notification_type', 190)->index();
                $table->json('extra')->nullable();
                $table->dateTime('sent_at')->nullable();
                $table->dateTime('delivered_at')->nullable();
                $table->string('status', 20)->default('sending')->index();
                $table->json('failed_data')->nullable();
                $table->timestamps();

                $table->index(['created_at']);
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
        Schema::dropIfExists('notification_logs');
    }
};
