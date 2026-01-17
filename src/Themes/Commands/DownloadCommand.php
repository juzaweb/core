<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Themes\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

abstract class DownloadCommand extends Command
{
    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function setDataDefault(string $key, string $value): void
    {
        $data = Cache::get('html_download', []);

        $data[$key] = $value;

        Cache::forever('html_download', $data);
    }

    protected function getDataDefault(string $key, ?string $default = null): ?string
    {
        $data = Cache::get('html_download', []);

        return Arr::get($data, $key, $default);
    }

    protected function downloadFile(string $url, string $path): void
    {
        $folder = dirname($path);

        if (!is_dir($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        try {
            // Normalize URL: đảm bảo là URL tuyệt đối
            if (!preg_match('/^https?:\/\//i', $url)) {
                throw new \InvalidArgumentException("Invalid URL: {$url}");
            }

            $response = $this->client->request('GET', $url, [
                'sink' => $path,
                'verify' => false,
                'allow_redirects' => true,
                'http_errors' => false, // không throw exception cho lỗi HTTP (vd: 404)
                'timeout' => 30,
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                // Xóa file nếu tải lỗi (404, 403, 500, ...)
                if (File::exists($path)) {
                    File::delete($path);
                }

                throw new \RuntimeException("Failed to download file ({$statusCode}) from {$url}");
            }

            // Kiểm tra kích thước file > 0
            if (File::exists($path) && File::size($path) === 0) {
                File::delete($path);
                throw new \RuntimeException("Downloaded file is empty: {$url}");
            }

        } catch (RequestException $e) {
            if (File::exists($path)) {
                File::delete($path);
            }

            throw new \RuntimeException("HTTP request failed for {$url}: " . $e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            if (File::exists($path)) {
                File::delete($path);
            }

            throw $e;
        }
    }

    protected function getFileContent(string $url): string
    {
        return $this->client->request(
            'GET',
            $url,
            [
                'verify' => false
            ]
        )->getBody()->getContents();
    }

    protected function curlGet(string $url): string
    {
        $curl = $this->client->get(
            $url,
            [
                'timeout' => 10,
                'verify' => false
            ]
        );

        return $curl->getBody()->getContents();
    }

    protected function isExcludeDomain(string $url): bool
    {
        return in_array(
            $this->getDomainUrl($url),
            [
                'fonts.googleapis.com',
                'maps.googleapis.com',
            ]
        );
    }

    protected function getFontExtentions(): array
    {
        return ['woff', 'woff2', 'ttf', 'eot', 'otf'];
    }

    protected function getDomainUrl(string $url): string
    {
        return get_domain_by_url($url);
    }
}
