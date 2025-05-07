<?php

namespace Juzaweb\Core\Media\Exceptions;

use Juzaweb\Core\Facades\Media;

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
}
