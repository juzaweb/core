<?php

namespace Juzaweb\Core\Media;

use Illuminate\Support\Facades\Storage;
use Juzaweb\Core\Media\Exceptions\MediaException;
use Juzaweb\Core\Models\Media;

/**
 * @mixin \Illuminate\Http\Request
 */
class RequestMethodsForMedia
{
    /**
     * Get the media model from the request input.
     *
     * @param string $key
     * @param mixed $default
     * @return Media|null
     */
    public function media(): \Closure
    {
        return function (string $key, mixed $default = null) {
            if (! $this->has($key)) {
                return $default;
            }

            return app(Media::class)->find($this->input($key)) ?? $default;
        };
    }

    /**
     * Get the media models from the request input.
     *
     * @param string $key
     * @param mixed $default
     * @return \Illuminate\Support\Collection|null
     */
    public function medias(): \Closure
    {
        return function (string $key, mixed $default = null) {
            if (! $this->has($key)) {
                return $default;
            }

            $ids = $this->input($key, []);

            if (! is_array($ids)) {
                $ids = [$ids];
            }

            return app(Media::class)->findMany($ids) ?? $default;
        };
    }

    /**
     * Get the media uploader instance from the request input.
     *
     * @param string $key
     * @param mixed $default
     * @return MediaUploader|null
     */
    public function shouldUpload(): \Closure
    {
        return function (string $key, mixed $default = null) {
            if (! $this->has($key)) {
                return $default;
            }

            return new MediaUploader($this->input($key));
        };
    }

    /**
     * Get the media uploader instance from the request input which is located in the tmp disk.
     *
     * @param string $key
     * @param mixed $default
     * @return MediaUploader|null
     *
     */
    public function uploadedTmpFile(): \Closure
    {
        return function (string $key, mixed $default = null) {
            if (! $this->has($key)) {
                return $default;
            }

            $path = $this->input($key);

            if (! Storage::disk('tmp')->exists($path)) {
                throw MediaException::fileNotFound($path);
            }

            return new MediaUploader(Storage::disk('tmp')->path($path));
        };
    }
}
