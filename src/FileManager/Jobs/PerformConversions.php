<?php

namespace Juzaweb\Modules\Core\FileManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Juzaweb\Modules\Core\Models\Media;

class PerformConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(protected Media|Collection $media, protected array $conversions)
    {
    }

    public function handle(): void
    {
        if ($this->media instanceof Collection) {
            $this->media->each(fn (Media $media) => $this->convertMedia($media));
        } else {
            $this->convertMedia($this->media);
        }
    }

    protected function convertMedia(Media $media): Media
    {
        $filesystem = $media->filesystem();
        $conversions = [];
        foreach ($this->conversions as $conversion) {
            if ($media->getPath($conversion)) {
                continue;
            }

            $path = $media->generateConversionPath($conversion);
            $image = app(\Juzaweb\Modules\Core\FileManager\Contracts\Media::class)->convert($media, $conversion, $path);

            $conversions[$conversion] = [
                'path' => $path,
                'size' => $filesystem->size($path),
                'image_size' => $image->getWidth().'x'.$image->getHeight(),
            ];
        }

        $media->conversions = array_merge($media->conversions ?? [], $conversions);
        $media->save();
        return $media;
    }
}
