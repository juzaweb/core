<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Admin\Database\Factories\MediaFactory;
use Juzaweb\Modules\Core\FileManager\Contracts\Media as MediaContract;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;

class Media extends Model
{
    use HasAPI, HasFactory, HasUuids, UsedInFrontend, SoftDeletes;

    protected Filesystem $filesystem;

    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'name',
        'conversions',
        'path',
        'parent_id',
        'mime_type',
        'size',
        'type',
        'metadata',
        'extension',
        'image_size',
        'disk',
        'in_cloud',
    ];

    protected $casts = [
        'conversions' => 'array',
        'metadata' => 'array',
        'type' => MediaType::class,
        'in_cloud' => 'boolean',
    ];

    protected $appends = [
        'url',
        'is_directory',
        'readable_size',
        'is_image',
        'is_video',
        'file_type',
    ];

    protected array $filterable = [
        'parent_id',
        'extension',
        'type',
        'accept',
        'root',
        'file_type',
    ];

    protected array $searchable = [
        'name',
        'extension',
    ];

    protected array $sortable = [
        'name',
        'extension',
    ];

    protected array $sortDefault = [
        'type' => 'asc',
        'id' => 'desc',
    ];

    public static function findByPath(string $path, ?string $disk = 'public', array $columns = ['*']): ?Model
    {
        return static::query()->where('path', $path)
            ->when($disk, fn($q) => $q->where('disk', $disk))
            ->first($columns);
    }

    /**
     * Calculate the total storage used by all files in the current website.
     *
     * @return int Total size in bytes
     */
    public static function getTotalStorageUsed(): int
    {
        return (int) static::query()
            ->where('type', MediaType::FILE)
            ->sum('size');
    }

    protected static function newFactory(): MediaFactory
    {
        return MediaFactory::new();
    }

    public function uploadable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'uploaded_by_type', 'uploaded_by_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function parents(): BelongsTo
    {
        return $this->parent()->with('parents');
    }

    public function scopeWhereRoot(Builder $builder): Builder
    {
        return $builder->whereNull('parent_id');
    }

    public function scopeRootFilterable(Builder $builder, array $params = []): Builder
    {
        $root = filter_var($params['root'], FILTER_VALIDATE_BOOLEAN);

        if ($root) {
            return $builder->whereRoot();
        }

        return $builder;
    }

    public function scopeWhereFileType(Builder $builder, string|array $fileType): Builder
    {
        if (!is_array($fileType)) {
            $fileType = [$fileType];
        }

        return $builder->where(
            function (Builder $q) use ($fileType) {
                $q->whereRaw('1 = 0');
                foreach ($fileType as $type) {
                    switch ($type) {
                        case 'image':
                            $q->orWhereIn('mime_type', self::IMAGE_MIME_TYPES);
                            break;
                        case 'video':
                            $q->orWhereIn('mime_type', self::VIDEO_MIME_TYPES);
                            break;
                        case 'audio':
                            $q->orWhereIn('mime_type', self::AUDIO_MIME_TYPES);
                            break;
                        case 'document':
                            $q->orWhereIn('mime_type', self::DOCUMENT_MIME_TYPES);
                            break;
                        case 'media':
                            $q->orWhereIn('mime_type', array_merge(self::VIDEO_MIME_TYPES, self::AUDIO_MIME_TYPES));
                            break;
                        case 'file':
                            $q->orWhereNotIn('mime_type', self::IMAGE_MIME_TYPES);
                            break;
                        default:
                            $q->whereRaw('1 = 0');
                            break;
                    }
                }
            }
        );
    }

    public function scopeAcceptFilterable(Builder $builder, array $params = []): Builder
    {
        $accept = array_filter(explode(',', $params['accept']), 'trim');

        if (empty($accept)) {
            return $builder;
        }

        return $builder->where(
            function (Builder $q) use ($accept) {
                $q->where('type', MediaType::DIRECTORY);
                $q->orWhereIn('mime_type', $accept);
            }
        );
    }

    public function scopeFileTypeFilterable(Builder $builder, array $params = []): Builder
    {
        $fileType = array_filter(explode(',', $params['file_type']), 'trim');

        if (empty($fileType)) {
            return $builder;
        }

        return $builder->where(
            function (Builder $q) use ($fileType) {
                $q->where('type', MediaType::DIRECTORY);

                foreach ($fileType as $type) {
                    switch ($type) {
                        case 'image':
                            $q->orWhereIn('mime_type', self::IMAGE_MIME_TYPES);
                            break;
                        case 'video':
                            $q->orWhereIn('mime_type', self::VIDEO_MIME_TYPES);
                            break;
                        case 'audio':
                            $q->orWhereIn('mime_type', self::AUDIO_MIME_TYPES);
                            break;
                        case 'document':
                            $q->orWhereIn('mime_type', self::DOCUMENT_MIME_TYPES);
                            break;
                        case 'media':
                            $q->orWhereIn('mime_type', array_merge(self::VIDEO_MIME_TYPES, self::AUDIO_MIME_TYPES));
                            break;
                        case 'file':
                            $q->orWhereNotIn('mime_type', self::IMAGE_MIME_TYPES);
                            break;
                        default:
                            $q->whereRaw('1 = 0');
                            break;
                    }
                }
            }
        );
    }

    /**
     * Get the original media url.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if ($this->type == MediaType::DIRECTORY) {
            return null;
        }

        return $this->getUrl();
    }

    /**
     * Determine if the media is a directory.
     *
     * @return bool
     */
    public function getIsDirectoryAttribute(): bool
    {
        return $this->isDirectory();
    }

    /**
     * Get the readable size attribute of the media.
     *
     * @return string The human-readable size of the media.
     */
    public function getReadableSizeAttribute(): string
    {
        return $this->readableSize();
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->isVideo();
    }

    public function getFileTypeAttribute(): string
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isVideo()) {
            return 'video';
        }

        if ($this->isAudio()) {
            return 'audio';
        }

        if ($this->isDocument()) {
            return 'document';
        }

        return 'file';
    }

    public function isDirectory(): bool
    {
        return $this->type === MediaType::DIRECTORY;
    }

    public function isFile(): bool
    {
        return ! $this->isDirectory();
    }

    public function getIsImageAttribute(): bool
    {
        return $this->isImage();
    }

    public function isImage(): bool
    {
        return in_array(
            $this->mime_type,
            config('media.types.image', [])
        );
    }

    public function isVideo(): bool
    {
        return in_array($this->mime_type, config('media.types.video', []));
    }

    public function isAudio(): bool
    {
        return in_array($this->mime_type, config('media.types.audio', []));
    }

    public function isDocument(): bool
    {
        return in_array($this->mime_type, config('media.types.document', []));
    }

    public function readableSize(int $precision = 1): string
    {
        return app(MediaContract::class)->readableSize($this->size, $precision);
    }

    /**
     * Get the url to the file.
     *
     * @param string $conversion
     * @return string
     */
    public function getUrl(?string $conversion = null): ?string
    {
        if ($this->disk !== 'public') {
            return null;
        }

        $path = $this->getPath($conversion);

        if (is_url($path)) {
            return $path;
        }

        // return $this->filesystem()->url($path);

        return upload_url($path);
    }

    public function temporaryUrl($expiration, array $options = []): string
    {
        return $this->filesystem()->temporaryUrl(
            $this->path,
            $expiration,
            $options
        );
    }

    /**
     * Get the full path to the file.
     *
     * @param string|null $conversion
     * @return string|null
     */
    public function getFullPath(?string $conversion = null): null|string
    {
        return $this->filesystem()->path(
            $this->getPath($conversion)
        );
    }

    /**
     * Get the path to the file on disk.
     *
     * @param string|null $conversion
     * @return string|null
     */
    public function getPath(?string $conversion = null): ?string
    {
        if ($conversion) {
            return $this->conversions[$conversion]['path'] ?? null;
        }

        return $this->path;
    }

    /**
     * Get the url of the file by its path.
     *
     * @param string $path The path of the file
     * @return string|null The url of the file, or null if it doesn't exist
     */
    public function getUrlByPath(string $path): ?string
    {
        // Get the url of the file using the filesystem
        return $this->filesystem()->url($path);
    }

    /**
     * Get the collection of conversions.
     *
     * @return Collection
     */
    public function collectConversion(): Collection
    {
        return collect($this->conversions ?? []);
    }

    /**
     * Generate the path for the given conversion.
     *
     * @param string $conversion
     * @return string
     */
    public function generateConversionPath(string $conversion): string
    {
        $folder = date('Y/m/d');

        $folder = "{$folder}/conversions/{$conversion}";

        if ($this->filesystem()->directoryMissing($folder)) {
            $this->filesystem()->makeDirectory($folder);
        }

        return "{$folder}/{$this->name}";
    }

    /**
     * Get the conversion response for the given media.
     *
     * This function is used to generate the srcset attribute for the img tag.
     * The srcset attribute is a comma-separated string of image URLs and their
     * corresponding widths.
     *
     * @return array The response containing the conversion URLs and their image sizes
     */
    public function getConversionResponse(): array
    {
        if (! $this->isImage()) {
            return [];
        }

        // srcset="elva-fairy-480w.jpg 480w, elva-fairy-800w.jpg 800w"
        $conversions = $this->collectConversion()->prepend(
            ['path' => $this->path, 'image_size' => $this->image_size],
            'origin'
        );

        $response = $conversions->mapWithKeys(
            fn($item, $conversion) => [$conversion => $this->getUrlByPath($item['path'])]
        );

        $srcset = $response->map(
            function ($url, $conversion) use ($conversions) {
                $size = $conversions[$conversion]['image_size'] ?? 'autoxauto';
                $width = explode('x', $size)[0];
                return "{$url} {$width}w";
            }
        )->implode(', ');

        return $response->put('srcset', $srcset)->toArray();
    }

    /**
     * Move the media file to a different disk and update its path.
     *
     * @param string $disk The name of the disk to move the file to.
     * @param string|null $path The new path for the file, or null to keep the same path.
     * @return Media The updated media instance.
     */
    public function move(string $disk, ?string $path = null): Media
    {
        $path = $path ?? $this->path;

        $this->filesystem()->move($this->path, $path);

        $this->disk = $disk;
        $this->path = $path;
        $this->save();

        $this->collectConversion()->each(
            function ($conversion, $name) use ($disk, $path) {
                $conversionPath = $this->generateConversionPath($name);

                $this->filesystem()->move($conversion['path'], $conversionPath);
            }
        );

        return $this;
    }

    /**
     * Get the filesystem where the associated file is stored.
     *
     * @return Filesystem|FilesystemAdapter
     */
    public function filesystem(): Filesystem|FilesystemAdapter
    {
        if ($this->in_cloud) {
            return cloud(true);
        }

        return $this->filesystem ??= Storage::disk($this->disk);
    }

    public function download(string $filename)
    {
        if ($this->in_cloud) {
            return $this->streamFromCloud(request(), $filename);
        }

        return $this->filesystem()->download($this->path, basename($filename));
    }

    public function streamFromCloud(Request $request, string $filename)
    {
        $disk = cloud(true);
        $path = $this->path;

        if (!$disk->exists($path)) {
            abort(404);
        }

        $size = $disk->size($path);
        $lastModified = $disk->lastModified($path);

        // Xác định MimeType
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeType = mime_type_from_extension($extension);

        $etag = md5($path . $lastModified);

        // 1. Xử lý Cache - Tiết kiệm băng thông
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304);
        }

        // 2. Thiết lập các thông số Range mặc định
        $start = 0;
        $end = $size - 1;
        $statusCode = 200;

        // 3. Xử lý Range Request (Hữu ích cho Video/Audio và ảnh lớn)
        if (($rangeHeader = $request->header('Range'))
            && preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)
        ) {
            $start = (int) $matches[1];
            if (!empty($matches[2])) {
                $end = (int) $matches[2];
            }

            // Giới hạn phạm vi hợp lệ
            $end = min($end, $size - 1);
            if ($start <= $end) {
                $statusCode = 206;
            } else {
                // Range không hợp lệ (ví dụ start > size)
                return response('', 416, ['Content-Range' => "bytes */{$size}"]);
            }
        }

        $contentLength = $end - $start + 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Length' => $contentLength,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ];

        if ($statusCode === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(
            function () use ($disk, $path, $start, $contentLength) {
                // Xóa mọi output buffer đang tồn tại để tránh hỏng dữ liệu binary
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $stream = $disk->readStream($path);
                if (!is_resource($stream)) {
                    return;
                }

                // Nếu không phải bắt đầu từ đầu, ta cần di chuyển con trỏ
                if ($start > 0) {
                    // Với S3, fseek có thể không hoạt động tùy thuộc vào driver
                    // Cách an toàn nhất là đọc và bỏ qua phần đầu (hoặc dùng S3 Range Get)
                    fseek($stream, $start);
                }

                $bufferSize = 8192; // 8KB mỗi lần đọc
                $remaining = $contentLength;

                while (!feof($stream) && $remaining > 0) {
                    $toRead = min($bufferSize, $remaining);
                    $chunk = fread($stream, $toRead);

                    if ($chunk === false) {
                        break;
                    }

                    echo $chunk;
                    $remaining -= strlen($chunk);

                    flush(); // Đẩy dữ liệu về trình duyệt ngay lập tức
                }

                fclose($stream);
            },
            $statusCode,
            $headers
        );
    }
}
