<?php

namespace Juzaweb\Modules\Core\FileManager\Contracts;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;
use Juzaweb\Modules\Core\FileManager\MediaUploader;
use Juzaweb\Modules\Core\Models\Media as MediaModel;
use Symfony\Component\Mime\MimeTypes;

interface Media
{
    public function upload(
        string|UploadedFile|null $source = null,
        string $disk = 'public',
        ?string $name = null
    ): MediaUploader;

    /**
     * Returns the extension based on the mime type.
     *
     * If the mime type is unknown, returns null.
     *
     * @return string|null The guessed extension or null if it cannot be guessed
     *
     * @see MimeTypes
     */
    public function guessExtension(string $mimeType): ?string;

    /**
     * Generate a human-readable byte count string.
     */
    public function readableSize(int $bytes, int $precision = 1): string;

    /**
     * Sanitize the file name.
     */
    public function sanitizeFileName(string $fileName): string;

    public function pathToUploadedFile(string $path, bool $test = false): UploadedFile;

    public function convert(MediaModel $media, string $conversion, string $toPath): Image;

    public function validateUploadedFile(UploadedFile $file, string $disk): void;
}
