<?php

namespace Juzaweb\Modules\Core\FileManager;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Juzaweb\Modules\Core\FileManager\Contracts\ImageConversion;
use Juzaweb\Modules\Core\FileManager\Contracts\Media;
use Juzaweb\Modules\Core\FileManager\Exceptions\MediaException;
use Juzaweb\Modules\Core\Models\Media as MediaModel;
use Symfony\Component\Mime\MimeTypes;
use function Juzaweb\Modules\Admin\FileManager\website;

class MediaRepository implements Media
{
    /**
     * Uploads a file to the storage.
     *
     * @param  string|UploadedFile|null  $source  The source of the file to upload. It can be a string
     *                                            representing the path to the file or an instance of
     *                                            UploadedFile. Defaults to null.
     * @param  string  $disk  The name of the disk to upload to. Defaults to 'public'.
     * @param  string|null  $name  The name of the file. Defaults to null.
     * @return MediaUploader  The instance of MediaUploader used to upload the file.
     */
    public function upload(
        string|UploadedFile|null $source = null,
        string $disk = 'public',
        ?string $name = null
    ): MediaUploader {
        // Create a new instance of MediaUploader and return it.
        return new MediaUploader($source, $disk, $name);
    }

    /**
     * Returns the extension based on the mime type.
     *
     * If the mime type is unknown, returns null.
     *
     * @param  string  $mimeType
     * @return string|null The guessed extension or null if it cannot be guessed
     *
     * @see MimeTypes
     */
    public function guessExtension(string $mimeType): ?string
    {
        return MimeTypes::getDefault()->getExtensions($mimeType)[0] ?? null;
    }

    /**
     * Generate a human-readable byte count string.
     *
     * @param  int  $bytes
     * @param  int  $precision
     * @return string
     */
    public function readableSize(int $bytes, int $precision = 1): string
    {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        if ($bytes === 0) {
            return '0 '.$units[0];
        }

        $exponent = (int) floor(log($bytes, 1024));
        $value = $bytes / (1024 ** $exponent);

        return round($value, $precision).' '.$units[$exponent];
    }

    /**
     * Sanitize the file name.
     *
     * @param  string  $fileName
     * @param  string|null  $mimeType
     * @return string
     * @throws MediaException
     */
    public function sanitizeFileName(string $fileName, ?string $mimeType = null): string
    {
        // Remove any special characters and convert to lowercase.
        $fileNameWithoutExtension = Str::slug(pathinfo($fileName, PATHINFO_FILENAME));
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (empty($extension) && $mimeType) {
            $extension = $this->guessExtension($mimeType);
        }

        // Throw an exception if the file extension is not found.
        if (empty($extension)) {
            // return $fileNameWithoutExtension;
            throw MediaException::extensionNotFound('File extension not found');
        }

        // Return the sanitized file name with the extension.
        return "{$fileNameWithoutExtension}.{$extension}";
    }

    /**
     * Create an UploadedFile object from absolute path
     *
     * @static
     * @param  string  $path
     * @param  bool  $public  default false
     * @return UploadedFile
     */
    public function pathToUploadedFile(string $path, bool $test = false): UploadedFile
    {
        $originalName = File::name($path);
        $extension = File::extension($path);

        if ($extension) {
            $originalName = "{$originalName}.{$extension}";
        }

        return new UploadedFile($path, $originalName, File::mimeType($path), File::size($path), $test);
    }

    /**
     * Determines if the given file is an image.
     *
     * @param UploadedFile|string $file The file to check. Can be an instance of UploadedFile or a string
     * representing the path to the file.
     * @return bool Returns true if the file is an image, false otherwise.
     */
    public function isImage(UploadedFile|string $file): bool
    {
        if ($file instanceof UploadedFile) {
            $mimeType = $file->getClientMimeType();
        } else {
            $mimeType = File::mimeType($file);
        }

        return in_array($mimeType, config('media.types.image', []));
    }

    public function convert(MediaModel $media, string $conversion, string $toPath): Image
    {
        $filesystem = $media->filesystem();
        $converter = app(ImageConversion::class)->get($conversion);

        /** @var Image $image */
        $image = $converter(
            app(ImageManager::class)->make(
                $filesystem->readStream($media->getPath())
            )
        );

        $filesystem->put($toPath, $image->stream());

        return $image;
    }

    public function validateUploadedFile(UploadedFile $file, string $disk): void
    {
        if (($mimeTypes = config("media.disks.{$disk}.mime_types", []))
            && !in_array($file->getMimeType(), $mimeTypes)
        ) {
            throw MediaException::mimeTypeNotSupported($file->getClientMimeType(), $mimeTypes);
        }

        if (($extensions = config("media.disks.{$disk}.extensions", []))
            && !in_array($file->getClientOriginalExtension(), $extensions)) {
            throw MediaException::extensionNotSupported($extensions);
        }

        if (($maxSize = config("media.disks.{$disk}.max_size", [])) && $file->getSize() > $maxSize) {
            throw MediaException::maxFileSizeExceeded($maxSize);
        }

        // Check storage limit from subscription plan
        $website = website();
        if ($website) {
            $storageLimit = $website->getFeatureValue('network', 'storage') * 1024 * 1024;

            $currentUsed = MediaModel::getTotalStorageUsed();
            $fileSize = $file->getSize();

            if (($currentUsed + $fileSize) > $storageLimit) {
                throw MediaException::storageLimitExceeded($currentUsed, $storageLimit, $fileSize);
            }
        }
    }
}
