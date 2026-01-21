<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Juzaweb\Modules\Admin\Models\Guest;
use Juzaweb\Modules\Admin\Networks\Facades\Network;
use Juzaweb\Modules\Core\Contracts\Viewable;
use Juzaweb\Modules\Core\Http\Controllers\Controller;
use Juzaweb\Modules\Core\Services\ImgProxyService;

class AddonController extends Controller
{
    public function statuses(Request $request): JsonResponse
    {
        $key = "site:users_online";
        $ip = client_ip();

        try {
            Redis::zadd($key, [$ip => time()]);
            Redis::zremrangebyscore($key, 0, time() - 300);
        } catch (\Throwable $e) {
            report($e);
        }

        if ($viewPage = $request->input('view-page')) {
            [$class, $id] = decrypt(base64url_decode($viewPage));
            $viewer = $request->user() ?? Guest::firstOrCreate(
                [
                    'ipv4' => client_ip(),
                ],
                [
                    'user_agent' => $request->userAgent(),
                ]
            );

            if (is_subclass_of($class, Viewable::class)) {
                $item = app($class)->find($id);
                $item?->incrementViews($viewer, $ip);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function thumbnail(Request $request)
    {
        $text = $request->query('text', 'L');
        $width = (int) $request->query('width', 200);
        $height = (int) $request->query('height', 200);
        $length = (int) $request->query('length', 2);

        $char = $length > 0 ? strtoupper(mb_substr($text, 0, $length)) : $text;
        $bgColor = $request->query('bg', '#3490dc');

        $manager = new ImageManager(new Driver());

        $img = $manager->create($width, $height)->fill($bgColor);

        $shorterSide = min($width, $height);
        $fontSize = (int) ($shorterSide * 0.5);

        $img->text($char, $width / 2, $height / 2, function ($font) use ($fontSize) {
            $font->filename(__DIR__ . '/../../../resources/assets/fonts/arial.ttf');
            $font->size($fontSize);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        return response($img->encodeByExtension('png'))
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    public function showFromCloud(string $path, Request $request)
    {
        $disk = Storage::disk('cloud_write');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $size = $disk->size($path);
        $lastModified = $disk->lastModified($path);

        // Xác định MimeType
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeType = $this->getMimeType($extension);

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
        if ($rangeHeader = $request->header('Range')) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
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
        }

        $contentLength = $end - $start + 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Length' => $contentLength,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
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

                    if ($chunk === false) break;

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

    /**
     * Hàm hỗ trợ lấy MimeType
     */
    private function getMimeType(string $extension): string
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            'svg'         => 'image/svg+xml',
            'mp4'         => 'video/mp4',
            'pdf'         => 'application/pdf',
            'css'         => 'text/css',
            'js'          => 'application/javascript',
            'woff'        => 'font/woff',
            'woff2'       => 'font/woff2',
            'ttf'         => 'font/ttf',
            'otf'         => 'font/otf',
            'eot'         => 'application/vnd.ms-fontobject',
            default       => 'application/octet-stream',
        };
    }

    /**
     * Handle image proxy request
     */
    public function proxy(string $methodParam, string $hash, Request $request, ImgProxyService $imgProxyService)
    {
        try {
            // Parse method and dimensions from methodParam (e.g., "resize:800x600")
            $method = $methodParam;
            $width = null;
            $height = null;

            if (str_contains($methodParam, ':')) {
                [$method, $size] = explode(':', $methodParam, 2);
                [$width, $height] = array_pad(explode('x', $size), 2, null);
                $width = $width === 'auto' ? null : (int)$width;
                $height = $height === 'auto' ? null : (int)$height;
            }

            // Handle image via service
            $result = $imgProxyService->handle($method, $hash, $width, $height);

            // Clear any output buffers
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Return response with proper headers
            return response($result['data'])
                ->header('Content-Type', $result['contentType'])
                ->header('Content-Length', $result['contentLength'])
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Accel-Buffering', 'no')
                ->header('Cache-Control', 'public, max-age=31536000');
        } catch (\InvalidArgumentException $e) {
            return response('Error: ' . $e->getMessage(), 400)
                ->header('Content-Type', 'text/plain');
        } catch (\Throwable $e) {
            return response('Error: ' . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Proxy files from storage_path('app/public')
     */
    public function storageProxy(Request $request, string $path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath) || !is_file($filePath)) {
            abort(404);
        }

        $size = filesize($filePath);
        $lastModified = filemtime($filePath);

        // Get MIME type
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = $this->getMimeType($extension);

        $etag = md5($path . $lastModified);

        // Handle cache
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304);
        }

        // Handle range requests
        $start = 0;
        $end = $size - 1;
        $statusCode = 200;

        if ($rangeHeader = $request->header('Range')) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
                $start = (int) $matches[1];
                if (!empty($matches[2])) {
                    $end = (int) $matches[2];
                }

                $end = min($end, $size - 1);
                if ($start <= $end) {
                    $statusCode = 206;
                } else {
                    return response('', 416, ['Content-Range' => "bytes */{$size}"]);
                }
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
        ];

        if ($statusCode === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(
            function () use ($filePath, $start, $contentLength) {
                // Clear output buffers
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $stream = fopen($filePath, 'rb');
                if (!is_resource($stream)) {
                    return;
                }

                if ($start > 0) {
                    fseek($stream, $start);
                }

                $bufferSize = 8192; // 8KB chunks
                $remaining = $contentLength;

                while (!feof($stream) && $remaining > 0) {
                    $toRead = min($bufferSize, $remaining);
                    $chunk = fread($stream, $toRead);

                    if ($chunk === false) {
                        break;
                    }

                    echo $chunk;
                    $remaining -= strlen($chunk);

                    flush();
                }

                fclose($stream);
            },
            $statusCode,
            $headers
        );
    }

    /**
     * Proxy files from themes directory
     */
    public function themesProxy(Request $request, string $path)
    {
        $filePath = base_path('themes/' . $path);

        return $this->serveStaticFile($request, $filePath, $path);
    }

    /**
     * Proxy files from modules directory
     */
    public function modulesProxy(Request $request, string $path)
    {
        $filePath = base_path('modules/' . $path);

        return $this->serveStaticFile($request, $filePath, $path);
    }

    /**
     * Serve static file with caching and range support
     */
    private function serveStaticFile(Request $request, string $filePath, string $path)
    {
        if (!file_exists($filePath) || !is_file($filePath)) {
            abort(404);
        }

        $size = filesize($filePath);
        $lastModified = filemtime($filePath);

        // Get MIME type
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = $this->getMimeType($extension);

        $etag = md5($path . $lastModified);

        // Handle cache
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304);
        }

        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
        ];

        // For small files (CSS/JS/fonts), serve directly without streaming
        // Streaming is only beneficial for large files or when range requests are needed
        $maxDirectServeSize = 5 * 1024 * 1024; // 5MB

        if ($size <= $maxDirectServeSize) {
            // Serve small files directly
            $content = file_get_contents($filePath);

            return response($content, 200, array_merge($headers, [
                'Content-Length' => $size,
            ]));
        }

        // For large files, use streaming with range request support
        $start = 0;
        $end = $size - 1;
        $statusCode = 200;

        if ($rangeHeader = $request->header('Range')) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
                $start = (int) $matches[1];
                if (!empty($matches[2])) {
                    $end = (int) $matches[2];
                }

                $end = min($end, $size - 1);
                if ($start <= $end) {
                    $statusCode = 206;
                } else {
                    return response('', 416, ['Content-Range' => "bytes */{$size}"]);
                }
            }
        }

        $contentLength = $end - $start + 1;

        $headers['Content-Length'] = $contentLength;
        $headers['Accept-Ranges'] = 'bytes';

        if ($statusCode === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(
            function () use ($filePath, $start, $contentLength) {
                // Clear output buffers
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $stream = fopen($filePath, 'rb');
                if (!is_resource($stream)) {
                    return;
                }

                if ($start > 0) {
                    fseek($stream, $start);
                }

                $bufferSize = 8192; // 8KB chunks
                $remaining = $contentLength;

                while (!feof($stream) && $remaining > 0) {
                    $toRead = min($bufferSize, $remaining);
                    $chunk = fread($stream, $toRead);

                    if ($chunk === false) {
                        break;
                    }

                    echo $chunk;
                    $remaining -= strlen($chunk);

                    flush();
                }

                fclose($stream);
            },
            $statusCode,
            $headers
        );
    }
}
