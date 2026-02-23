<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Models\Media;

class SendMediaToCloudCommand extends Command
{
    protected $signature = 'media:send-to-cloud {--disk= : The disk to move from}';

    protected $description = 'Send media files to cloud storage';

    public function handle(): int
    {
        if (empty(config('filesystems.disks.cloud'))) {
            $this->error('Cloud disk not configured.');
            return 1;
        }

        $query = Media::where('in_cloud', false)
            ->where('type', '!=', 'dir');

        if ($disk = $this->option('disk')) {
            $query->where('disk', $disk);
        }

        $ids = $query->pluck('id');
        $count = $ids->count();

        $this->info("Found {$count} files to move to cloud.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($ids->chunk(100) as $chunkIds) {
            $medias = Media::whereIn('id', $chunkIds)->get();
            foreach ($medias as $media) {
                try {
                    $this->moveMedia($media);
                } catch (\Exception $e) {
                    $this->error("Failed to move {$media->path}: {$e->getMessage()}");
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Media files moved to cloud successfully.');

        return 0;
    }

    protected function moveMedia(Media $media): void
    {
        $disk = Storage::disk($media->disk);
        if (!$disk->exists($media->path)) {
            // $this->warn("File not found: {$media->path}");
            return;
        }

        $stream = $disk->readStream($media->path);
        cloud(true)->put($media->path, $stream);

        if ($media->conversions) {
            foreach ($media->conversions as $conversion) {
                $cPath = $conversion['path'];
                if ($disk->exists($cPath)) {
                    $cStream = $disk->readStream($cPath);
                    cloud(true)->put($cPath, $cStream);
                }
            }
        }

        $media->in_cloud = true;
        $media->disk = 'cloud';
        $media->save();
    }
}
