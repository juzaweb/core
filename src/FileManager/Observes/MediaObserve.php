<?php

namespace Juzaweb\Modules\Core\FileManager\Observes;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Models\Media;

class MediaObserve
{
    /**
     * @param  Model|Media  $media
     * @return void
     */
    public function forceDeleted(Model $media): void
    {
        $media->filesystem()->delete($media->path);

        collect($media->conversions ?? [])->each(
            fn($conversion) => $media->filesystem()->delete($conversion)
        );
    }
}
