<?php

declare(strict_types=1);

namespace Juzaweb\Modules\Core\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use RuntimeException;

class ImgProxyService extends BaseService
{
    protected string $cacheDir;
    protected ImageManager $imageManager;
    protected ?S3Client $s3Client = null;
    protected array $allowedMethods = ['resize', 'original', 'cover', 'crop'];

    public function __construct()
    {
        $this->cacheDir = storage_path('app/imgproxy-cache');

        // Initialize ImageManager with configurable driver
        $driver = strtolower(config('image.driver', 'gd'));
        $this->imageManager = $driver === 'imagick'
            ? new ImageManager(new ImagickDriver())
            : new ImageManager(new GdDriver());

        // Create cache directory if not exists
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        // Initialize S3 client if cloud storage is configured
        $cloudConfig = config('filesystems.disks.cloud');
        if (
            !empty($cloudConfig['key']) &&
            !empty($cloudConfig['bucket'])
        ) {
            $this->s3Client = new S3Client([
                'version' => 'latest',
                'region' => $cloudConfig['region'] ?? 'us-east-1',
                'endpoint' => $cloudConfig['endpoint'] ?? null,
                'use_path_style_endpoint' => !empty($cloudConfig['endpoint']),
                'credentials' => [
                    'key' => $cloudConfig['key'],
                    'secret' => $cloudConfig['secret'],
                ],
                'http' => [
                    'connect_timeout' => 5,
                    'timeout' => 10,
                ],
                'stats' => false,
            ]);
        }
    }

    /**
     * Handle image proxy request
     */
    public function handle(string $method, string $hash, ?int $width, ?int $height): array
    {
        // Validate method
        if (!in_array($method, $this->allowedMethods, true)) {
            throw new InvalidArgumentException('Invalid method');
        }

        // Decrypt URL from hash
        $url = $this->decryptUrl($hash);

        // Get image data
        [$imageData, $contentType, $extension] = $this->getImage($url);

        // Read image
        $image = $this->readImage($imageData);

        // Apply watermark if needed
        $image = $this->applyWatermarkIfNeeded($image);

        // Process image based on method
        $processedImage = $this->processImage($image, $method, $width, $height);

        // Determine output format
        $isWebpRequested = ($extension === 'webp' || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'image/webp'));

        // Encode image
        $encodedData = $isWebpRequested
            ? $processedImage->toWebp()
            : $processedImage->encode();

