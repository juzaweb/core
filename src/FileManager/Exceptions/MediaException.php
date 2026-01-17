<?php

namespace Juzaweb\Modules\Core\FileManager\Exceptions;

use Juzaweb\Modules\Core\FileManager\Facades\Media;

class MediaException extends \Exception
{
    public static function extensionNotFound(string $name): static
    {
        return new static("File {$name} extension not found");
    }

    public static function fileNotFound(string $filename): static
    {
        return new static("File {$filename} not found");
    }

    public static function failedToUpload(string $filename): static
    {
        return new static("Failed to upload file {$filename}");
    }

    public static function mimeTypeNotSupported(string $meimeType, array $supportedTypes): static
    {
        return new static("File mime type {$meimeType} not supported, supported types: ".implode(', ', $supportedTypes));
    }

    public static function extensionNotSupported(array $extensions): static
    {
        return new static("File extension not supported, supported extensions: ".implode(', ', $extensions));
    }

    public static function maxFileSizeExceeded(int $maxSize): static
    {
        return new static('Maximum file size exceeded, max size: '. Media::readableSize($maxSize));
    }

    public static function storageLimitExceeded(int $currentUsed, int $maxStorage, int $fileSize): static
    {
        return new static(
            trans(
                'admin::translation.storage_limit_exceeded',
                [
                    'current' => Media::readableSize($currentUsed),
                    'limit' => Media::readableSize($maxStorage),
                    'file_size' => Media::readableSize($fileSize),
                ]
            )
        );
    }
}
