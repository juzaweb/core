<?php

namespace Juzaweb\Core\Media\Observes;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Core\Models\Media;

class MediaObserve
{
    /**
     * @param  Model|Media  $media
     * @return void
     */
    public function forceDeleted(Model $media): void
    {
        collect($media->conversions ?? [])->each(
            fn($conversion) => $media->filesystem()->delete($conversion)
        );
    }
}
