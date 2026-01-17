<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;

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
            'media',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('disk', 20)->index()->default('public');
                $table->nullableUuidMorphs('uploaded_by');
                $table->string('name');
                $table->string('type', 5)->index()->default(MediaType::FILE->value);
                $table->string('path', 190)->nullable();
                $table->string('mime_type', 100)->index()->nullable();
                $table->string('extension', 10)->index()->nullable();
                $table->string('image_size', 20)->nullable();
                $table->bigInteger('size')->default(0);
                $table->json('conversions')->nullable();
                $table->json('metadata')->nullable();
                $table->uuid('parent_id')->index()->nullable();
                $table->datetimes();

                $table->foreign('parent_id')
                    ->on('media')
                    ->references('id');
            }
        );

        Schema::create(
            'mediable',
            function (Blueprint $table) {
                $table->primary(['media_id', 'mediable_id', 'mediable_type', 'channel']);
                $table->uuid('media_id')->index();
                $table->string('mediable_id', 100);
                $table->string('mediable_type', 150);
                $table->string('channel', 50)->index();
                $table->datetimes();

                $table->index(['mediable_id', 'mediable_type']);
                $table->foreign('media_id')
                    ->on('media')
                    ->references('id')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('mediable');
        Schema::dropIfExists('media');
    }
};
