<?php

namespace Juzaweb\Modules\Core\FileManager;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\FileManager\Contracts\ImageConversion;
use Juzaweb\Modules\Core\FileManager\Contracts\Media as MediaContract;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\FileManager\Events\UploadFileSuccess;
use Juzaweb\Modules\Core\FileManager\Exceptions\MediaException;
use Juzaweb\Modules\Core\FileManager\Jobs\PerformConversions;
use Juzaweb\Modules\Core\Models\Media;
use RuntimeException;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class MediaUploader
{
    /**
     * The name of the disk to use.
     *
     * @var string
     */
    protected string $disk = 'public';

    /**
     * The source of the file to upload.
     *
     * @var string|UploadedFile
     */
    protected string|UploadedFile $source;

    /**
     * The type of source.
     *
     * @var string<'path'|'url'|'file'>
     */
    protected string $sourceType = 'file';

    /**
     * The name of the file to upload.
     *
     * @var string
     */
    protected string $newFileName;

    /**
     * The name of the file to upload.
     *
     * @var ?string
     */
    protected ?string $name = null;

    /**
     * The name of the file to upload.
     *
     * @var ?string
     */
    protected ?string $parentId = null;

    /**
     * User who uploaded the file.
     *
     * @var Authenticatable|int|null
     */
    protected null|Authenticatable|int $user = null;

    /**
     * Force a specific path for the uploaded file.
     *
     * @var ?string
     */
    protected ?string $forcedPath = null;

    protected bool $uploaded = false;

    protected bool $overwrite = false;

    /**
     * Creates a new instance of MediaUploader with the given arguments.
     *
     * @param string|UploadedFile $source
     * @param string $disk
     * @param string $name
     *
     * @return static Returns the current instance of the class.
     */
    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function __construct(
        string|UploadedFile $source = null,
        string              $disk = 'public',
        string              $name = null
    ) {
        $this->source = $source;
        $this->disk = $disk;
        $this->name = $name;
    }

    /**
     * Set the source of the file to upload.
     *
     * @param string|UploadedFile $source
     * @return static Returns the current instance of the class.
     */
    public function source(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Set the disk for the uploader.
     *
     * @param string $disk The name of the disk.
     * @return static Returns the current instance of the class.
     */
    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Sets the name of the object and returns the current instance.
     *
     * @param string $name The name to set.
     * @return static The current instance.
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the user who uploaded the object and returns the current instance.
     *
     * @param Authenticatable|int $user The user to set.
     * @return static The current instance.
     */
    public function user(int|Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the parent id of the object and returns the current instance.
     *
     * @param int $parent The parent id to set.
     * @return static The current instance.
     */
    public function parent(int $parent): static
    {
        $this->parentId = $parent;

        return $this;
    }

    /**
     * Sets the folder id of the object and returns the current instance.
     *
     * @param string $folder The folder id to set.
     * @return static The current instance.
     */
    public function folder(?string $folder): static
    {
        $this->parentId = $folder;

        return $this;
    }

    /**
     * Force a specific path for the uploaded file (overwrite if exists).
     *
     * @param string $path The path to force (relative to disk root).
     * @return static The current instance.
     */
    public function forcePath(string $path): static
    {
        $this->forcedPath = $path;

        return $this;
    }

    /**
     * Set whether to overwrite existing files.
     *
     * @param  bool  $value
     * @return $this
     */
    public function overwrite(bool $value = true): static
    {
        $this->overwrite = $value;

        return $this;
    }

    /**
     * Upload the file to the storage.
     *
     * @param string|null $disk The name of the disk to upload to. If not provided, the
     *  disk set in the constructor will be used.
     * @return Media The uploaded file.
     *
     * @throws MediaException
     * @throws RuntimeException
     */
    public function upload(?string $disk = null): Media
    {
        $this->uploaded = true;

        if ($disk !== null) {
            $this->disk = $disk;
        }

        $folder = $this->getDirectory();

        // Upload the file to the storage.
        try {
            // If the source is a string and the file exists, use the path to the file instead.
            if (is_string($this->source) && file_exists($this->source)) {
                $this->sourceType = 'path';
                $this->source = $this->pathToUploadedFile($this->source);
            }

            // If the source is a string and is a valid URL, download the file and use the path to the file instead.
            if (is_string($this->source) && is_url($this->source)) {
                $this->source = $this->pathToUploadedFile($this->downloadFileUrl());
                $this->sourceType = 'url';
            }

            // If the source is not an instance of UploadedFile, throw an exception.
            if (!$this->source instanceof UploadedFile) {
                throw MediaException::fileNotFound($this->source);
            }

            app(MediaContract::class)->validateUploadedFile($this->source, $this->disk);

            if ($this->forcedPath) {
                $this->newFileName = basename($this->forcedPath);
                $folder = dirname($this->forcedPath);
                if ($folder === '.') {
                    $folder = '';
                }

                if ($folder && !$this->filesystem()->directoryExists($folder)) {
                    $this->filesystem()->makeDirectory($folder);
                }
            } else {
                $this->newFileName = $this->uniqueFileName($folder, $this->getFileName());
            }

            // Optimize the image if the image optimization is enabled.
            if (config('media.image-optimize')) {
                OptimizerChainFactory::create()->optimize(
                    $this->source->getRealPath()
                );
            }

            $upload = $this->filesystem()->putFileAs(
                $folder,
                $this->source,
                $this->newFileName
            );

            throw_if($upload === false, MediaException::failedToUpload($this->newFileName));

            // Create a new file instance from the uploaded file.
            $media = new Media(
                [
                    'disk' => $this->disk,
                    'path' => $this->forcedPath ?: $this->getDirectory($this->newFileName),
                    'name' => $this->getName(),
                    'extension' => $this->getExtension(),
                    'mime_type' => $this->source->getMimeType(),
                    'size' => $this->source->getSize(),
                    'parent_id' => $this->parentId,
                    'image_size' => $this->getImageSize(),
                    'type' => MediaType::FILE,
                ]
            );

            // Associate the file with the user if it is set.
            if ($this->user) {
                $media->uploadable()->associate($this->getUser());
            }

            // Save the file to the database.
            // Check if media exists with this path to avoid duplicates when forcing path
            if ($this->forcedPath) {
                $existingMedia = Media::where('disk', $this->disk)
                    ->where('path', $this->forcedPath)
                    ->first();

                if ($existingMedia) {
                    $media = $existingMedia;
                    // Update metadata if needed
                    $media->update([
                        'size' => $this->source->getSize(),
                        'mime_type' => $this->source->getMimeType(),
                        'image_size' => $this->getImageSize(),
                    ]);
                } else {
                    $media->save();
                }
            } else {
                $media->save();
            }

            if ($media->isImage() && ($conversions = app(ImageConversion::class)->getGlobalConversions())) {
                PerformConversions::dispatch($media, $conversions);
            }

            // If the source is a URL, delete the temporary directory.
            if ($this->sourceType === 'url') {
                File::deleteDirectory(dirname($this->source->getRealPath()));
            }
        } catch (Exception $e) {
            // If an error occurs, roll back the upload by deleting the file.
            $this->rollbackUpload();
            throw $e;
        }

        event(new UploadFileSuccess($media, $this->overwrite));

        return $media;
    }

    /**
     * Save the external URL to the database.
     *
     * @return Media
     * @throws GuzzleException
     */
    public function saveExternalUrl(): Media
    {
        $this->uploaded = true;

        if (!is_url($this->source)) {
            throw new MediaException('The source must be a valid URL.');
        }

        $client = new Client();
        try {
            $response = $client->head($this->source, [
                'timeout' => 10,
                'connect_timeout' => 5,
                'verify' => false,
            ]);

            $mimeType = $response->getHeaderLine('Content-Type');
            $size = $response->getHeaderLine('Content-Length');
        } catch (Exception) {
            $mimeType = 'application/octet-stream';
            $size = 0;
        }

        $this->newFileName = $this->getFileNameFromSource();

        // Create a new file instance from the uploaded file.
        $media = new Media(
            [
                'disk' => 'public',
                'path' => $this->source,
                'name' => $this->getName(),
                'extension' => pathinfo($this->source, PATHINFO_EXTENSION),
                'mime_type' => $mimeType,
                'size' => $size,
                'parent_id' => $this->parentId,
                'image_size' => null, // We can't get image size without downloading
                'type' => MediaType::FILE,
            ]
        );

        // Associate the file with the user if it is set.
        if ($this->user) {
            $media->uploadable()->associate($this->getUser());
        }

        // Save the file to the database.
        $media->save();

        return $media;
    }

    /**
     * Retrieves the name of the object.
     *
     * This method returns the value of the `name` property if it is set, otherwise it calls the `getFileNameFromSource`
     * method to retrieve the file name from the source and returns it.
     *
     * @return string The name of the object, or the file name from the source if the `name` property is not set.
     */
    public function getName(): string
    {
        return $this->name ?? $this->getFileNameFromSource();
    }

    /**
     * Retrieves the user model associated with the current instance.
     *
     * If the user model is not set, it falls back to the authenticated user
     * obtained from the `auth()` function.
     *
     * @return Authenticatable|Model The user model.
     */
    public function getUser(): Authenticatable
    {
        return $this->user ?? auth()->user();
    }

    /**
     * Retrieves the file name after sanitizing it using the `sanitizeFileName` method.
     *
     * @return string The sanitized file name.
     */
    public function getFileName(): string
    {
        return $this->sanitizeFileName(
            $this->getName(),
            $this->source->getClientMimeType()
        );
    }

    /**
     * Retrieves the original extension of the source file.
     *
     * @return string The original extension of the source file.
     */
    public function getExtension(): string
    {
        return $this->source->getClientOriginalExtension();
    }

    /**
     * Retrieves the size of the image.
     *
     * @return string|null The size of the image in the format "widthxheight", or null if the source is not an image.
     */
    public function getImageSize(): ?string
    {
        if (app(MediaContract::class)->isImage($this->source)) {
            $imageSize = getimagesize($this->source->getRealPath());

            return $imageSize ? $imageSize[0] . 'x' . $imageSize[1] : null;
        }

        return null;
    }

    /**
     * Retrieves an instance of the UploadedFile class for the given file path.
     *
     * @param string $path The path to the file.
     * @param bool $test (optional) Whether to test the file path. Defaults to false.
     * @return UploadedFile The instance of the UploadedFile class.
     */
    protected function pathToUploadedFile(string $path, bool $test = false): UploadedFile
    {
        return app(MediaContract::class)->pathToUploadedFile($path, $test);
    }

    /**
     * Sanitize the file name by removing any special characters and converting to lowercase.
     *
     * @param string $fileName
     * @param string|null $mimeType
     * @return string
     */
    protected function sanitizeFileName(string $fileName, ?string $mimeType = null): string
    {
        return app(MediaContract::class)->sanitizeFileName($fileName, $mimeType);
    }

    /**
     * Downloads a file from the specified source and saves it to the temporary storage disk.
     *
     * @return string The path to the downloaded file.
     * @throws GuzzleException
     */
    protected function downloadFileUrl(): string
    {
        $tmp = Storage::build([
            'driver' => 'local',
            'root' => storage_path('app/tmps'),
            'throw' => true,
        ]);
        $folder = Str::random(32);
        $fileName = $this->getFileNameFromSource();
        $filePath = $tmp->path($folder . '/' . $fileName);
        $tmp->makeDirectory($folder);

        (new Client())->request(
            'GET',
            $this->source,
            [
                'sink' => $filePath,
                'headers' => [
                    'timeout' => 30,
                    'connect_timeout' => 10,
                ]
            ]
        );

        return $filePath;
    }

    /**
     * Retrieves the file name from the source.
     *
     * This function checks if the source is an instance of the UploadedFile class. If it is,
     * it returns the original name of the file using the getClientOriginalName() method.
     * Otherwise, it extracts the file name from the source by splitting it at the '?' character
     * and taking the first part.
     *
     * @return string The file name extracted from the source.
     */
    protected function getFileNameFromSource(): string
    {
        if ($this->source instanceof UploadedFile) {
            return $this->source->getClientOriginalName();
        }

        return basename(explode('?', $this->source)[0]);
    }

    /**
     * Rollback the upload by deleting the file if it exists.
     *e name of the new file.
     * @return void
     */
    protected function rollbackUpload(): void
    {
        if (isset($this->newFileName)) {
            $filePath = $this->getDirectory($this->newFileName);

            if ($this->filesystem()->exists($filePath)) {
                $this->filesystem()->delete($filePath);
            }
        }

        if ($this->sourceType === 'url') {
            File::deleteDirectory(dirname($this->source->getRealPath()));
        }
    }

    protected function uniqueFileName(string $folder, string $fileName): string
    {
        /**
         * @var string $extension The file extension.
         */
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return time() . '-' . Str::random(32) . '.' . $extension;
    }

    /**
     * Generates a unique file name by appending a number to the given file name until a
     * non-existing file name is found.
     *
     * @param string $fileName The original file name.
     * @return string The unique file name.
     */
    protected function uniqueFileName2(string $folder, string $fileName): string
    {
        $i = 1;

        /**
         * @var string $fileNameWithoutExtension The file name without the extension.
         */
        $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

        /**
         * @var string $extension The file extension.
         */
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $fileNameWithoutExtension;

        if (config('media.cloud_upload_enabled')) {
            while (Storage::disk('cloud_write')->exists("{$folder}/{$newFileName}.{$extension}")) {
                $newFileName = $fileNameWithoutExtension . '-' . $i;
                $i++;
            }
        }

        if (empty($extension)) {
            return $newFileName;
        }

        return "{$newFileName}.{$extension}";
    }

    /**
     * Returns the filesystem instance for the current disk.
     *
     * @return Filesystem|FilesystemAdapter The filesystem instance.
     */
    protected function filesystem(): Filesystem|FilesystemAdapter
    {
        return Storage::disk($this->disk);
    }

    /**
     * Retrieves the directory for uploading files.
     *
     * This function generates a directory name based on the current date and checks if it exists in the filesystem.
     * If the directory does not exist, it creates it.
     *
     * @return string The directory path where files will be uploaded.
     */
    protected function getDirectory(?string $path = null): string
    {
        $folder = date('Y/m');

        if (!$this->filesystem()->directoryExists($folder)) {
            $this->filesystem()->makeDirectory($folder);
        }

        return $folder . '/' . ltrim($path, '/');
    }

    public function __destruct()
    {
        if (!$this->uploaded) {
            $this->upload();
        }
    }
}
