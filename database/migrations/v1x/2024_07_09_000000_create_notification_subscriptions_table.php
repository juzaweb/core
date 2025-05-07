<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notification_subscriptions',
            function (Blueprint $table) {
                $table->id();
                $table->string('channel', 150)->index();
                $table->uuidMorphs('notifiable');
                $table->json('data')->nullable();
                $table->timestamps();

                $table->unique(
                    ['channel', 'notifiable_type', 'notifiable_id'],
                    'notification_subscriptions_channel_notifiable_unique'
                );
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
        Schema::dropIfExists('notification_subscriptions');
    }
};
