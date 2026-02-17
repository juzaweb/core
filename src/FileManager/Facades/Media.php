<?php

namespace Juzaweb\Modules\Core\FileManager\Facades;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use Intervention\Image\Image;
use Juzaweb\Modules\Core\Models\Media as MediaModel;

/**
 * @method static MediaUploader upload(string|UploadedFile $source = null, string $disk = 'public', string $name = null)
 * @method static string guessExtension(string $mimeType)
 * @method static string readableSize(int $bytes, int $precision = 1)
 * @method static string sanitizeFileName(string $fileName)
 * @method static string pathToUploadedFile(string $path, bool $test = false)
 * @method static Image convert(MediaModel $media, string $conversion, string $toPath)
 * @method static string getImageSize(string $path)
 * @see \Juzaweb\Modules\Core\FileManager\Contracts\Media
 */
class Media extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\FileManager\Contracts\Media::class;
    }
}
