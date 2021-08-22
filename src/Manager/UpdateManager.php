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
use Illuminate\Support\Facades\Log;
use Juzaweb\Core\Support\Curl;
use Juzaweb\Core\Support\JuzawebApi;
use Juzaweb\Core\Version;

class UpdateManager
{
    protected $curl;
    protected $api;

    public function __construct(
        Curl $curl,
        JuzawebApi $api
    )
    {
        $this->curl = $curl;
        $this->api = $api;
    }

    public function checkUpdate($tag = 'core')
    {


        return true;
    }

    public function getCurrentVersion()
    {
        return Version::getVersion();
    }

    public function getVersionAvailable($tag = 'core')
    {

    }

    public function update($tag = 'core')
    {

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

            return true;

        } catch (\Throwable $e) {
            Log::error($e);
        }

        return false;
    }
}