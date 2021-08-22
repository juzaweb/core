<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Manager;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Juzaweb\Core\Support\Curl;
use Juzaweb\Core\Support\JuzawebApi;
use Juzaweb\Core\Version;

class UpdateManager
{
    protected $curl;
    protected $api;

    public function __construct
    (
        Curl $curl,
        JuzawebApi $api
    ) {
        $this->curl = $curl;
        $this->api = $api;
    }

    public function checkUpdate($tag = 'core', $val = '')
    {
        if ($this->getVersionAvailable($tag, $val) > $this->getCurrentVersion()) {
            return true;
        }

        return false;
    }

    public function getCurrentVersion()
    {
        return Version::getVersion();
    }

    public function getVersionAvailable($tag = 'core', $val = '')
    {
        $uri = ($val ? $tag . '/'. $val : $tag) . '/version-available';

        $response = $this->api->get($uri, [
            'current_version' => $this->getCurrentVersion(),
        ]);

        return $response->version;
    }

    public function update($tag = 'core', $val = '')
    {
        $uri = ($val ? $tag . '/'. $val : $tag) . '/get-update';

        $response = $this->api->get($uri, [
            'current_version' => $this->getCurrentVersion(),
        ]);

        if (empty($response->download)) {
            return false;
        }

        $tmp = Storage::disk('tmp');

        foreach ($response->download as $file) {
            $tmpFolder = $tag . '/' . Str::random(5);
            foreach (['zip', 'unzip', 'backup'] as $folder) {
                if ($tmp->exists($tmpFolder . '/' . $folder)) {
                    File::makeDirectory($tmp->exists($tmpFolder . '/' . $folder), 0775, true);
                }
            }

            $tmpFile = $tmpFolder . '/zip/' . Str::random(10) . '.zip';
            $tmpFile = $tmp->path($tmpFile);

            if (!$this->downloadFile($file['link'], $tmpFile)) {
                return false;
            }

            $zip = new \ZipArchive();
            $op = $zip->open($tmpFile);

            if ($op !== true) {
                return false;
            }

            $zip->extractTo($tmp->path($tmpFolder . '/unzip'));
            $zip->close();

            $localFolder = $this->getLocalFolder($file, $tag, $val);
            File::moveDirectory($localFolder, $tmp->path($tmpFolder . '/backup'));
            File::moveDirectory($tmp->path($tmpFolder . '/unzip'), $localFolder);
            File::deleteDirectory($tmpFolder, true);
        }

        return true;
    }

    protected function getLocalFolder($file, $tag = 'core', $val = '')
    {
        switch ($tag) {
            case 'core':
                return base_path('vendor')[$file['path']];
            case 'plugin':
                return plugin_path($val);
            case 'theme':
                return theme_path($val);
        }

        return false;
    }

    protected function downloadFile($url, $filename)
    {
        $resource = Utils::tryFopen($filename, 'w');

        try {
            $this->curl->getClient()->request('GET', $url, [
                'curl' => [
                    CURLOPT_TCP_KEEPALIVE => 10,
                    CURLOPT_TCP_KEEPIDLE => 10
                ],
                'sink' => $resource,
            ]);

            return $filename;

        } catch (\Throwable $e) {
            Log::error($e);
        }

        return false;
    }
}