        return [
            'data' => $encodedData,
            'contentType' => $isWebpRequested ? 'image/webp' : $contentType,
            'contentLength' => strlen((string)$encodedData),
        ];
    }

    /**
     * Decrypt URL from hash
     */
    protected function decryptUrl(string $hash): string
    {
        $key = sha1(config('app.key'));
        $url = decrypt_deterministic($hash, $key);

        if (!$url) {
            throw new InvalidArgumentException('Invalid hash');
        }

        return $url;
    }

    /**
     * Get image from URL (local, S3, or remote)
     */
    protected function getImage(string $url): array
    {
        $cloudConfig = config('filesystems.disks.cloud');

        // Check if image is from cloud storage (S3)
        if (!empty($cloudConfig['url']) && str_contains($url, $cloudConfig['url'])) {
            return $this->getImageFromS3($url);
        }

        // Check if image is from local storage
        $publicConfig = config('filesystems.disks.public');
        $storageUrl = $publicConfig['url'] ?? '/storage';

        if (str_contains($url, $storageUrl)) {
            $path = str_replace($storageUrl, '', $url);
            $fullPath = $publicConfig['root'] . '/' . ltrim($path, '/');

            if (is_file($fullPath)) {
                $imageData = file_get_contents($fullPath);
                $contentType = mime_content_type($fullPath);
                $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                return [$imageData, $contentType, $extension];
            }
        }

        // Try to get from cache or fetch remote
        $cachePath = $this->cacheDir . '/' . sha1($url);

        // Clean up cache periodically
        $this->cleanupCache();

        if (is_file($cachePath) && !$this->isExpired($cachePath)) {
            $imageData = file_get_contents($cachePath);
            $contentType = mime_content_type($cachePath);
        } else {
            [$imageData, $contentType] = $this->fetchRemoteImage($url);
            file_put_contents($cachePath, $imageData, LOCK_EX);
        }

        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        return [$imageData, $contentType, $extension];
    }

    /**
     * Get image from S3
     */
    protected function getImageFromS3(string $url): array
    {
        if (!$this->s3Client) {
            throw new RuntimeException('S3 client not initialized');
        }

        $cloudConfig = config('filesystems.disks.cloud');

        // Extract path from cloud URL
        $path = str_replace($cloudConfig['url'], '', $url);
        $key = ltrim($path, '/');

        // Generate cache path for S3 image
        $cachePath = $this->cacheDir . '/s3_' . sha1($path);

        // Check if cached version exists and is not expired
        if (is_file($cachePath) && !$this->isExpired($cachePath)) {
            $imageData = file_get_contents($cachePath);
            $contentType = mime_content_type($cachePath);
            $extension = strtolower(pathinfo($key, PATHINFO_EXTENSION));

            return [$imageData, $contentType, $extension];
        }

        try {
            $result = $this->s3Client->getObject([
                'Bucket' => $cloudConfig['bucket'],
                'Key' => $key,
            ]);

            $body = $result['Body'];
            $contentType = $result['ContentType'] ?? 'image/jpeg';
            $extension = strtolower(pathinfo($key, PATHINFO_EXTENSION));

            // Save to cache efficiently using stream
            $resource = $body->detach();
            file_put_contents($cachePath, $resource, LOCK_EX);

            // Read from cache file
            $imageData = file_get_contents($cachePath);

            return [$imageData, $contentType, $extension];
        } catch (\Throwable $e) {
            throw new RuntimeException('S3 Fetch Error: ' . $e->getMessage());
        }
    }

    /**
     * Fetch image from remote URL
     */
    protected function fetchRemoteImage(string $url): array
    {
        $context = stream_context_create([
            'http' => ['timeout' => 10, 'follow_location' => 1],
        ]);

        $data = @file_get_contents($url, false, $context);
        if ($data === false) {
            throw new RuntimeException('Failed to fetch image');
        }

        $contentType = $this->getHeaderContentType($http_response_header ?? []);
        return [$data, $contentType];
    }

    /**
     * Extract Content-Type from HTTP headers
     */
    protected function getHeaderContentType(array $headers): string
    {
        foreach ($headers as $header) {
            if (stripos($header, 'Content-Type:') === 0) {
                return trim(substr($header, 13));
            }
        }
        return 'image/jpeg';
    }

    /**
     * Check if cache file is expired
     */
    protected function isExpired(string $file): bool
    {
        $days = config('cache.imgproxy_expiry_days', 30);
        $expiry = time() - ($days * 86400);
        return filemtime($file) < $expiry;
    }

    /**
     * Cleanup expired and oversized cache
     */
    protected function cleanupCache(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        $maxMB = config('cache.imgproxy_max_size_mb', 1024);
        $expiryDays = config('cache.imgproxy_expiry_days', 30);

        $files = glob($this->cacheDir . '/*');
        if (!$files) {
            return;
        }

        $totalSize = 0;
        $fileData = [];

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $size = filesize($file);
            $totalSize += $size;
            $fileData[$file] = [
                'mtime' => filemtime($file),
                'size' => $size,
            ];

            // Delete expired files
            if ($expiryDays > 0 && $this->isExpired($file)) {
                @unlink($file);
                unset($fileData[$file]);
            }
        }

        // Enforce cache size limit
        if ($maxMB > 0) {
            $maxBytes = $maxMB * 1024 * 1024;
            if ($totalSize > $maxBytes) {
                // Sort by oldest first
                uasort($fileData, fn($a, $b) => $a['mtime'] <=> $b['mtime']);

                foreach ($fileData as $file => $info) {
                    if ($totalSize <= $maxBytes) {
                        break;
                    }
                    $totalSize -= $info['size'];
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Read image from data
     */
    protected function readImage(string $data)
    {
        try {
            return $this->imageManager->read($data);
        } catch (\Throwable $e) {
            throw new RuntimeException('Invalid image data');
        }
    }

    /**
     * Apply watermark if needed based on host
     */
    protected function applyWatermarkIfNeeded($image)
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $watermarkHosts = config('image.watermark_hosts', []);

        if (!in_array($host, $watermarkHosts, true)) {
            return $image;
        }

        $watermarkPath = resource_path('images/watermark.png');
        if (is_file($watermarkPath)) {
            $image->place($watermarkPath, 'bottom-right', 10, 10, 25);
        }

        return $image;
    }

    /**
     * Process image based on method
     */
    protected function processImage($image, string $method, ?int $width, ?int $height)
    {
        if ($method === 'resize') {
            return $image->scale($width, $height);
        } elseif ($method === 'cover') {
            return $image->cover($width ?? $image->width(), $height ?? $image->height());
        } elseif ($method === 'crop') {
            return $image->crop($width ?? $image->width(), $height ?? $image->height());
        }

        // original - no processing
        return $image;
    }
}
