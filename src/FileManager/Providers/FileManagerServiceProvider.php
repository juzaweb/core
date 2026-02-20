<?php

namespace Juzaweb\Modules\Core\FileManager\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Juzaweb\Modules\Core\FileManager\Contracts\ImageConversion;
use Juzaweb\Modules\Core\FileManager\Contracts\Media;
use Juzaweb\Modules\Core\FileManager\Events\UploadFileSuccess;
use Juzaweb\Modules\Core\FileManager\ImageConversionRepository;
use Juzaweb\Modules\Core\FileManager\Listeners\UploadToCloudListener;
use Juzaweb\Modules\Core\FileManager\MediaRepository;
use Juzaweb\Modules\Core\FileManager\Observes\MediaObserve;
use Juzaweb\Modules\Core\Models\Media as MediaModel;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        MediaModel::observe(MediaObserve::class);

        // Register event listener for cloud upload
        Event::listen(UploadFileSuccess::class, UploadToCloudListener::class);
    }

    public function register(): void
    {
        $this->app->singleton(Media::class, MediaRepository::class);

        $this->app->singleton(ImageConversion::class, ImageConversionRepository::class);
    }
}
