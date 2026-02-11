<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\FileManager\Listeners;

use Juzaweb\Modules\Core\FileManager\Events\UploadFileSuccess;
use Juzaweb\Modules\Core\FileManager\Jobs\UploadToCloudJob;

class UploadToCloudListener
{
    /**
     * Handle the event.
     *
     * @param UploadFileSuccess $event
     * @return void
     */
    public function handle(UploadFileSuccess $event): void
    {
        // Check if cloud upload is enabled
        if (!config('media.cloud_upload_enabled', false)) {
            return;
        }

        // Get the cloud disk name from configuration
        $cloudDisk = 'cloud';

        // Get the source disk from the media or use configured default
        $sourceDisk = $event->media->disk ?? config('juzaweb.filemanager.disk', 'public');

        // Skip if source and target are the same
        if ($sourceDisk === $cloudDisk) {
            return;
        }

        // Dispatch the upload job
        UploadToCloudJob::dispatch($event->media, $sourceDisk, false, $event->overwrite);
    }
}
