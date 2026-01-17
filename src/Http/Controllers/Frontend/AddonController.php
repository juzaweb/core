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

class AddonController extends Controller
{
    public function statuses(Request $request): JsonResponse
    {
        $siteId = Network::website()->id;
        $key = "site:{$siteId}:users_online";
        $ip = client_ip();

        Redis::zadd($key, [$ip => time()]);
        Redis::zremrangebyscore($key, 0, time() - 300);

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

    public function recaptcha(Request $request): JsonResponse
    {
        $nonce = $request->input('nonce');
        $csrf = $request->header('X-CSRF-TOKEN');
        $hash = Network::website()->id . substr($csrf, 9, 16);

        if ($nonce !== hash('sha256', hash('sha256', $hash))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid nonce',
            ], 403);
        }

        $now = now()->format('Y-m-d H:i:s');
        $token = hash('sha256', config('app.key') . Network::website()->id);

        return response()->json([
            'success' => true,
            't' => $now,
            'k' => $token,
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
            default       => 'application/octet-stream',
        };
    }
}
