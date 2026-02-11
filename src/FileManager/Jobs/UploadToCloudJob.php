<?php

namespace Juzaweb\Modules\Core\FileManager\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Models\Media;

class UploadToCloudJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public array $backoff = [60, 300, 900]; // 1min, 5min, 15min

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     *
     * @param Media $media The media file to upload to cloud
     * @param string $sourceDisk The source disk where the file is stored
     */
    public function __construct(
        protected Media $media,
        protected string $sourceDisk = 'public',
        protected bool $trash = false,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $path = $this->media->path;

        // Check if source file exists
        if (!Storage::disk($this->sourceDisk)->exists($path)) {
            Log::warning("Cloud upload failed: Source file not found", [
                'media_id' => $this->media->id,
                'path' => $path,
                'source_disk' => $this->sourceDisk,
            ]);
            return;
        }

        $disk = cloud(true);

        // Check if file already exists on cloud or already marked as uploaded
        if ($this->media->in_cloud || $disk->exists($path)) {
            throw new \RuntimeException("File already exists on cloud storage or marked as uploaded: {$path}");
        }

        // Get file contents from source disk
        $fileContents = Storage::disk($this->sourceDisk)->get($path);

        // Upload to cloud disk
        $uploaded = $disk->put($path, $fileContents, [
            'visibility' => 'public',
            'ContentType' => $this->media->mime_type,
            'overwrite' => false,
        ]);

        if ($uploaded) {
            $this->media->update(['in_cloud' => true]);

            if ($this->trash) {
                if (!Storage::disk('trash')->exists(dirname($path))) {
                    Storage::disk('trash')->makeDirectory(dirname($path));
                }

                File::move(
                    Storage::disk($this->sourceDisk)->path($path),
                    Storage::disk('trash')->path($path)
                );
            } else {
                Storage::disk($this->sourceDisk)->delete($path);
            }
        } else {
            throw new \RuntimeException("Failed to upload file to cloud storage");
        }
    }
}
