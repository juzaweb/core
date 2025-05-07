<?php

namespace Juzaweb\Core\Media\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;
use Juzaweb\Core\Media\MediaUploader;
use Juzaweb\Core\Models\Media as MediaModel;
use Symfony\Component\Mime\MimeTypes;

interface Media
{
    public function upload(
        string|UploadedFile $source = null,
        string $disk = 'public',
        string $name = null
    ): MediaUploader;

    /**
     * Returns the extension based on the mime type.
     *
     * If the mime type is unknown, returns null.
     *
     * @param  string $mimeType
     * @return string|null The guessed extension or null if it cannot be guessed
     *
     * @see MimeTypes
     */
    public function guessExtension(string $mimeType): ?string;

    /**
     * Generate a human-readable byte count string.
     *
     * @param  int $bytes
     * @param  int $precision
     * @return string
     */
    public function readableSize(int $bytes, int $precision = 1): string;

    /**
     * Sanitize the file name.
     *
     * @param string $fileName
     * @return string
     */
    public function sanitizeFileName(string $fileName): string;

    public function pathToUploadedFile(string $path, bool $test = false): UploadedFile;

    public function convert(MediaModel $media, string $conversion, string $toPath): Image;

    public function validateUploadedFile(UploadedFile $file, string $disk): void;
}
